#!/usr/bin/env bash
# =====================================================================
#  vib-hub.com als eigenes Laravel-Projekt auf diesem Server einrichten
#  – komplett getrennt von anderen Projekten: eigener Linux-Benutzer "vibhub",
#    eigener PHP-Prozess, eigene Datenbank, eigener GitHub-Schlüssel.
#
#  Voraussetzung: GitHub-Repo (z. B. willisamson/vib-hub) mit dem Laravel-Projekt.
#  Aufruf (Server):  bash /var/www/vibhub/app/server/setup-vibhub.sh deine@email.de
#  Kann gefahrlos erneut gestartet werden.
# =====================================================================
set -euo pipefail

EMAIL="${1:-}"
if [ -z "$EMAIL" ]; then echo "Aufruf: bash setup-vibhub.sh deine@email.de"; exit 1; fi

REPO="${REPO:-willisamson/vib-hub}"
DOMAIN="vib-hub.com"
BASE=/var/www/vibhub
APPDIR=$BASE/app
ENVFILE=$APPDIR/.env

exec > >(tee -a /root/setup-vibhub.log) 2>&1
step(){ echo; echo "=================== $* ==================="; }
frage(){ echo; echo ">>> $*"; }
SERVER_IP=$(hostname -I | awk '{print $1}')
PHPV=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
SOCK="/run/php/php$PHPV-fpm-vibhub.sock"
secret(){ local f=$1; [ -s "$f" ] || { openssl rand -hex 20 > "$f"; chmod 600 "$f"; }; cat "$f"; }

# ---------------------------------------------------------------------
step "1/9 Sicherung der bisherigen Nginx-Einstellungen"
# ---------------------------------------------------------------------
SICHERUNG=/root/nginx-sicherung-$(date +%F-%H%M)
cp -a /etc/nginx "$SICHERUNG"
echo "Gesichert nach $SICHERUNG (zurück: rm -rf /etc/nginx && cp -a $SICHERUNG /etc/nginx && systemctl reload nginx)"

# ---------------------------------------------------------------------
step "2/9 Benutzer, PHP-Prozess, Datenbank"
# ---------------------------------------------------------------------
id vibhub >/dev/null 2>&1 || useradd -m -s /bin/bash vibhub
mkdir -p "$BASE" "$BASE/acme" && chown vibhub:vibhub "$BASE" && chmod 755 "$BASE" "$BASE/acme"

cat > "/etc/php/$PHPV/fpm/pool.d/vibhub.conf" <<EOF
[vibhub]
user = vibhub
group = vibhub
listen = $SOCK
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 6
pm.start_servers = 1
pm.min_spare_servers = 1
pm.max_spare_servers = 2
EOF
php-fpm$PHPV -t
systemctl reload "php$PHPV-fpm"   # reload: andere Projekte auf dem Server laufen ohne Unterbrechung weiter
for i in 1 2 3 4 5; do [ -S "$SOCK" ] && break; sleep 1; done
[ -S "$SOCK" ] || { echo "FEHLER: PHP-Prozess für vibhub startet nicht."; exit 1; }

DBPASS=$(secret /root/.vh-dbpass)
mysql -e "CREATE DATABASE IF NOT EXISTS vibhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS 'vibhub'@'localhost' IDENTIFIED BY '$DBPASS'; ALTER USER 'vibhub'@'localhost' IDENTIFIED BY '$DBPASS';"
mysql -e "GRANT ALL PRIVILEGES ON \`vibhub\`.* TO 'vibhub'@'localhost'; FLUSH PRIVILEGES;"
echo "Datenbank 'vibhub' bereit (Benutzer vibhub darf nur diese eine Datenbank)."

# ---------------------------------------------------------------------
step "3/9 GitHub: eigener Lesezugriff für vib-hub"
# ---------------------------------------------------------------------
sudo -u vibhub -H bash -c 'mkdir -p ~/.ssh && chmod 700 ~/.ssh && ssh-keyscan github.com >> ~/.ssh/known_hosts 2>/dev/null'
[ -f /home/vibhub/.ssh/github ] || sudo -u vibhub -H ssh-keygen -q -t ed25519 -N "" -f /home/vibhub/.ssh/github -C "vibhub-server-deploy"
sudo -u vibhub -H bash -c 'printf "Host github.com\n  IdentityFile ~/.ssh/github\n  IdentitiesOnly yes\n" > ~/.ssh/config && chmod 600 ~/.ssh/config'

repo_ok(){ sudo -u vibhub -H git ls-remote "git@github.com:$REPO.git" >/dev/null 2>&1; }
if ! repo_ok; then
  frage "Der Server braucht Lesezugriff auf github.com/$REPO."
  frage "GitHub → Repo $REPO → Settings → Deploy keys → Add deploy key"
  frage "Titel: Server, KEIN Schreibzugriff, diese Zeile einfügen:"
  echo
  cat /home/vibhub/.ssh/github.pub
  echo
  until repo_ok; do
    read -rp "    Wenn eingetragen, Enter drücken … " _
    repo_ok || echo "    Noch kein Zugriff – Repo-Name richtig? Schlüssel ohne Zeilenumbruch eingefügt?"
  done
fi
echo "GitHub-Zugriff auf $REPO klappt."

# ---------------------------------------------------------------------
step "4/9 Projekt holen und einrichten"
# ---------------------------------------------------------------------
if [ -d "$APPDIR/.git" ] && [ ! -f "$APPDIR/composer.json" ]; then
  sudo -u vibhub -H git -C "$APPDIR" pull --ff-only || true
fi
if [ ! -d "$APPDIR/.git" ]; then
  rm -rf "$APPDIR"
  sudo -u vibhub -H git clone "git@github.com:$REPO.git" "$APPDIR"
fi
cd "$APPDIR"
[ -f composer.json ] || { echo "FEHLER: Im Repo $REPO liegt noch kein Laravel-Projekt (composer.json fehlt). Erst lokal anlegen und pushen."; exit 1; }
[ -f "$ENVFILE" ] || sudo -u vibhub cp .env.example .env

setenv(){
  KEY="$1" VAL="$2" python3 - "$ENVFILE" <<'PY'
import os, re, sys
pfad = sys.argv[1]; key = os.environ['KEY']; val = os.environ['VAL']
if val and not (val[:1] in "'\"" and val[-1:] == val[:1]) and re.search(r'[\s#"\'$\\]', val):
    val = "'" + val + "'" if "'" not in val else '"' + val.replace('\\', '\\\\').replace('"', '\\"') + '"'
text = open(pfad, encoding='utf-8').read()
muster = re.compile(r'^#?\s?' + re.escape(key) + r'=.*$', re.M)
zeile = f'{key}={val}'
text = muster.sub(lambda m: zeile, text, count=1) if muster.search(text) else text.rstrip('\n') + '\n' + zeile + '\n'
open(pfad, 'w', encoding='utf-8').write(text)
PY
}
setenv APP_NAME vib-hub
setenv APP_ENV production
setenv APP_DEBUG false
setenv APP_URL "https://$DOMAIN"
setenv APP_LOCALE de
setenv APP_FALLBACK_LOCALE en
setenv LOG_LEVEL warning
setenv SESSION_SECURE_COOKIE true
setenv DB_CONNECTION mysql
setenv DB_HOST 127.0.0.1
setenv DB_PORT 3306
setenv DB_DATABASE vibhub
setenv DB_USERNAME vibhub
setenv DB_PASSWORD "$DBPASS"
# Mail-Versand: eigene Zugangsdaten später in $ENVFILE eintragen (MAIL_HOST, MAIL_USERNAME …)
setenv MAIL_FROM_NAME vib-hub
chown vibhub:vibhub "$ENVFILE" && chmod 640 "$ENVFILE"

# ---------------------------------------------------------------------
step "5/9 Deploy-Befehl: deploy-vibhub"
# ---------------------------------------------------------------------
cat > /usr/local/bin/deploy-vibhub <<EOF
#!/bin/bash
set -e
cd $APPDIR
echo "== Code holen ==";      sudo -u vibhub -H git pull --ff-only
echo "== PHP-Pakete ==";      sudo -u vibhub -H composer install --no-dev --optimize-autoloader --no-interaction
echo "== Frontend bauen ==";  if [ -f package-lock.json ]; then sudo -u vibhub -H npm ci; else sudo -u vibhub -H npm install; fi
                              sudo -u vibhub -H npm run build
echo "== Datenbank ==";       sudo -u vibhub php artisan migrate --force
echo "== Speicher-Link ==";  sudo -u vibhub php artisan storage:link >/dev/null 2>&1 || true
echo "== Cache ==";           sudo -u vibhub php artisan optimize
systemctl reload php$PHPV-fpm
echo "== Fertig: https://$DOMAIN =="
EOF
chmod 755 /usr/local/bin/deploy-vibhub

grep -q '^APP_KEY=base64' "$ENVFILE" || { sudo -u vibhub -H composer install --no-dev --no-interaction; sudo -u vibhub php artisan key:generate --force; }
/usr/local/bin/deploy-vibhub
echo "* * * * * vibhub cd $APPDIR && php artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/vibhub-scheduler

# ---------------------------------------------------------------------
step "6/9 Zertifikat für $DOMAIN und www.$DOMAIN"
# ---------------------------------------------------------------------
LE=/etc/letsencrypt/live/$DOMAIN
if [ ! -s "$LE/fullchain.pem" ]; then
  certbot certonly --nginx --non-interactive --agree-tos -m "$EMAIL" -d "$DOMAIN" -d "www.$DOMAIN"
fi
[ -s "$LE/fullchain.pem" ] || { echo "FEHLER: Kein Zertifikat für $DOMAIN. Zeigen vib-hub.com und www.vib-hub.com per DNS auf $SERVER_IP?"; exit 1; }
SSL_EXTRA=""
[ -f /etc/letsencrypt/options-ssl-nginx.conf ] && SSL_EXTRA="include /etc/letsencrypt/options-ssl-nginx.conf;"
[ -f /etc/letsencrypt/ssl-dhparams.pem ] && SSL_EXTRA="$SSL_EXTRA ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;"

# ---------------------------------------------------------------------
step "7/9 Nginx: vib-hub.com auf das neue Projekt umstellen"
# ---------------------------------------------------------------------
# Nur die Datei von vib-hub.com wird ersetzt – andere Seiten auf dem Server bleiben unberührt.
cat > "/etc/nginx/sites-available/$DOMAIN" <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name $DOMAIN www.$DOMAIN;
    # Für die automatische Verlängerung des Zertifikats
    location ^~ /.well-known/acme-challenge/ { root $BASE/acme; default_type text/plain; }
    location / { return 301 https://$DOMAIN\$request_uri; }
}

server {
    listen 443 ssl;
    listen [::]:443 ssl;
    server_name www.$DOMAIN;
    ssl_certificate     $LE/fullchain.pem;
    ssl_certificate_key $LE/privkey.pem;
    $SSL_EXTRA
    return 301 https://$DOMAIN\$request_uri;
}

server {
    listen 443 ssl;
    listen [::]:443 ssl;
    server_name $DOMAIN;

    ssl_certificate     $LE/fullchain.pem;
    ssl_certificate_key $LE/privkey.pem;
    $SSL_EXTRA

    add_header Strict-Transport-Security "max-age=31536000" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    root $APPDIR/public;
    index index.php;
    charset utf-8;
    client_max_body_size 20M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    error_page 404 /index.php;

    location ~ ^/index\.php(/|\$) {
        fastcgi_pass unix:$SOCK;
        fastcgi_split_path_info ^(.+\.php)(/.*)\$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT \$realpath_root;
        fastcgi_hide_header X-Powered-By;
    }
    location ~ /\.(?!well-known).* { deny all; }
}
EOF
ln -sf "/etc/nginx/sites-available/$DOMAIN" "/etc/nginx/sites-enabled/$DOMAIN"
if nginx -t; then
  systemctl reload nginx
else
  echo "FEHLER in der Nginx-Konfiguration – stelle die Sicherung wieder her."
  rm -rf /etc/nginx && cp -a "$SICHERUNG" /etc/nginx && systemctl reload nginx
  exit 1
fi
# Verlängerung künftig über den Ordner $BASE/acme (unabhängig vom Aufbau der Nginx-Datei)
if certbot reconfigure --cert-name "$DOMAIN" --webroot -w "$BASE/acme" --non-interactive; then
  echo "Automatische Verlängerung getestet – klappt."
else
  echo "WARNUNG: Test der Zertifikats-Verlängerung fehlgeschlagen. Bitte später prüfen: certbot renew --dry-run"
fi
mkdir -p /etc/letsencrypt/renewal-hooks/deploy
printf '#!/bin/sh\nsystemctl reload nginx\n' > /etc/letsencrypt/renewal-hooks/deploy/nginx-reload.sh
chmod 755 /etc/letsencrypt/renewal-hooks/deploy/nginx-reload.sh

# ---------------------------------------------------------------------
step "8/9 Admin-Zugang (falls Filament im Projekt ist)"
# ---------------------------------------------------------------------
cd "$APPDIR"
if sudo -u vibhub php artisan list --raw 2>/dev/null | grep -q '^make:filament-user'; then
  if [ "$(mysql -N -e "SELECT COUNT(*) FROM vibhub.users WHERE email='$EMAIL'" 2>/dev/null || echo 0)" = "0" ]; then
    frage "Admin-Zugang für https://$DOMAIN/admin (Login: $EMAIL)"
    read -rp "    Dein Name: " ADMIN_NAME
    while true; do
      read -rsp "    Passwort (mind. 8 Zeichen, wird nicht angezeigt): " ADMIN_PASS; echo
      read -rsp "    Passwort wiederholen: " ADMIN_PASS2; echo
      if [ "${#ADMIN_PASS}" -ge 8 ] && [ "$ADMIN_PASS" = "$ADMIN_PASS2" ]; then break; fi
      echo "    Zu kurz oder nicht gleich – bitte nochmal."
    done
    sudo -u vibhub php artisan make:filament-user --name="$ADMIN_NAME" --email="$EMAIL" --password="$ADMIN_PASS"
  else
    echo "Admin $EMAIL gibt es schon."
  fi
else
  echo "Kein Filament im Projekt – übersprungen."
fi
cd /root

# ---------------------------------------------------------------------
step "9/9 Prüfung"
# ---------------------------------------------------------------------
echo
echo "=================== FERTIG ==================="
for d in "$DOMAIN" "www.$DOMAIN" "$DOMAIN/admin/login"; do
  host=${d%%/*}
  printf "%-38s %s\n" "$d:" "$(curl -s -o /dev/null -w '%{http_code}' --max-time 10 --resolve "$host:443:$SERVER_IP" "https://$d" || true)"
done
echo
echo "Erwartet: 200 / 301 / 200"
echo "Neuen Stand von vib-hub ausspielen:  deploy-vibhub"
