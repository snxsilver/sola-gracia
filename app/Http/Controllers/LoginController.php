<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function username()
    {
        return 'username';
    }

    public function login()
    {
        return view('login');
    }

    public function login_aksi(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'username' => 'Username',
            'password' => 'Password',
        ];
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors([
                'message' => 'Username atau password salah.'
            ]);
        }

        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return Redirect::back()->withErrors([
            'message' => 'Username atau password salah.'
        ]);
    }
}
