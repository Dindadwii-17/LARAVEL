<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        // Cek apakah username atau email yang digunakan
        $user = User::where('username', $validated['username'])
                    ->orWhere('email', $validated['username'])
                    ->first();

        if ($user && Hash::check($validated['password'], $user->password)) {
            // Cek apakah user sudah disetujui
            if (!$user->is_approved) {
                return back()->withErrors(['username' => 'Akun Anda belum disetujui oleh Admin. Mohon tunggu.'])->withInput();
            }

            Auth::login($user);
            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang Admin!');
            }
            
            return redirect('/dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors(['username' => 'Username atau password salah'])->withInput();
    }

    // Tampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:users,nim',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'address' => 'nullable|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'nim.required' => 'NIM harus diisi',
            'nim.unique' => 'NIM sudah terdaftar',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Password tidak cocok',
        ]);

        // Buat user baru (is_approved default false)
        $user = User::create([
            'name' => $validated['name'],
            'nim' => $validated['nim'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/login')->with('success', 'Pendaftaran berhasil! Mohon tunggu persetujuan Admin sebelum login.');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Anda telah logout');
    }
}
