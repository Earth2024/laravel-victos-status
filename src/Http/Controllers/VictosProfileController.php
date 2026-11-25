<?php

namespace Earth2024\VictosStatus\Http\Controllers;

use Earth2024\VictosStatus\Support\VictosDomains;
use Earth2024\VictosStatus\Support\VictosTenantUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class VictosProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('victos-status::victos.profile', [
            'user' => $request->user(),
            'allowedDomainsText' => VictosDomains::toText($request->user()->allowed_domains),
            'endpoint' => route('status.check'),
            'assetUrl' => asset('vendor/victos-status/victos-check-status.js'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique($request->user()->getTable(), 'email')->ignore($request->user()->getKey())],
            'allowed_domains_text' => ['nullable', 'string', 'max:5000'],
        ]);

        VictosTenantUser::fill($request->user(), [
            'name' => $data['name'],
            'email' => $data['email'],
            'allowed_domains' => VictosDomains::fromText($data['allowed_domains_text'] ?? ''),
        ]);
        $request->user()->save();
        $request->session()->regenerate();

        return back()->with('success', 'Profile and allowed domains updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->forceFill(['password' => Hash::make($data['password'])])->save();
        $request->session()->regenerate();

        return back()->with('success', 'Password changed successfully.');
    }
}
