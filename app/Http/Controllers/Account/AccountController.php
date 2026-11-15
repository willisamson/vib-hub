<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

/** Bereich „Mein Konto“ für angemeldete Kunden. */
class AccountController extends Controller
{
    protected function customer(): Customer
    {
        /** @var Customer */
        return Auth::guard('customer')->user();
    }

    public function dashboard(): View
    {
        $customer = $this->customer();

        return view('account.dashboard', [
            'customer' => $customer,
            'inquiries' => $customer->inquiries()->latest()->get(),
        ]);
    }

    public function inquiry(Inquiry $inquiry): View
    {
        abort_unless($inquiry->customer_id === $this->customer()->getKey(), 404);

        return view('account.inquiry', ['customer' => $this->customer(), 'inquiry' => $inquiry]);
    }

    public function profile(): View
    {
        return view('account.profile', ['customer' => $this->customer()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $customer = $this->customer();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:190', Rule::unique('customers', 'email')->ignore($customer->getKey())],
            'phone' => ['nullable', 'string', 'max:60'],
        ], [], ['name' => __('Name'), 'email' => __('E-Mail')]);

        $data['email'] = Str::lower($data['email']);
        $customer->update($data + ['locale' => app()->getLocale()]);

        return redirect()->to(lroute('account.profile'))->with('status', __('Ihre Angaben wurden gespeichert.'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password:customer'],
            'password' => ['required', 'confirmed', PasswordRule::min(10)],
        ], [
            'current_password.current_password' => __('Das aktuelle Passwort stimmt nicht.'),
        ], ['current_password' => __('Aktuelles Passwort'), 'password' => __('Neues Passwort')]);

        $this->customer()->update(['password' => $request->input('password')]);
        $request->session()->regenerate();

        return redirect()->to(lroute('account.profile'))->with('status', __('Ihr Passwort wurde geändert.'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('delete', [
            'current_password' => ['required', 'current_password:customer'],
        ], [
            'current_password.current_password' => __('Das Passwort stimmt nicht.'),
        ], ['current_password' => __('Passwort')]);

        $customer = $this->customer();
        Auth::guard('customer')->logout();
        $customer->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to(lroute('home'))->with('status', __('Ihr Kundenkonto wurde gelöscht.'));
    }
}
