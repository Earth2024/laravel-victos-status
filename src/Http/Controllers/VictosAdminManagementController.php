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

class VictosAdminManagementController extends Controller
{
    public function index(): View
    {
        $userModel = config('victos-status.user_model');
        $userTable = (new $userModel())->getTable();
        $admins = $userModel::query()
            ->where('role', 'admin')
            ->addSelect(['status_records_count' => \Illuminate\Support\Facades\DB::table('status_records')
                ->selectRaw('count(*)')
                ->whereColumn('status_records.user_id', $userTable . '.id')])
            ->orderBy('name')
            ->paginate(25);
        $admins->getCollection()->transform(function ($admin) {
            $admin->allowed_domains_for_display = str_replace(
                PHP_EOL,
                ', ',
                VictosDomains::toText($admin->allowed_domains)
            );

            return $admin;
        });

        return view('victos-status::victos.admins.index', compact('admins'));
    }

    public function create(): View
    {
        return view('victos-status::victos.admins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $userModel = config('victos-status.user_model');
        $user = new $userModel();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique($user->getTable(), 'email')],
            'password' => ['required', 'confirmed', Password::min(12)],
            'allowed_domains_text' => ['nullable', 'string', 'max:5000'],
        ]);

        VictosTenantUser::fill($user, [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
            'is_active' => true,
            'allowed_domains' => VictosDomains::fromText($data['allowed_domains_text'] ?? ''),
        ]);
        $user->save();

        return redirect()->route('victos.admins.index')->with('success', 'Admin account created.');
    }
}
