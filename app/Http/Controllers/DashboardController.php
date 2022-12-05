<?php

namespace App\Http\Controllers;

use App\Models\Bukukas;
use App\Models\Kategori;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.dashboard');
    }

    public function user()
    {
        $data['user'] = User::get();
        return view('dashboard.user', $data);
    }

    public function user_tambah()
    {
        return view('dashboard.user_tambah');
    }

    public function user_aksi(Request $request)
    {
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
        ], $message, $attribute);

        if ($validator->fails()) {
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

    public function user_edit($id)
    {
        $data['user'] = User::where('id', $id)->first();
        return view('dashboard.user_edit', $data);
    }

    public function user_update(Request $request)
    {
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
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $id = $request->input('id');
        $username = $request->input('username');
        $password = $request->input('password');
        $role = $request->input('role');

        User::where('id', $id)->update([
            'username' => $username,
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        return redirect('/dashboard/user');
    }

    public function user_hapus($id)
    {
        User::where('id', $id)->delete();

        return redirect('/dashboard/user');
    }

    public function kategori()
    {
        $data['kategori'] = Kategori::get();
        return view('dashboard.kategori', $data);
    }

    public function kategori_tambah()
    {
        return view('dashboard.kategori_tambah');
    }

    public function kategori_aksi(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute telah dipakai.'
        ];
        $attribute = [
            'kategori' => 'Kategori',
        ];
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|unique:kategori,nama',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $kategori = $request->input('kategori');

        Kategori::create([
            'nama' => $kategori,
            'kreator' => Session::get('id'),
        ]);

        return redirect('/dashboard/kategori');
    }

    public function kategori_edit($id)
    {
        $data['kategori'] = Kategori::where('id', $id)->first();
        return view('dashboard.kategori_edit', $data);
    }

    public function kategori_update(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'unique' => ':attribute telah dipakai.'
        ];
        $attribute = [
            'kategori' => 'Kategori',
        ];
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|unique:kategori,nama',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $id = $request->input('id');
        $kategori = $request->input('kategori');

        Kategori::where('id', $id)->update([
            'nama' => $kategori,
            'kreator' => Session::get('id')
        ]);

        return redirect('/dashboard/kategori');
    }

    public function kategori_hapus($id)
    {
        Kategori::where('id', $id)->delete();

        return redirect('/dashboard/kategori');
    }

    public function proyek()
    {
        $data['proyek'] = Proyek::get();
        return view('dashboard.proyek', $data);
    }

    public function proyek_tambah()
    {
        return view('dashboard.proyek_tambah');
    }

    public function proyek_aksi(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'kode' => 'Kode Proyek',
            'nama' => 'Nama Proyek',
            'nilai' => 'Nilai Proyek',
        ];
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
            'nilai' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $nama = $request->input('nama');
        $kode = $request->input('kode');
        $nilai = $request->input('nilai');

        Proyek::create([
            'nama' => $nama,
            'kode' => $kode,
            'nilai' => $nilai,
            'kreator' => Session::get('id'),
        ]);

        return redirect('/dashboard/proyek');
    }

    public function proyek_edit($id)
    {
        $data['proyek'] = Proyek::where('id', $id)->first();
        return view('dashboard.proyek_edit', $data);
    }

    public function proyek_update(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'kode' => 'Kode Proyek',
            'nama' => 'Nama Proyek',
            'nilai' => 'Nilai Proyek',
        ];
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
            'nilai' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $id = $request->input('id');
        $nama = $request->input('nama');
        $kode = $request->input('kode');
        $nilai = $request->input('nilai');

        Proyek::where('id', $id)->update([
            'nama' => $nama,
            'kode' => $kode,
            'nilai' => $nilai,
            'kreator' => Session::get('id'),
        ]);

        return redirect('/dashboard/proyek');
    }

    public function proyek_hapus($id)
    {
        Proyek::where('id', $id)->delete();

        return redirect('/dashboard/proyek');
    }

    public function bukukas()
    {
        $data['bukukas'] = Bukukas::
            join('kategori', 'bukukas.kategori', '=', 'kategori.id')
            ->join('proyek', 'bukukas.proyek', '=', 'proyek.id')
            ->select('bukukas.*','proyek.nama as namaproyek','kategori.nama as namakategori')
            ->get();
        return view('dashboard.bukukas', $data);
    }

    public function bukukas_tambah()
    {
        $data['proyek'] = Proyek::get();
        $data['kategori'] = Kategori::get();
        return view('dashboard.bukukas_tambah',$data);
    }

    public function bukukas_aksi(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'proyek' => 'Proyek',
            'tanggal' => 'Tanggal',
            'keterangan' => 'Keterangan',
            'kategori' => 'Kategori',
            'bukti' => 'No Bukti',
            'masuk' => 'Uang Masuk',
            'keluar' => 'Uang Keluar',
        ];
        $validator = Validator::make($request->all(), [
            'proyek' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'required',
            'kategori' => 'required',
            // 'bukti' => 'required',
            // 'masuk' => 'required',
            // 'keluar' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $proyek = $request->input('proyek');
        $tanggal = $request->input('tanggal');
        $keterangan = $request->input('keterangan');
        $kategori = $request->input('kategori');
        $bukti = $request->input('bukti');
        $masuk = $request->input('masuk');
        $keluar = $request->input('keluar');

        Bukukas::create([
            'proyek' => $proyek,
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'kategori' => $kategori,
            'no_bukti' => $bukti,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'kreator' => Session::get('id'),
        ]);

        return redirect('/dashboard/bukukas');
    }

    public function bukukas_edit($id)
    {
        $data['proyek'] = Proyek::get();
        $data['kategori'] = Kategori::get();
        $data['bukukas'] = bukukas::where('id', $id)->first();
        return view('dashboard.bukukas_edit', $data);
    }

    public function bukukas_update(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'proyek' => 'Proyek',
            'tanggal' => 'Tanggal',
            'keterangan' => 'Keterangan',
            'kategori' => 'Kategori',
            'bukti' => 'No Bukti',
            'masuk' => 'Uang Masuk',
            'keluar' => 'Uang Keluar',
        ];
        $validator = Validator::make($request->all(), [
            'proyek' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'required',
            'kategori' => 'required',
            // 'bukti' => 'required',
            // 'masuk' => 'required',
            // 'keluar' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $id = $request->input('id');
        $proyek = $request->input('proyek');
        $tanggal = $request->input('tanggal');
        $keterangan = $request->input('keterangan');
        $kategori = $request->input('kategori');
        $bukti = $request->input('bukti');
        $masuk = $request->input('masuk');
        $keluar = $request->input('keluar');

        Bukukas::where('id',$id)->update([
            'proyek' => $proyek,
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'kategori' => $kategori,
            'no_bukti' => $bukti,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'kreator' => Session::get('id'),
        ]);

        return redirect('/dashboard/bukukas');
    }

    public function bukukas_hapus($id)
    {
        bukukas::where('id', $id)->delete();

        return redirect('/dashboard/bukukas');
    }
}
