<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $users = User::orderBy('name')->get();
        $currentUser = Auth::user();

        return view('admin.settings.index', compact('settings', 'users', 'currentUser'));
    }

    public function updateInstitution(Request $request)
    {
        $validated = $request->validate([
            'institution_name' => 'required|string|max:200',
            'institution_code' => 'nullable|string|max:50',
            'ministry_name' => 'required|string|max:200',
            'address' => 'required|string|max:300',
            'phone' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'head_of_institution' => 'nullable|string|max:150',
            'nip_head' => 'nullable|string|max:50',
            'running_text' => 'nullable|string|max:500',
            'whatsapp_service' => 'nullable|string|max:20',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan profil instansi BGTK berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password akun Anda berhasil diubah.');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,petugas',
            'phone' => 'nullable|string|max:25',
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', 'Pengguna petugas baru berhasil ditambahkan.');
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$statusText}.");
    }
}
