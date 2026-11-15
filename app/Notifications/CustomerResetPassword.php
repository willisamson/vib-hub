<?php

namespace App\Notifications;

use App\Support\Site;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/** E-Mail mit Link zum Zurücksetzen des Passworts für Kundenkonten. */
class CustomerResetPassword extends Notification
{
    public function __construct(public string $token, public string $lang = 'de') {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $previous = app()->getLocale();
        app()->setLocale($this->lang);

        $url = lroute('account.password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()]);
        $name = app(Site::class)->name();

        $mail = (new MailMessage)
            ->subject(__('Passwort zurücksetzen – :name', ['name' => $name]))
            ->greeting(__('Hallo :name,', ['name' => $notifiable->name]))
            ->line(__('Sie erhalten diese E-Mail, weil für Ihr Kundenkonto ein neues Passwort angefordert wurde.'))
            ->action(__('Neues Passwort festlegen'), $url)
            ->line(__('Der Link ist :count Minuten gültig. Wenn Sie kein neues Passwort angefordert haben, können Sie diese E-Mail ignorieren.', ['count' => config('auth.passwords.customers.expire', 60)]))
            ->salutation(__('Viele Grüße').', '.$name);

        app()->setLocale($previous);

        return $mail;
    }
}
