<?php

namespace App\Support;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/** Leitet eine Anfrage (nur mit Zustimmung des Kunden) per E-Mail an den Anlagenpartner weiter. */
class PartnerForwarder
{
    public static function send(Inquiry $inquiry, Site $site, ?string $to = null): bool
    {
        $to = $to ?: $site->get('partner_email');
        if (! $inquiry->forward_consent || blank($to)) {
            return false;
        }

        $text = implode("\n", array_filter([
            'Anfrage über vib-hub.com – weitergeleitet mit Zustimmung des Kunden',
            '',
            $inquiry->reference ? 'Bezug:   '.$inquiry->reference : null,
            'Name:    '.$inquiry->name,
            $inquiry->company ? 'Firma:   '.$inquiry->company : null,
            'E-Mail:  '.$inquiry->email,
            $inquiry->phone ? 'Telefon: '.$inquiry->phone : null,
            '',
            $inquiry->message,
            '',
            'Ansprechpartner bei vib-hub: '.$site->get('email'),
        ], fn ($line) => $line !== null));

        try {
            Mail::raw($text, function ($message) use ($to, $inquiry): void {
                $message->to($to)
                    ->replyTo($inquiry->email, $inquiry->name)
                    ->subject('Anfrage Schwingprüfanlage über vib-hub: '.($inquiry->company ?: $inquiry->name));
            });
        } catch (Throwable $e) {
            Log::warning('Weiterleitung an Partner fehlgeschlagen: '.$e->getMessage());

            return false;
        }

        $inquiry->forceFill(['forwarded_to' => $to, 'forwarded_at' => now()])->save();

        return true;
    }
}
