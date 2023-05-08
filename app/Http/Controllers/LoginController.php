<?php

namespace App\Http\Controllers;

use App\Models\Index;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $data['alert'] = $request->alert;
        return view('login',$data);
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
            ])->withInput();
        }

        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $data = User::whereRaw('BINARY `username` = ?',$request->username)->first();
            if ($data){
                $cek = Index::where('tahun', Carbon::parse(now())->year)->first();
                $data_session = [
                    'id' => $data->id,
                    'username' => $data->username,
                    'role' => $data->role,
                    'tahun' => Carbon::parse(now())->year,

                ];
                if ($cek) {
                    $data_session['approved'] = $cek->approved;
                } else {
                    // for($x = 2014; $x<2023;$x++){
                    //     Index::create([
                    //         'tahun' => $x
                    //     ]);
                    // }

                    Index::create([
                        'tahun' => Carbon::parse(now())->year
                    ]);
                    $data_session['approved'] = null;
                }
                $request->session()->put($data_session);
                
                return redirect()->intended('dashboard');
            }
        }

        return Redirect::back()->withErrors([
            'message' => 'Username atau password salah.'
        ])->withInput();
    }
    public function register(){
        return view('register');
    }
    public function register_aksi(Request $request){
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'same' => ':attribute harus sama dengan password.',
            'unique' => ':attribute telah dipakai.'
        ];
        $attribute = [
            'username' => 'Username',
            'role' => 'Role',
            'password' => 'Password',
            'confirm_password' => 'Konfirmasi Password',
        ];
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'role' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors([
                'message' => 'You are not allowed.'
            ]);
        }

        $username = $request->input('username');
        $password = $request->input('password');
        $role = $request->input('role');

        if ($role == "owner"){   
            User::create([
                'username' => $username,
                'password' => Hash::make($password),
                'role' => $role,
            ]);

            return redirect('/');
        } else {
            return Redirect::back()->withErrors([
                'message' => 'You are not allowed'
            ]);
        }
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->flush();
        return redirect('/');
    }
}
