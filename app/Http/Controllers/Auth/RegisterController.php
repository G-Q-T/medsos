<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[\pL\s\.\'\,\\-]+$/u',
            ],
            'email' => ['required', 'string', 'email:rfc', 'max:150', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.regex' => 'Nama tidak valid. Nama hanya boleh berupa huruf, spasi, titik, koma, tanda petik, atau tanda hubung (tidak boleh mengandung script, simbol, atau kode khusus).',
            'name.min' => 'Nama harus memiliki minimal 2 karakter.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $cleanName = strip_tags(trim($validated['name']));

        $user = User::create([
            'name' => $cleanName,
            'email' => strtolower(trim($validated['email'])),
            'password' => $validated['password'],
        ]);

        Auth::login($user);

        return redirect()->route('feed')->with('success', 'Registrasi berhasil! Selamat datang di ConnectSpace.');
    }
}
