<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            $data = User::where('username',$request->username)->firstOrFail();
            $data_session = [
                'id' => $data->id,
                'username' => $data->username,
                'role' => $data->role
            ];
            $request->session()->put($data_session);

            return redirect()->intended('dashboard');
        }

        return Redirect::back()->withErrors([
            'message' => 'Username atau password salah.'
        ]);
    }
    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
