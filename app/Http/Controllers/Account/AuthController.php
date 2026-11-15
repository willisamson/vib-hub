<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

/** Registrierung, Anmeldung und Passwort für Kundenkonten. */
class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('account.register');
    }

    public function register(Request $request): RedirectResponse
    {
        if (filled($request->input('website'))) {
            return redirect()->to(lroute('account.login'));
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:190', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:60'],
            'password' => ['required', 'confirmed', PasswordRule::min(10)],
            'privacy' => ['accepted'],
        ], [
            'privacy.accepted' => __('Bitte stimmen Sie der Verarbeitung Ihrer Angaben zu.'),
            'email.unique' => __('Zu dieser E-Mail-Adresse gibt es bereits ein Konto. Melden Sie sich an oder setzen Sie Ihr Passwort zurück.'),
        ], [
            'name' => __('Name'),
            'email' => __('E-Mail'),
            'password' => __('Passwort'),
        ]);

        $customer = Customer::query()->create([
            'name' => $data['name'],
            'company' => $data['company'] ?? null,
            'email' => Str::lower($data['email']),
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'locale' => app()->getLocale(),
            'privacy_accepted_at' => now(),
            'last_login_at' => now(),
        ]);

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()->to(lroute('account.dashboard'))->with('status', __('Willkommen! Ihr Kundenkonto ist eingerichtet.'));
    }

    public function showLogin(): View
    {
        return view('account.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [], ['email' => __('E-Mail'), 'password' => __('Passwort')]);

        $credentials['email'] = Str::lower($credentials['email']);
        $credentials['is_active'] = true;

        if (! Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('E-Mail-Adresse oder Passwort stimmen nicht.'),
            ]);
        }

        $request->session()->regenerate();
        Auth::guard('customer')->user()->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(lroute('account.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->regenerateToken();

        return redirect()->to(lroute('home'));
    }

    public function showForgot(): View
    {
        return view('account.forgot');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']], [], ['email' => __('E-Mail')]);

        try {
            Password::broker('customers')->sendResetLink(['email' => Str::lower($request->input('email'))]);
        } catch (Throwable $e) {
            Log::warning('Passwort-Link für Kundenkonto konnte nicht gesendet werden: '.$e->getMessage());
        }

        // Immer dieselbe Antwort – verrät nicht, ob ein Konto existiert
        return back()->with('status', __('Wenn ein Konto zu dieser Adresse besteht, haben wir Ihnen einen Link zum Zurücksetzen geschickt.'));
    }

    public function showReset(Request $request, string $token): View
    {
        return view('account.reset', ['token' => $token, 'email' => (string) $request->query('email', '')]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(10)],
        ], [], ['email' => __('E-Mail'), 'password' => __('Passwort')]);

        $status = Password::broker('customers')->reset(
            ['email' => Str::lower($data['email'])] + $data,
            function (Customer $customer, string $password): void {
                $customer->forceFill(['password' => $password, 'remember_token' => Str::random(60)])->save();
                event(new PasswordReset($customer));
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => __('Der Link ist ungültig oder abgelaufen. Bitte fordern Sie einen neuen an.'),
            ]);
        }

        return redirect()->to(lroute('account.login'))->with('status', __('Ihr Passwort wurde geändert. Sie können sich jetzt anmelden.'));
    }
}
