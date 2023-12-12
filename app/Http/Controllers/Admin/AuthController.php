<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function postlogin(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
    
            // Periksa peran user
            if ($user->role === 'admin' || $user->role === 'pelatih') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'murid') {
                return redirect()->route('murid.dashboard');
            }
        }
    
        // Gagal login, redirect ke halaman login
        return redirect('/login')->with('error', 'Login failed. Please check your credentials.');
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
