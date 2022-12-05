<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function dashboard(){
        return view('dashboard.dashboard');
    }

    public function user(){
        $data['user'] = User::get();
        return view('dashboard.user',$data);
    }

    public function user_tambah(){
        return view('dashboard.user_tambah');
    }

    public function user_aksi(Request $request){
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'same' => ':attribute harus sama dengan password.',
            'unique' => ':attribute telah dipakai.'
        ];
        $attribute = [
            'username' => 'Username',
            'password' => 'Password',
            'confirm_password' => 'Konfirmasi Password',
        ];
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'role' => 'required',
        ],$message, $attribute);

        if ($validator->fails()){
            return Redirect::back()->withErrors($validator);
        }

        $username = $request->input('username');
        $password = $request->input('password');
        $role = $request->input('role');

        User::create([
            'username' => $username,
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        return redirect('/dashboard/user');
    }

    public function user_edit($id){
        $data['user'] = User::where('id', $id)->first();
        return view('dashboard.user_edit',$data);
    }

    public function user_update(Request $request){
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'same' => ':attribute harus sama dengan password.',
            'unique' => ':attribute telah dipakai.'
        ];
        $attribute = [
            'username' => 'Username',
            'password' => 'Password',
            'confirm_password' => 'Konfirmasi Password',
        ];
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'role' => 'required',
        ],$message, $attribute);

        if ($validator->fails()){
            return Redirect::back()->withErrors($validator);
        }

        $id = $request->input('id');
        $username = $request->input('username');
        $password = $request->input('password');
        $role = $request->input('role');

        User::where('id',$id)->update([
            'username' => $username,
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        return redirect('/dashboard/user');
    }

    public function user_hapus($id){
        User::where('id',$id)->delete();

        return redirect('/dashboard/user');
    }
}
