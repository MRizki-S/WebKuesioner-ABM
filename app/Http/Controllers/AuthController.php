<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function ActionLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        // dd($request->all());

        if (Auth::attempt($credentials)) {
            // dd('masukk bos');
            Session::flash('success', 'Login berhasil! Selamat datang, ' . Auth::user()->username);
            // Redirect to the intended page or dashboard
            return redirect('/');
        } else {
            Session::flash('error', 'Username atau password salah. Silakan coba lagi.');
            // dd('gagal bos');
            return redirect('/login');
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flash('success', 'Anda telah berhasil logout.');
        return redirect('/login');
    }
}
