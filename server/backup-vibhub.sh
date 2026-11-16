#!/usr/bin/env bash
# =====================================================================
#  Tägliche Sicherung von vib-hub.com
#  Gesichert werden: Datenbank, hochgeladene Dateien (storage/app, z. B. Logo)
#  und die .env (enthält den APP_KEY – ohne ihn sind z. B. die
#  Zwei-Faktor-Schlüssel der Admins nicht mehr lesbar).
#
#  Einmal einrichten (als root):   bash /var/www/vibhub/app/server/backup-vibhub.sh install
#  Von Hand sichern:               backup-vibhub
#  Sicherungen ansehen:            ls -lh /var/backups/vibhub /var/backups/vibhub/woche
#
#  Einstellungen (optional) in /etc/default/vibhub-backup:
#    ZIEL=/var/backups/vibhub        Ablageort
#    TAGE=14                         tägliche Sicherungen so viele Tage aufheben
#    WOCHEN=8                        Sonntags-Sicherungen so viele Wochen aufheben
#    REMOTE=benutzer@host:/pfad/     zusätzlich per rsync auf einen anderen Rechner kopieren
#
#  Wiederherstellen (Beispiel):
#    mkdir /tmp/rueck && tar -C /tmp/rueck -xf /var/backups/vibhub/vibhub-DATUM.tar
#    gunzip -c /tmp/rueck/datenbank.sql.gz | mysql vibhub
#    tar -C /var/www/vibhub/app -xzf /tmp/rueck/dateien.tar.gz   # storage/app und .env
#    chown -R vibhub:vibhub /var/www/vibhub/app/storage && sudo -u vibhub php /var/www/vibhub/app/artisan optimize
# =====================================================================
set -euo pipefail

APPDIR=/var/www/vibhub/app
DB=vibhub
ZIEL=/var/backups/vibhub
TAGE=14
WOCHEN=8
REMOTE=
[ -f /etc/default/vibhub-backup ] && . /etc/default/vibhub-backup

[ "$(id -u)" = 0 ] || { echo "Bitte als root ausführen (sudo backup-vibhub)."; exit 1; }

if [ "${1:-}" = install ]; then
  install -m 750 "$0" /usr/local/bin/backup-vibhub
  cat > /etc/cron.d/vibhub-backup <<'CRON'
# vib-hub.com: tägliche Sicherung um 3:17 Uhr (Protokoll: /var/log/vibhub-backup.log)
17 3 * * * root /usr/local/bin/backup-vibhub >> /var/log/vibhub-backup.log 2>&1
CRON
  chmod 644 /etc/cron.d/vibhub-backup
  mkdir -p "$ZIEL/woche" && chmod 700 "$ZIEL"
  echo "Eingerichtet: täglich um 3:17 Uhr nach $ZIEL. Erste Sicherung läuft jetzt …"
  exec /usr/local/bin/backup-vibhub
fi

umask 077
mkdir -p "$ZIEL/woche"
chmod 700 "$ZIEL"
STAMP=$(date +%F_%H%M)
TMP=$(mktemp -d "$ZIEL/.tmp-XXXXXX")
trap 'rm -rf "$TMP"' EXIT

echo "$(date '+%F %T') Sicherung startet"

# Datenbank: konsistenter Stand ohne die Website anzuhalten
mysqldump --single-transaction --quick --routines --triggers --default-character-set=utf8mb4 "$DB" | gzip -9 > "$TMP/datenbank.sql.gz"
gzip -t "$TMP/datenbank.sql.gz"
[ "$(gunzip -c "$TMP/datenbank.sql.gz" | head -c 4096 | wc -c)" -gt 100 ] || { echo "FEHLER: Datenbank-Sicherung ist leer."; exit 1; }

# Hochgeladene Dateien und Zugangsdaten
tar -C "$APPDIR" -czf "$TMP/dateien.tar.gz" storage/app .env

DATEI="$ZIEL/vibhub-$STAMP.tar"
tar -C "$TMP" -cf "$DATEI" datenbank.sql.gz dateien.tar.gz
chmod 600 "$DATEI"

# Sonntags zusätzlich eine Wochensicherung behalten
[ "$(date +%u)" = 7 ] && cp -p "$DATEI" "$ZIEL/woche/"

# Alte Sicherungen löschen
find "$ZIEL" -maxdepth 1 -name 'vibhub-*.tar' -mtime +"$TAGE" -delete
find "$ZIEL/woche" -maxdepth 1 -name 'vibhub-*.tar' -mtime +$((WOCHEN * 7)) -delete

# Optional: Kopie auf einen anderen Rechner
if [ -n "$REMOTE" ]; then
  rsync -a --delete "$ZIEL/" "$REMOTE" && echo "Kopie nach $REMOTE übertragen"
fi

echo "$(date '+%F %T') Fertig: $DATEI ($(du -h "$DATEI" | cut -f1))"
