<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Support\Catalog;
use App\Support\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class ContactController extends Controller
{
    public function show(Request $request, Site $site): View
    {
        $topic = $request->query('thema');

        return view('pages.contact', [
            'customer' => Auth::guard('customer')->user(),
            'requiresAccount' => $site->contactRequiresAccount(),
            'topics' => Catalog::INQUIRY_TOPICS,
            'preset' => [
                'topic' => array_key_exists((string) $topic, Catalog::INQUIRY_TOPICS) ? $topic : null,
                'reference' => mb_substr((string) $request->query('bezug', ''), 0, 200),
            ],
        ]);
    }

    public function store(Request $request, Site $site): RedirectResponse
    {
        // Spam-Falle: echte Besucher sehen dieses Feld nicht
        if (filled($request->input('website'))) {
            return redirect()->to(lroute('contact'))->with('sent', true);
        }

        $customer = Auth::guard('customer')->user();

        // Optional (Admin → Website & Logo → Kundenkonto): nur angemeldete Kunden dürfen anfragen
        if (! $customer && $site->contactRequiresAccount()) {
            return redirect()->to(lroute('account.login'));
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:60'],
            'topic' => ['required', Rule::in(array_keys(Catalog::INQUIRY_TOPICS))],
            'reference' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'privacy' => ['accepted'],
        ], [
            'privacy.accepted' => __('Bitte stimmen Sie der Verarbeitung Ihrer Angaben zu.'),
            'message.min' => __('Bitte beschreiben Sie Ihr Anliegen in ein paar Worten.'),
        ], [
            'name' => __('Name'),
            'email' => __('E-Mail'),
            'topic' => __('Thema'),
            'message' => __('Nachricht'),
        ]);

        unset($data['privacy']);

        if (app()->getLocale() === 'en') {
            $data['reference'] = trim('[EN] '.($data['reference'] ?? ''));
        }

        $inquiry = Inquiry::query()->create($data + [
            'status' => 'neu',
            'ip' => $request->ip(),
            'customer_id' => $customer?->getKey(),
        ]);

        $to = $site->get('notify_email');
        if (filled($to)) {
            try {
                Mail::raw($this->mailText($inquiry), function ($message) use ($to, $inquiry): void {
                    $message->to($to)
                        ->replyTo($inquiry->email, $inquiry->name)
                        ->subject('Neue Anfrage: '.$inquiry->topicLabel().' – '.$inquiry->name);
                });
            } catch (Throwable $e) {
                Log::warning('Anfrage-Benachrichtigung konnte nicht gesendet werden: '.$e->getMessage());
            }
        }

        if ($customer) {
            return redirect()->to(lroute('account.inquiry', $inquiry))->with('status', __('Vielen Dank – Ihre Anfrage ist angekommen. Den Stand sehen Sie hier in Ihrem Konto.'));
        }

        return redirect()->to(lroute('contact'))->with('sent', true);
    }

    protected function mailText(Inquiry $inquiry): string
    {
        return implode("\n", array_filter([
            'Neue Anfrage über vib-hub.com',
            '',
            'Thema:   '.(Catalog::INQUIRY_TOPICS[$inquiry->topic] ?? $inquiry->topic),
            $inquiry->reference ? 'Bezug:   '.$inquiry->reference : null,
            'Name:    '.$inquiry->name,
            $inquiry->company ? 'Firma:   '.$inquiry->company : null,
            'E-Mail:  '.$inquiry->email,
            $inquiry->phone ? 'Telefon: '.$inquiry->phone : null,
            $inquiry->customer_id ? 'Kunde:   registriertes Kundenkonto (#'.$inquiry->customer_id.')' : null,
            '',
            $inquiry->message,
            '',
            'Im Admin öffnen: '.url('/admin/inquiries/'.$inquiry->id.'/edit'),
        ], fn ($line) => $line !== null));
    }
}
