<?php

namespace App\Http\Controllers;

use App\Models\Ambil;
use App\Models\Bukukas;
use App\Models\Invoice;
use App\Models\Kategori;
use App\Models\Proyek;
use App\Models\Stok;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Str;
use Image;

use App\Exports\UsersExport;
use App\Helpers\Helper;
use App\Models\GajiMandorTukang;
use App\Models\HarianBayar;
use App\Models\InvoiceDetail;
use App\Models\Pajak;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.dashboard');
    }

    public function user()
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }

        $data['user'] = User::get();
        return view('dashboard.user', $data);
    }

    public function user_tambah()
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        return view('dashboard.user_tambah');
    }

    public function user_aksi(Request $request)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
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
            notify()->error('Gagal menambahkan user.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $username = $request->input('username');
        $password = $request->input('password');
        $role = $request->input('role');

        User::create([
            'username' => $username,
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        notify()->success('User berhasil ditambahkan.');
        return redirect('/dashboard/user');
    }

    public function user_edit($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['user'] = User::where('id', $id)->first();
        return view('dashboard.user_edit', $data);
    }

    public function user_update(Request $request)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $id = $request->input('id');
        $olddata = User::where('id', $id)->first();
        User::where('id', $id)->update([
            'username' => 'xFaP12',
        ]);

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
            'confirm_password' => 'same:password',
            'role' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            User::where('id', $id)->update([
                'username' => $olddata->username,
            ]);
            notify()->error('Gagal menambahkan user.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $username = $request->input('username');
        $password = $request->input('password');
        $role = $request->input('role');

        if ($password != '') {
            User::where('id', $id)->update([
                'username' => $username,
                'password' => Hash::make($password),
                'role' => $role,
            ]);
        } else {
            User::where('id', $id)->update([
                'username' => $username,
                'role' => $role,
            ]);
        }
        notify()->success('User berhasil diupdate.');
        return redirect('/dashboard/user');
    }

    public function user_hapus($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('id' === $id)) {
            notify()->error('Akses dilarang.');
            return back();
        }
        User::where('id', $id)->delete();
        notify()->success('User berhasil dihapus.');
        return redirect('/dashboard/user');
    }

    public function kategori()
    {
        if (Session::get('role') === 'supervisor' || Session::get('role') === 'manager') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['kategori'] = Kategori::where(function ($query) {
            if (Session::get('role') === 'admin'){
                $query->where('id', '!=', 2);
            }
        })->get();
        return view('dashboard.kategori', $data);
    }

    public function kategori_view(Request $request, $id)
    {
        if (Session::get('role') === 'supervisor' || Session::get('role') === 'manager') {
            notify()->error('Akses dilarang.');
            return back();
        }

        if (Session::get('role') === 'admin' && $id == 2) {
            notify()->error('Akses dilarang.');
            return back();
        }

        $request->session()->put([
            'kategori' => $id,
        ]);

        return redirect('/dashboard/bukukas');
    }

    public function kategori_tambah()
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        return view('dashboard.kategori_tambah');
    }

    public function kategori_aksi(Request $request)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
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
            notify()->error('Gagal menambahkan kategori.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $kategori = $request->input('kategori');

        Kategori::create([
            'nama' => $kategori,
            'kreator' => Session::get('id'),
        ]);

        notify()->success('Kategori berhasil ditambahkan.');
        return redirect('/dashboard/kategori');
    }

    public function kategori_edit($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['kategori'] = Kategori::where('id', $id)->first();
        return view('dashboard.kategori_edit', $data);
    }

    public function kategori_update(Request $request)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
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
            notify()->error('Gagal mengupdate kategori.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $id = $request->input('id');
        $kategori = $request->input('kategori');

        Kategori::where('id', $id)->update([
            'nama' => $kategori,
            'kreator' => Session::get('id')
        ]);

        notify()->success('Kategori berhasil diupdate.');
        return redirect('/dashboard/kategori');
    }

    public function kategori_hapus($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $protected = [1, 2, 3];
        if (in_array($id, $protected)){
            notify()->error('Kategori tidak dapat dihapus.');
            return back();
        }

        $bukukas = Bukukas::where('kategori',$id)->first();
        if ($bukukas){
            notify()->error('Terdapat data bukukas yang menggunakan kategori ini.');
            return back();
        }

        Kategori::where('id', $id)->delete();

        notify()->success('Kategori berhasil dihapus.');
        return redirect('/dashboard/kategori');
    }

    public function proyek()
    {
        if (Session::get('role') === 'supervisor' || Session::get('role') === 'manager') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data_query = Proyek::where(function ($query) {
            if (Session::get('role') === 'operator') :
                $query->where('pajak', 1);
            endif;
        })->where('tahun', Session::get('tahun'));

        if (Session::get('sort_kategori')) {
            if (Session::get('sort_kategori') === 'asc') {
                $data_query2 = $data_query->orderBy('kode', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('kode', 'desc');
            }
        }

        if (Session::get('sort_keluar')) {
            if (Session::get('sort_keluar') === 'asc') {
                $data_query2 = $data_query->orderBy('nilai', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('nilai', 'desc');
            }
        }

        if (Session::get('sort_proyek')) {
            if (Session::get('sort_proyek') === 'asc') {
                $data_query2 = $data_query->orderBy('nama', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('nama', 'desc');
            }
        }

        if (Session::get('sort_tanggal')) {
            if (Session::get('sort_tanggal') === 'asc') {
                $data_query2 = $data_query->orderBy('id', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('id', 'desc');
            }
        } else {
            $data_query2 = $data_query->orderBy('id', 'desc');
        }

        $data['proyek'] = $data_query2->paginate(50);

        return view('dashboard.proyek', $data);
    }

    public function proyek_tambah()
    {
        if (Session::get('role') !== 'owner' && Session::get('role') !== 'admin') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        return view('dashboard.proyek_tambah');
    }

    public function proyek_aksi(Request $request)
    {
        if (Session::get('role') !== 'owner' && Session::get('role') !== 'admin') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
            'unique' => ':attribute telah dipakai.'
        ];
        $attribute = [
            'kode' => 'Kode Proyek',
            'nama' => 'Nama Proyek',
            // 'nilai' => 'Nilai Proyek',
        ];
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
            // 'nilai' => 'nullable|numeric|gte:0',
        ], $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal menambahkan proyek.');
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $kode = $request->input('kode');

        $cek = Proyek::where('tahun', Session::get('tahun'))->where('kode', $kode)->first();
        if ($cek){
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors([
                'kode' => 'Kode Proyek telah dipakai.'
            ])->withInput();
        }

        $nama = $request->input('nama');
        $nilai = $request->input('nilai') ?? 0;
        $pajak = $request->input('pajak');

        Proyek::create([
            'tahun' => Carbon::parse(now())->year,
            'nama' => $nama,
            'kode' => $kode,
            'nilai' => $nilai,
            'pajak' => $pajak,
            'kreator' => Session::get('id'),
        ]);

        notify()->success('Proyek berhasil ditambahkan.');
        return redirect('/dashboard/proyek');
    }

    public function proyek_edit($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->where('id', $id)->first();
        return view('dashboard.proyek_edit', $data);
    }

    public function proyek_update(Request $request)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $id = $request->input('id');
        $cek = Proyek::where('id',$id)->first();
        if (Session::get('tahun') != Carbon::parse(now())->year || $cek->tahun !== Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $olddata = Proyek::where('id', $id)->first();
        Proyek::where('id', $id)->update([
            'kode' => 'xFaP12'
        ]);
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
            'unique' => ':attribute telah dipakai.'
        ];
        $attribute = [
            'kode' => 'Kode Proyek',
            'nama' => 'Nama Proyek',
            // 'nilai' => 'Nilai Proyek',
        ];
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
            // 'nilai' => 'nullable|numeric|gte:0',
        ], $message, $attribute);

        if ($validator->fails()) {
            Proyek::where('id', $id)->update([
                'kode' => $olddata->kode
            ]);
            notify()->error('Gagal mengupdate proyek.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $kode = $request->input('kode');
        
        $cek = Proyek::where('tahun', Session::get('tahun'))->where('kode', $kode)->first();
        if ($cek){
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors([
                'kode' => 'Kode Proyek telah dipakai.'
            ])->withInput();
        }

        $nama = $request->input('nama');
        $nilai = $request->input('nilai') ?? 0;
        $pajak = $request->input('pajak');

        Proyek::where('id', $id)->update([
            'nama' => $nama,
            'kode' => $kode,
            'nilai' => $nilai,
            'pajak' => $pajak,
            'kreator' => Session::get('id'),
        ]);

        notify()->success('Proyek berhasil diupdate.');
        return redirect('/dashboard/proyek');
    }

    public function proyek_hapus($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $cek = Proyek::where('id',$id)->first();
        if (Session::get('tahun') != Carbon::parse(now())->year || $cek->tahun !== Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $invoice = Invoice::where('proyek',$id)->first();
        if ($invoice){
            notify()->error('Terdapat data invoice yang menggunakan proyek ini.');
            return back();
        }
        $borongan = Borongan::where('proyek',$id)->first();
        if ($borongan){
            notify()->error('Terdapat data gaji tukang borongan yang menggunakan proyek ini.');
            return back();
        }
        $harian = HarianBayar::where('proyek',$id)->first();
        if ($harian){
            notify()->error('Terdapat data gaji tukang harian yang menggunakan proyek ini.');
            return back();
        }
        $mandor = GajiMandorTukang::where('proyek',$id)->first();
        if ($mandor){
            notify()->error('Terdapat data gaji tukang mandor yang menggunakan proyek ini.');
            return back();
        }
        $bukukas = Bukukas::where('proyek',$id)->first();
        if ($bukukas){
            notify()->error('Terdapat data bukukas yang menggunakan proyek ini.');
            return back();
        }
        
        Proyek::where('id', $id)->delete();

        notify()->success('Proyek berhasil dihapus.');
        return redirect('/dashboard/proyek');
    }

    public function filter(Request $request)
    {
        $submit = $request->input('submit');
        if ($submit == "Filter" || $submit == "Go") {
            $kategori = $request->input('kategori');
            $proyek = $request->input('proyek');
            $mulai = $request->input('mulai') != '-' ? $request->input('mulai') : '';
            $selesai = $request->input('selesai') != '-' ? $request->input('selesai') : '';
            $tahun = $request->input('tahun') ?? Carbon::parse(now())->year;
            if ($mulai && $selesai) {
                if (Carbon::parse($selesai)->year !== Carbon::parse($mulai)->year) {
                    return Redirect::back()->withErrors([
                        'selesai' => 'Tanggal selesai dan tanggal mulai harus di tahun yang sama.'
                    ])->withInput();
                }
                if (Carbon::parse($selesai) < Carbon::parse($mulai)) {
                    return Redirect::back()->withErrors([
                        'selesai' => 'Tanggal selesai harus lebih besar dari tanggal mulai.'
                    ])->withInput();
                }
                $tahun = Carbon::parse($selesai)->year;
            }
            $bulan = $request->input('bulan') != '-' ? $request->input('bulan') : '';
            if ($bulan) {
                $tahun = Carbon::parse($bulan)->year;
            }

            $request->session()->put([
                'kategori' => $kategori,
                'proyek' => $proyek,
                'mulai' => $mulai,
                'selesai' => $selesai,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);

            return back();
        } else {
            $request->session()->forget(['proyek', 'kategori', 'bulan', 'mulai', 'selesai']);

            return back();
        }
    }

    public function export()
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $data_query = Bukukas::where(function ($query) {
            if (Session::get('kategori')) :
                $query->where('kategori', Session::get('kategori'));
            endif;
        })
            ->where(function ($query) {
                if (Session::get('proyek')) :
                    $query->where('proyek', Session::get('proyek'));
                endif;
            })
            ->where(function ($query) {
                if (Session::get('bulan')) :
                    $sesi = Session::get('bulan');
                    $start = Carbon::parse($sesi)->startOfMonth();
                    $end = Carbon::parse($sesi)->endOfMonth();
                    $query->where('tanggal', '>=', $start)
                        ->where('tanggal', '<=', $end);
                elseif (Session::get('mulai') || Session::get('selesai')) :
                    $mulai = Session::get('mulai');
                    $selesai = Session::get('selesai');
                    if ($mulai && $selesai) :
                        $start = Carbon::parse($mulai)->startOfDay();
                        $end = Carbon::parse($selesai)->endOfDay();
                        $query->where('tanggal', '>=', $start)
                            ->where('tanggal', '<=', $end);
                    elseif ($mulai) :
                        $start = Carbon::parse($mulai)->startOfDay();
                        $query->where('tanggal', '>=', $start);
                    elseif ($selesai) :
                        $end = Carbon::parse($selesai)->endOfDay();
                        $query->where('tanggal', '<=', $end);
                    endif;
                endif;
            })
            ->join('kategori', 'bukukas.kategori', '=', 'kategori.id')
            ->join('proyek', 'bukukas.proyek', '=', 'proyek.id')
            ->select('bukukas.*', 'proyek.nama as namaproyek', 'kategori.nama as namakategori')
            ->orderBy('bukukas.tanggal', 'asc');

        $bukukas = $data_query->get();
        $keluar = $data_query->sum('bukukas.keluar');
        $masuk = $data_query->sum('bukukas.masuk');

        $centerBold = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => 'center',
            ]
        ];

        $rightBold = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => 'right',
            ]
        ];

        $right = [
            'alignment' => [
                'horizontal' => 'right',
            ]
        ];

        $left = [
            'alignment' => [
                'horizontal' => 'left',
            ]
        ];

        $bold = [
            'font' => [
                'bold' => true,
            ],
        ];

        // return Excel::download(new UsersExport, 'users.xlsx');
        $sheet->setCellValue('A1', 'Laporan Keuangan Buku Kas CV. Sola Gracia');
        $x = 2;
        if (Session::get('proyek') || Session::get('kategori')) {
            if (Session::get('proyek')) {
                $proyek = Proyek::where('id', Session::get('proyek'))->first();
                $state = 'Proyek ' . $proyek->nama;

                $sheet->setCellValue('A' . $x, $state);

                $sheet->mergeCells('A' . $x . ':H' . $x);
                $sheet->getStyle('A' . $x)->applyFromArray($centerBold);

                $x++;
            }
            if (Session::get('kategori')) {
                $kategori = Kategori::where('id', Session::get('kategori'))->first();

                $state = 'Kategori ' . $kategori->nama;

                $sheet->setCellValue('A' . $x, $state);

                $sheet->mergeCells('A' . $x . ':H' . $x);
                $sheet->getStyle('A' . $x)->applyFromArray($centerBold);

                $x++;
            }
        } else {
            $state = 'Semua Data';

            $sheet->setCellValue('A' . $x, $state);

            $sheet->mergeCells('A' . $x . ':H' . $x);
            $sheet->getStyle('A' . $x)->applyFromArray($centerBold);

            $x++;
        }


        if (Session::get('mulai') || Session::get('selesai') || Session::get('bulan')) {
            if (Session::get('mulai') && Session::get('selesai')) {
                $mulai = Carbon::parse(Session::get('mulai'))->locale('id');
                $mulai->settings(['formatFunction' => 'translatedFormat']);
                $get_mulai = $mulai->format('j F Y');
                $selesai = Carbon::parse(Session::get('selesai'))->locale('id');
                $selesai->settings(['formatFunction' => 'translatedFormat']);
                $get_selesai = $selesai->format('j F Y');
                $state = 'Dari ' . $get_mulai . ' Sampai ' . $get_selesai;
            } else if (Session::get('mulai')) {
                $mulai = Carbon::parse(Session::get('mulai'))->locale('id');
                $mulai->settings(['formatFunction' => 'translatedFormat']);
                $get_mulai = $mulai->format('j F Y');
                $selesai = Carbon::parse(now())->locale('id');
                $selesai->settings(['formatFunction' => 'translatedFormat']);
                $get_selesai = $selesai->format('j F Y');
                $state = 'Dari ' . $get_mulai . ' Sampai ' . $get_selesai;
            } else if (Session::get('selesai')) {
                $selesai = Carbon::parse(Session::get('selesai'))->locale('id');
                $selesai->settings(['formatFunction' => 'translatedFormat']);
                $get_selesai = $selesai->format('j F Y');
                $state = 'Per ' . $get_selesai;
            } else if (Session::get('bulan')) {
                $selesai = Carbon::parse(Session::get('bulan'))->locale('id');
                $selesai->settings(['formatFunction' => 'translatedFormat']);
                $get_selesai = $selesai->format('F Y');
                $state = 'Bulan ' . $get_selesai;
            }
        } else {
            $selesai = Carbon::parse(now())->locale('id');
            $selesai->settings(['formatFunction' => 'translatedFormat']);
            $get_selesai = $selesai->format('j F Y');
            $state = 'Per ' . $get_selesai;
        }
        $sheet->setCellValue('A' . $x, $state);

        $sheet->mergeCells('A' . $x . ':H' . $x);
        $sheet->getStyle('A' . $x)->applyFromArray($centerBold);
        $x++;

        $x++;

        $sheet->setCellValue('A' . $x, 'No');
        $sheet->setCellValue('B' . $x, 'Proyek');
        $sheet->setCellValue('C' . $x, 'Tanggal');
        $sheet->setCellValue('D' . $x, 'Keterangan');
        $sheet->setCellValue('E' . $x, 'Kategori');
        $sheet->setCellValue('F' . $x, 'No Bukti');
        $sheet->setCellValue('G' . $x, 'Masuk');
        $sheet->setCellValue('H' . $x, 'Keluar');

        $y = $x;
        $x++;
        $no = 1;

        foreach ($bukukas as $b) {
            $sheet->setCellValue('A' . $x, $no);
            $sheet->setCellValue('B' . $x, $b->namaproyek);
            $sheet->setCellValue('C' . $x, $b->tanggal);
            $sheet->setCellValue('D' . $x, $b->keterangan);
            $sheet->setCellValue('E' . $x, $b->namakategori);
            $sheet->setCellValue('F' . $x, $b->no_bukti ?? '-');
            $sheet->setCellValue('G' . $x, $b->masuk ?? '-');
            $sheet->setCellValue('H' . $x, $b->keluar ?? '-');
            $no++;
            $x++;
        }

        $sheet->setCellValue('A' . $x, 'Jumlah');
        $sheet->setCellValue('G' . $x, $masuk);
        $sheet->setCellValue('H' . $x, $keluar);

        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A' . $x . ':F' . $x);

        $sheet->getStyle('G4:H' . $x)->getNumberFormat()->setFormatCode('"Rp "#,##0');

        $sheet->getColumnDimension('A')->setWidth(35, 'px');
        $sheet->getColumnDimension('B')->setWidth(100, 'px');
        $sheet->getColumnDimension('C')->setWidth(100, 'px');
        $sheet->getColumnDimension('D')->setWidth(180, 'px');
        $sheet->getColumnDimension('E')->setWidth(90, 'px');
        $sheet->getColumnDimension('F')->setWidth(80, 'px');
        $sheet->getColumnDimension('G')->setWidth(120, 'px');
        $sheet->getColumnDimension('H')->setWidth(120, 'px');

        $sheet->getStyle('A1')->applyFromArray($centerBold);
        $sheet->getStyle('A' . $y . ':F' . $y)->applyFromArray($bold);
        $sheet->getStyle('G' . $y . ':H' . $y)->applyFromArray($rightBold);
        $sheet->getStyle('F' . $y + 1 . ':F' . $x - 1)->applyFromArray($left);
        $sheet->getStyle('G' . $y + 1 . ':H' . $x - 1)->applyFromArray($right);
        $sheet->getStyle('A' . $x . ':F' . $x)->applyFromArray($rightBold);
        $sheet->getStyle('G' . $x . ':H' . $x)->applyFromArray($rightBold);

        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $color = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'ffddd9c4',
                ],
            ],
        ];

        $sheet->getStyle('A' . $y . ':H' . $x)->applyFromArray($border);
        $sheet->getStyle('A' . $y . ':H' . $y)->applyFromArray($color);
        $sheet->getStyle('A' . $x . ':H' . $x)->applyFromArray($color);

        $sheet->getStyle('A' . $y . ':H' . $y)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A' . $x . ':H' . $x)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan Keuangan.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function bukukas_sort(Request $request, $sort)
    {
        switch ($sort):
            case "tanggal":
                $request->session()->forget(['sort_proyek', 'sort_kategori', 'sort_bukti', 'sort_masuk', 'sort_keluar']);
                if (Session::get('sort_tanggal') === "asc") {
                    $request->session()->put([
                        'sort_tanggal' => 'desc'
                    ]);
                } else {
                    $request->session()->put([
                        'sort_tanggal' => 'asc'
                    ]);
                }
                break;
            case "proyek":
                $request->session()->forget(['sort_tanggal', 'sort_kategori', 'sort_bukti', 'sort_masuk', 'sort_keluar']);
                if (Session::get('sort_proyek') === "asc") {
                    $request->session()->put([
                        'sort_proyek' => 'desc'
                    ]);
                } else {
                    $request->session()->put([
                        'sort_proyek' => 'asc'
                    ]);
                }
                break;
            case "kategori":
                $request->session()->forget(['sort_tanggal', 'sort_proyek', 'sort_bukti', 'sort_masuk', 'sort_keluar']);
                if (Session::get('sort_kategori') === "asc") {
                    $request->session()->put([
                        'sort_kategori' => 'desc'
                    ]);
                } else {
                    $request->session()->put([
                        'sort_kategori' => 'asc'
                    ]);
                }
                break;
            case "bukti":
                $request->session()->forget(['sort_tanggal', 'sort_proyek', 'sort_kategori', 'sort_masuk', 'sort_keluar']);
                if (Session::get('sort_bukti') === "desc") {
                    $request->session()->put([
                        'sort_bukti' => 'asc'
                    ]);
                } else {
                    $request->session()->put([
                        'sort_bukti' => 'desc'
                    ]);
                }
                break;
            case "masuk":
                $request->session()->forget(['sort_tanggal', 'sort_proyek', 'sort_kategori', 'sort_bukti', 'sort_keluar']);
                if (Session::get('sort_masuk') === "asc") {
                    $request->session()->put([
                        'sort_masuk' => 'desc'
                    ]);
                } else {
                    $request->session()->put([
                        'sort_masuk' => 'asc'
                    ]);
                }
                break;
            case "keluar":
                $request->session()->forget(['sort_tanggal', 'sort_proyek', 'sort_kategori', 'sort_bukti', 'sort_masuk']);
                if (Session::get('sort_keluar') === "asc") {
                    $request->session()->put([
                        'sort_keluar' => 'desc'
                    ]);
                } else {
                    $request->session()->put([
                        'sort_keluar' => 'asc'
                    ]);
                }
                break;
            case "clear":
                $request->session()->forget(['sort_tanggal', 'sort_proyek', 'sort_kategori', 'sort_bukti', 'sort_masuk', 'sort_keluar']);
                break;
        endswitch;

        return back();
    }

    public function bukukas()
    {
        if (Session::get('role') === 'supervisor' || Session::get('role') === 'manager') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['proyek'] = Proyek::where(function ($query) {
            if (Session::get('role') === 'operator') {
                $query->where('proyek.pajak', 1);
            }
        })->where('tahun', Session::get('tahun'))
            ->orderBy('nama', 'asc')->get();
        $data['kategori'] = Kategori::where(function ($query) {
            $query->where('id', '!=', 2);
        })->orderBy('nama', 'asc')->get();
        $data_query = Bukukas::where(function ($query) {
            if (Session::get('kategori')) :
                $query->where('kategori', Session::get('kategori'));
            endif;
        })
            ->where(function ($query) {
                if (Session::get('proyek')) :
                    $query->where('proyek', Session::get('proyek'));
                endif;
            })
            ->where(function ($query) {
                if (Session::get('bulan')) :
                    $sesi = Session::get('bulan');
                    $start = Carbon::parse($sesi)->startOfMonth();
                    $end = Carbon::parse($sesi)->endOfMonth();
                    $query->where('tanggal', '>=', $start)
                        ->where('tanggal', '<=', $end);
                elseif (Session::get('mulai') || Session::get('selesai')) :
                    $mulai = Session::get('mulai');
                    $selesai = Session::get('selesai');
                    if ($mulai && $selesai) :
                        $start = Carbon::parse($mulai)->startOfDay();
                        $end = Carbon::parse($selesai)->endOfDay();
                        $query->where('tanggal', '>=', $start)
                            ->where('tanggal', '<=', $end);
                    elseif ($mulai) :
                        $start = Carbon::parse($mulai)->startOfDay();
                        $query->where('tanggal', '>=', $start);
                    elseif ($selesai) :
                        $end = Carbon::parse($selesai)->endOfDay();
                        $query->where('tanggal', '<=', $end);
                    endif;
                elseif (Session::get('tahun')) :
                    $sesi = Carbon::create(Session::get('tahun'), 1, 31, 12, 0, 0);
                    $start = Carbon::parse($sesi)->startOfYear();
                    $end = Carbon::parse($sesi)->endOfYear();
                    $query->where('tanggal', '>=', $start)
                        ->where('tanggal', '<=', $end);
                endif;
            })
            ->join('kategori', 'bukukas.kategori', '=', 'kategori.id')
            ->join('proyek', 'bukukas.proyek', '=', 'proyek.id')
            ->where(function ($query) {
                if (Session::get('role') === 'operator') {
                    $query->where('proyek.pajak', 1);
                } else if (Session::get('role') === 'admin'){
                    $query->where('kategori.id', '!=', 2);
                }
            })
            ->select('bukukas.*', 'proyek.nama as namaproyek', 'kategori.nama as namakategori');

        if (Session::get('sort_bukti')) {
            if (Session::get('sort_bukti') === 'asc') {
                $data_query2 = $data_query->orderBy('bukukas.no_bukti', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('bukukas.no_bukti', 'desc');
            }
        }

        if (Session::get('sort_kategori')) {
            if (Session::get('sort_kategori') === 'asc') {
                $data_query2 = $data_query->orderBy('kategori.nama', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('kategori.nama', 'desc');
            }
        }

        if (Session::get('sort_masuk')) {
            if (Session::get('sort_masuk') === 'asc') {
                $data_query2 = $data_query->orderBy('bukukas.masuk', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('bukukas.masuk', 'desc');
            }
        }

        if (Session::get('sort_keluar')) {
            if (Session::get('sort_keluar') === 'asc') {
                $data_query2 = $data_query->orderBy('bukukas.keluar', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('bukukas.keluar', 'desc');
            }
        }

        if (Session::get('sort_proyek')) {
            if (Session::get('sort_proyek') === 'asc') {
                $data_query2 = $data_query->orderBy('proyek.nama', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('proyek.nama', 'desc');
            }
        }

        if (Session::get('sort_tanggal')) {
            if (Session::get('sort_tanggal') === 'asc') {
                $data_query2 = $data_query->orderBy('bukukas.tanggal', 'asc')->orderBy('id', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('bukukas.tanggal', 'desc')->orderBy('id', 'desc');
            }
        } else {
            $data_query2 = $data_query->orderBy('bukukas.tanggal', 'desc')->orderBy('id', 'desc');
        }

        $data_query3 = Bukukas::where(function ($query) {
            if (Session::get('kategori')) :
                $query->where('kategori', Session::get('kategori'));
            endif;
        })
            ->where(function ($query) {
                if (Session::get('proyek')) :
                    $query->where('proyek', Session::get('proyek'));
                endif;
            })
            ->where(function ($query) {
                if (Session::get('bulan')) :
                    $sesi = Session::get('bulan');
                    $start = Carbon::parse($sesi)->startOfMonth();
                    $end = Carbon::parse($sesi)->endOfMonth();
                    $query->where('tanggal', '>=', $start)
                        ->where('tanggal', '<=', $end);
                elseif (Session::get('mulai') || Session::get('selesai')) :
                    $mulai = Session::get('mulai');
                    $selesai = Session::get('selesai');
                    if ($mulai && $selesai) :
                        $start = Carbon::parse($mulai)->startOfDay();
                        $end = Carbon::parse($selesai)->endOfDay();
                        $query->where('tanggal', '>=', $start)
                            ->where('tanggal', '<=', $end);
                    elseif ($mulai) :
                        $start = Carbon::parse($mulai)->startOfDay();
                        $query->where('tanggal', '>=', $start);
                    elseif ($selesai) :
                        $end = Carbon::parse($selesai)->endOfDay();
                        $query->where('tanggal', '<=', $end);
                    endif;
                elseif (Session::get('tahun')) :
                    $sesi = Carbon::create(Session::get('tahun'), 1, 31, 12, 0, 0);
                    $start = Carbon::parse($sesi)->startOfYear();
                    $end = Carbon::parse($sesi)->endOfYear();
                    $query->where('tanggal', '>=', $start)
                        ->where('tanggal', '<=', $end);
                endif;
            })
            ->join('kategori', 'bukukas.kategori', '=', 'kategori.id')
            ->join('proyek', 'bukukas.proyek', '=', 'proyek.id')
            ->where(function ($query) {
                if (Session::get('role') === 'operator') {
                    $query->where('proyek.pajak', 1);
                }
            })
            ->select('bukukas.*', 'proyek.nama as namaproyek', 'kategori.nama as namakategori');

        $data['bukukas'] = $data_query2->paginate(100);
        $data['keluar'] = $data_query3->sum('bukukas.keluar');
        $data['masuk'] = $data_query3->sum('bukukas.masuk');
        return view('dashboard.bukukas', $data);
    }

    public function bukukas_tambah()
    {
        if (Session::get('role') !== 'owner' && Session::get('role') !== 'admin') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->orderBy('nama', 'asc')->get();
        $data['kategori'] = Kategori::where(function ($query) {
            $query->where('id', '!=', 2);
        })->orderBy('nama', 'asc')->get();
        return view('dashboard.bukukas_tambah', $data);
    }

    public function bukukas_aksi(Request $request)
    {
        if (Session::get('role') !== 'owner' && Session::get('role') !== 'admin') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'mimes' => ':attribute harus jpg, jpeg atau png.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
            'max' => ':attribute melebihi :max kB.',
        ];
        $attribute = [
            'proyek' => 'Proyek',
            'tanggal' => 'Tanggal',
            'uraian' => 'Uraian',
            'keterangan' => 'Keterangan',
            'kategori' => 'Kategori',
            'no_nota' => 'Nomor Nota',
            'masuk' => 'Uang Masuk',
            'keluar' => 'Uang Keluar',
            'nota' => 'Nota',
        ];

        $rules = [
            'proyek' => 'required',
            'tanggal' => 'required',
            'uraian' => 'required',
            'kategori' => 'required',
            'no_nota' => 'required',
            'nota' => 'mimes:jpg,jpeg,png|max:10240',
            'masuk' => 'nullable|numeric|gte:0',
            'keluar' => 'nullable|numeric|gte:0'
        ];

        $validator = Validator::make($request->all(), $rules, $message, $attribute);


        if ($validator->fails()) {
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $proyek = $request->input('proyek');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $uraian = $request->input('uraian');
        $keterangan = $request->input('keterangan');
        $kategori = $request->input('kategori');
        $bukti = $request->input('no_nota') ?? null;
        $masuk = $request->input('masuk');
        $keluar = $request->input('keluar');

        $bukukas = Bukukas::create([
            'tahun' => Carbon::parse(now())->year,
            'proyek' => $proyek,
            'tanggal' => $tanggal,
            'uraian' => $uraian,
            'keterangan' => $keterangan,
            'kategori' => $kategori,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'kreator' => Session::get('id'),
            'no_nota' => $bukti,
        ]);

        $idbukukas = $bukukas->id;

        if ($request->hasFile('nota')) {
            $gbr = $request->file('nota');
            $ext = $request->nota->extension();
            $slug = Str::slug($bukti, '-');
            $gbrnama = $slug ? $slug . '-' . rand(99, 999) . '.' . $ext : rand(99, 999) . '-' . rand(99, 999) . '.' . $ext;
            $path = public_path('/images/nota/' . $gbrnama);
            $gbresize = Image::make($gbr->path());
            $width = $gbresize->width();
            $height = $gbresize->height();
            if ($width > $height) {
                $gbresize->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path);
            } else {
                $gbresize->resize(null, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path);
            }

            Bukukas::where('id', $idbukukas)->update([
                'nota' => $gbrnama,
            ]);
        }

        notify()->success('Transaksi berhasil ditambahkan.');
        return redirect('/dashboard/bukukas');
    }

    public function bukukas_edit($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->orderBy('nama', 'asc')->get();
        $data['kategori'] = Kategori::where(function ($query) {
            $query->where('id', '!=', 2);
        })->orderBy('nama', 'asc')->get();
        $data['bukukas'] = bukukas::where('tahun', Session::get('tahun'))->where('id', $id)->first();
        return view('dashboard.bukukas_edit', $data);
    }

    public function bukukas_update(Request $request)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $id = $request->input('id');
        $cek = Bukukas::where('id',$id)->first();
        if (Session::get('tahun') != Carbon::parse(now())->year || $cek->tahun !== Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'mimes' => ':attribute harus jpg, jpeg atau png.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
            'max' => ':attribute melebihi :max kB.',
        ];
        $attribute = [
            'proyek' => 'Proyek',
            'tanggal' => 'Tanggal',
            'uraian' => 'Uraian',
            'keterangan' => 'Keterangan',
            'kategori' => 'Kategori',
            'no_nota' => 'Nomor Nota',
            'masuk' => 'Uang Masuk',
            'nota' => 'Nota',
            'keluar' => 'Uang Keluar',
        ];

        $rules = [
            'proyek' => 'required',
            'tanggal' => 'required',
            'uraian' => 'required',
            'kategori' => 'required',
            'no_nota' => 'required',
            'nota' => 'mimes:jpg,jpeg,png|max:10240',
            'masuk' => 'nullable|numeric|gte:0',
            'keluar' => 'nullable|numeric|gte:0',
        ];

        $validator = Validator::make($request->all(), $rules, $message, $attribute);


        if ($validator->fails()) {
            notify()->error('Gagal mengupdate transaksi.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $id = $request->input('id');
        $proyek = $request->input('proyek');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $uraian = $request->input('uraian');
        $keterangan = $request->input('keterangan');
        $kategori = $request->input('kategori');
        $bukti = $request->input('no_nota');
        $masuk = $request->input('masuk');
        $keluar = $request->input('keluar');

        Bukukas::where('id', $id)->update([
            'proyek' => $proyek,
            'tanggal' => $tanggal,
            'uraian' => $uraian,
            'keterangan' => $keterangan,
            'kategori' => $kategori,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'kreator' => Session::get('id'),
            'no_nota' => $bukti,
        ]);

        $nota = Bukukas::where('id', $id)->first();

        if ($request->input('d_nota') === 'hapus') {
            unlink(public_path('/images/nota/' . $nota->nota));
            Bukukas::where('id', $id)->update([
                'nota' => '',
                'no_nota' => ''
            ]);
        }

        if ($request->hasFile('nota')) {
            if ($nota->nota) {
                unlink(public_path('/images/nota/' . $nota->nota));
            }

            $gbr = $request->file('nota');
            $ext = $request->nota->extension();
            $slug = Str::slug($bukti, '-');
            $gbrnama = $slug . '-' . rand(99, 999) . '.' . $ext;
            $path = public_path('/images/nota/' . $gbrnama);
            $gbresize = Image::make($gbr->path());
            $width = $gbresize->width();
            $height = $gbresize->height();
            if ($width > $height) {
                $gbresize->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path);
            } else {
                $gbresize->resize(null, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path);
            }

            Bukukas::where('id', $id)->update([
                'nota' => $gbrnama,
            ]);
        }

        notify()->success('Transaksi berhasil diupdate.');
        return redirect('/dashboard/bukukas');
    }

    public function bukukas_hapus($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $cek = Bukukas::where('id',$id)->first();
        if (Session::get('tahun') != Carbon::parse(now())->year || $cek->tahun !== Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $nota = Bukukas::where('id', $id)->first();
        if ($nota->nota) {
            if (file_exists(public_path('/images/nota/' . $nota->nota))) {
                unlink(public_path('/images/nota/' . $nota->nota));
            }
        }

        bukukas::where('id', $id)->delete();

        notify()->success('Transaksi berhasil dihapus.');
        return redirect('/dashboard/bukukas');
    }

    public function ambil_stok()
    {
        if (Session::get('role') !== 'owner' && Session::get('role') !== 'admin') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->orderBy('nama', 'asc')->get();
        $data['kategori'] = Kategori::where(function ($query) {
            $query->where('id', '!=', 2);
        })->orderBy('nama', 'asc')->get();
        $data['stok'] = Stok::where('kuantitas', '>', 0)->get();
        return view('dashboard.ambil_stok', $data);
    }

    public function ambil_stok_aksi(Request $request)
    {
        if (Session::get('role') !== 'owner' && Session::get('role') !== 'admin') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gt' => ':attribute harus lebih dari 0.',
        ];
        $attribute = [
            'proyek' => 'Proyek',
            'tanggal' => 'Tanggal',
            'kategori' => 'Kategori',
            'stok' => 'Stok',
            'kuantitas' => 'Jumlah',
        ];
        $validator = Validator::make($request->all(), [
            'proyek' => 'required',
            'tanggal' => 'required',
            'kategori' => 'required',
            'stok' => 'required',
            'kuantitas' => 'required|numeric|gt:0',
        ], $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $proyek = $request->input('proyek');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $kategori = $request->input('kategori');
        $idstok = $request->input('stok');
        $kuantitas = $request->input('kuantitas');

        $stok = Stok::where('id', $idstok)->first();

        if ($kuantitas > $stok->kuantitas) {
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors([
                'kuantitas' => 'Jumlah ambil stok melebihi stok yang tersedia.'
            ])->withInput();
        }

        $keterangan = $stok->barang . ' ' . $kuantitas . ' ' . $stok->satuan;
        $keluar = $kuantitas / $stok->kuantitas * $stok->harga;

        $bukukas = Bukukas::create([
            'tahun' => Carbon::parse(now())->year,
            'proyek' => $proyek,
            'tanggal' => $tanggal,
            'uraian' => $keterangan,
            'kategori' => $kategori,
            'keluar' => $keluar,
            'kreator' => Session::get('id'),
            'ambil_stok' => 1,
        ]);

        $idbukukas = $bukukas->id;

        Stok::where('id', $idstok)->update([
            'kuantitas' => $stok->kuantitas - $kuantitas,
            'harga' => $stok->harga - $keluar,
        ]);

        Ambil::create([
            'tanggal' => $tanggal,
            'stok' => $idstok,
            'kuantitas' => $kuantitas,
            'harga' => $keluar,
            'bukukas' => $idbukukas,
            'kreator' => Session::get('id'),
        ]);
        notify()->success('Transaksi berhasil ditambahkan.');
        return redirect('/dashboard/bukukas');
    }

    public function ambil_stok_update(Request $request)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $id = $request->input('id');
        $ambil = Ambil::where('bukukas', $id)->first();
        $cek = Bukukas::where('id',$ambil->bukukas)->first();
        if (Session::get('tahun') != Carbon::parse(now())->year || $cek->tahun !== Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka.',
            'gt' => ':attribute harus lebih dari 0.',
        ];
        $attribute = [
            'proyek' => 'Proyek',
            'tanggal' => 'Tanggal',
            'kategori' => 'Kategori',
            'stok' => 'Stok',
            'kuantitas' => 'Jumlah',
        ];
        $validator = Validator::make($request->all(), [
            'proyek' => 'required',
            'tanggal' => 'required',
            'kategori' => 'required',
            'stok' => 'required',
            'kuantitas' => 'required|numeric|gt:0',
        ], $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal mengupdate transaksi.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $proyek = $request->input('proyek');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $kategori = $request->input('kategori');
        $idstok = $request->input('stok');
        $kuantitas = $request->input('kuantitas');

        $stok = Stok::where('id', $idstok)->first();

        if ($kuantitas > $stok->kuantitas + $ambil->kuantitas) {
            notify()->error('Gagal mengupdate transaksi.');
            return Redirect::back()->withErrors([
                'kuantitas' => 'Jumlah ambil stok melebihi stok yang tersedia.'
            ]);
        }

        $keterangan = $stok->barang . ' ' . $kuantitas . ' ' . $stok->satuan;
        $keluar = $kuantitas / $ambil->kuantitas * $ambil->harga;

        Bukukas::where('id', $ambil->bukukas)->update([
            'tahun' => Carbon::parse(now())->year,
            'proyek' => $proyek,
            'tanggal' => $tanggal,
            'uraian' => $keterangan,
            'kategori' => $kategori,
            'keluar' => $keluar,
            'kreator' => Session::get('id'),
            'ambil_stok' => 1,
        ]);

        Stok::where('id', $idstok)->update([
            'kuantitas' => $stok->kuantitas + $ambil->kuantitas - $kuantitas,
            'harga' => $stok->harga + $ambil->harga - $keluar,
        ]);

        Ambil::where('bukukas', $id)->update([
            'tanggal' => $tanggal,
            'stok' => $idstok,
            'kuantitas' => $kuantitas,
            'harga' => $keluar,
            'bukukas' => $ambil->bukukas,
            'kreator' => Session::get('id'),
        ]);

        notify()->success('Transaksi berhasil diupdate.');
        return redirect('/dashboard/bukukas');
    }

    public function ambil_stok_edit($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['ambil'] = Ambil::where('bukukas', $id)
            ->join('bukukas', 'ambil_stok.bukukas', '=', 'bukukas.id')
            ->where('bukukas.tahun', Session::get('tahun'))
            ->first();
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->orderBy('nama', 'asc')->get();
        $data['kategori'] = Kategori::where(function ($query) {
            $query->where('id', '!=', 2);
        })->orderBy('nama', 'asc')->get();
        $data['stok'] = Stok::where('id', $data['ambil']->stok)->first();
        return view('dashboard.ambil_stok_edit', $data);
    }

    public function ambil_stok_hapus($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $ambil = Ambil::where('bukukas', $id)->first();
        $cek = Bukukas::where('id',$ambil->bukukas)->first();
        if (Session::get('tahun') != Carbon::parse(now())->year || $cek->tahun !== Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $stok = Stok::where('id', $ambil->stok)->first();

        if ($stok) {
            Stok::where('id', $ambil->stok)->update([
                'kuantitas' => $stok->kuantitas + $ambil->kuantitas,
                'harga' => $stok->harga + $ambil->harga,
            ]);
        }

        Ambil::where('bukukas', $id)->delete();

        Bukukas::where('id', $id)->delete();
        notify()->success('Transaksi berhasil dihapus.');
        return redirect('/dashboard/bukukas');
    }

    public function pajak()
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['pajak'] = Pajak::first();

        return view('dashboard.pajak', $data);
    }

    public function pajak_aksi(Request $request)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
        ];
        $attribute = [
            'pajak' => 'Besar pajak',
        ];
        $validator = Validator::make($request->all(), [
            'pajak' => 'required|numeric|gte:0',
        ], $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal mengupdate pajak.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        if ($request->input('id')) {
            Pajak::where('id', $request->id)->update([
                'pajak' => $request->pajak,
            ]);
        } else {
            Pajak::create([
                'pajak' => $request->pajak,
            ]);
        }

        notify()->success('Pajak berhasil ditambahkan.');
        return redirect('/dashboard/invoice');
    }

    public function invoice()
    {
        if (Session::get('role') === 'supervisor' || Session::get('role') === 'manager' || Session::get('role') === 'admin') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data_query = Invoice::where(function ($query) {
            if (Session::get('bulan')) :
                $sesi = Session::get('bulan');
                $start = Carbon::parse($sesi)->startOfMonth();
                $end = Carbon::parse($sesi)->endOfMonth();
                $query->where('tanggal', '>=', $start)
                    ->where('tanggal', '<=', $end);
            elseif (Session::get('mulai') || Session::get('selesai')) :
                $mulai = Session::get('mulai');
                $selesai = Session::get('selesai');
                if ($mulai && $selesai) :
                    $start = Carbon::parse($mulai)->startOfDay();
                    $end = Carbon::parse($selesai)->endOfDay();
                    $query->where('tanggal', '>=', $start)
                        ->where('tanggal', '<=', $end);
                elseif ($mulai) :
                    $start = Carbon::parse($mulai)->startOfDay();
                    $query->where('tanggal', '>=', $start);
                elseif ($selesai) :
                    $end = Carbon::parse($selesai)->endOfDay();
                    $query->where('tanggal', '<=', $end);
                endif;
            elseif (Session::get('tahun')) :
                $sesi = Carbon::create(Session::get('tahun'), 1, 31, 12, 0, 0);
                $start = Carbon::parse($sesi)->startOfYear();
                $end = Carbon::parse($sesi)->endOfYear();
                $query->where('tanggal', '>=', $start)
                    ->where('tanggal', '<=', $end);
            endif;
        })
            ->join('proyek', 'invoice.proyek', '=', 'proyek.id')
            ->where(function ($query) {
                if (Session::get('role') === 'operator') {
                    $query->where('proyek.pajak', 1);
                }
            })
            ->select('invoice.*', 'proyek.nama as namaproyek');

        if (Session::get('sort_keluar')) {
            if (Session::get('sort_keluar') === 'asc') {
                $data_query2 = $data_query->orderBy('invoice.total', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('invoice.total', 'desc');
            }
        }

        if (Session::get('sort_proyek')) {
            if (Session::get('sort_proyek') === 'asc') {
                $data_query2 = $data_query->orderBy('invoice.nama_perusahaan', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('invoice.nama_perusahaan', 'desc');
            }
        }

        if (Session::get('sort_tanggal')) {
            if (Session::get('sort_tanggal') === 'asc') {
                $data_query2 = $data_query->orderBy('invoice.tanggal', 'asc')->orderBy('id','asc');
            } else {
                $data_query2 = $data_query->orderBy('invoice.tanggal', 'desc')->orderBy('id','desc');
            }
        } else {
            $data_query2 = $data_query->orderBy('invoice.tanggal', 'desc')->orderBy('id','desc');
        }

        $data['invoice'] = $data_query2->paginate(50);
        return view('dashboard.invoice', $data);
    }

    public function invoice_tambah()
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->orderBy('nama', 'asc')->get();
        return view('dashboard.invoice_tambah', $data);
    }

    public function invoice_aksi(Request $request)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
        ];
        $attribute = [
            'tanggal' => 'Tanggal Invoice',
            'nama_perusahaan' => 'Nama Perusahaan',
            'keterangan.*' => 'Keterangan',
            'nominal.*' => 'Nominal Pembayaran',
        ];
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'nama_perusahaan' => 'required',
            'keterangan.*' => 'required',
            'nominal.*' => 'required|numeric|gte:0',
            'dp' => 'nullable|numeric|gte:0',
        ], $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal menambahkan invoice.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $faktur_pajak = $request->input('faktur_pajak');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo') ? date('Y-m-d', strtotime($request->input('tanggal_jatuh_tempo'))) : null;
        $nama_perusahaan = $request->input('nama_perusahaan');
        $alamat = $request->input('alamat');
        $telp = $request->input('telp');
        $npwp = $request->input('npwp');
        $dp = $request->input('dp');
        $nominal = $request->input('nominal');
        $ket = $request->input('keterangan');
        $proyek = $request->input('proyek');

        $dbproyek = Proyek::where('id', $proyek)->first();

        if($ket){
            if(count($ket) > 1){
                $keterangan = '';
                $w = '';
                $total = 0;
                for ($i = 0; $i < count($ket); $i++){
                    $total += $nominal[$i];
                    if ($i == 0){
                        $w = $ket[$i];
                    } elseif($i == count($ket) - 1){
                        $w = ' dan '.$ket[$i];
                    } else {
                        $w = ', '.$ket[$i];
                    }
                    $keterangan = $keterangan.$w;
                }
                $cek_pajak = Proyek::where('id', $proyek)->first();
                $pajak = Pajak::first();
        
                if ($faktur_pajak && $cek_pajak->pajak === 1) {
                    $total2 = $total * (100 + $pajak->pajak) / 100;
                } else {
                    $total2 = $total;
                }
        
                $start = Carbon::parse($tanggal)->startOfMonth();
                $end = Carbon::parse($tanggal)->endOfMonth();
        
                $no = Invoice::where('tanggal', '>=', $start)->where('tanggal', '<=', $end)->count() + 1;
                if (strlen($no) == 1) {
                    $no = "0" . $no;
                }
                $month = Helper::numberToRoman(Carbon::parse($tanggal)->month);
                $year = Carbon::parse($tanggal)->year;
        
                $no_invoice = $no . "/" . $month . "/SG/" . $year;

                $bukukas = Bukukas::create([
                    'tahun' => Carbon::parse(now())->year,
                    'proyek' => $proyek,
                    'tanggal' => $tanggal,
                    'uraian' => $keterangan,
                    'kategori' => 2,
                    'masuk' => $total,
                    'kreator' => Session::get('id'),
                    'ambil_stok' => 2,
                ]);

                Proyek::where('id', $proyek)->update([
                    'nilai' => $dbproyek->nilai + $total
                ]);
        
                $invoice = Invoice::create([
                    'no_invoice' => $no_invoice,
                    'tahun' => Carbon::parse(now())->year,
                    'faktur_pajak' => $faktur_pajak,
                    'tanggal' => $tanggal,
                    'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo,
                    'nama_perusahaan' => $nama_perusahaan,
                    'alamat' => $alamat,
                    'telp' => $telp,
                    'npwp' => $npwp,
                    'dp' => $dp,
                    'subtotal' => $total - $dp,
                    'total' => $total2,
                    'keterangan' => $keterangan,
                    'proyek' => $proyek,
                    'kreator' => Session::get('id'),
                    'bukukas' => $bukukas->id,
                ]);
                $idinvoice = $invoice->id;
                for ($i = 0; $i < count($ket); $i++){
                    InvoiceDetail::create([
                        'invoice' => $idinvoice,
                        'keterangan' => $ket[$i],
                        'nominal' => $nominal[$i],
                    ]);
                }
            } else {
                $total = $nominal[0];
                $keterangan = $ket[0];
                $cek_pajak = Proyek::where('id', $proyek)->first();
                $pajak = Pajak::first();
        
                if ($faktur_pajak && $cek_pajak->pajak === 1) {
                    $total2 = $total * (100 + $pajak->pajak) / 100;
                } else {
                    $total2 = $total;
                }
        
                $start = Carbon::parse($tanggal)->startOfMonth();
                $end = Carbon::parse($tanggal)->endOfMonth();
        
                $no = Invoice::where('tanggal', '>=', $start)->where('tanggal', '<=', $end)->count() + 1;
                if (strlen($no) == 1) {
                    $no = "0" . $no;
                }
                $month = Helper::numberToRoman(Carbon::parse($tanggal)->month);
                $year = Carbon::parse($tanggal)->year;
        
                $no_invoice = $no . "/" . $month . "/SG/" . $year;

                $bukukas = Bukukas::create([
                    'tahun' => Carbon::parse(now())->year,
                    'proyek' => $proyek,
                    'tanggal' => $tanggal,
                    'uraian' => $keterangan,
                    'kategori' => 2,
                    'masuk' => $total,
                    'kreator' => Session::get('id'),
                    'ambil_stok' => 2,
                ]);

                Proyek::where('id', $proyek)->update([
                    'nilai' => $dbproyek->nilai + $total
                ]);
        
                Invoice::create([
                    'tahun' => Carbon::parse(now())->year,
                    'no_invoice' => $no_invoice,
                    'faktur_pajak' => $faktur_pajak,
                    'tanggal' => $tanggal,
                    'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo,
                    'nama_perusahaan' => $nama_perusahaan,
                    'alamat' => $alamat,
                    'telp' => $telp,
                    'npwp' => $npwp,
                    'dp' => $dp,
                    'subtotal' => $total - $dp,
                    'total' => $total2,
                    'keterangan' => $keterangan,
                    'proyek' => $proyek,
                    'kreator' => Session::get('id'),
                    'bukukas' => $bukukas->id,
                ]);
            }
        }

        notify()->success('Invoice berhasil ditambahkan.');
        return redirect('/dashboard/invoice');
    }

    public function invoice_edit($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        if (Session::get('tahun') != Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->orderBy('nama', 'asc')->get();
        $data['invoice'] = Invoice::where('tahun', Session::get('tahun'))->where('id', $id)->first();
        return view('dashboard.invoice_edit', $data);
    }

    public function invoice_update(Request $request)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $id = $request->input('id');
        $cek = Invoice::where('id',$id)->first();
        if (Session::get('tahun') != Carbon::parse(now())->year || $cek->tahun !== Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
        ];
        $attribute = [
            'tanggal' => 'Tanggal Invoice',
            'nama_perusahaan' => 'Nama Perusahaan',
            'keterangan.*' => 'Keterangan',
            'nominal.*' => 'Nominal Pembayaran',
            'dp' => 'DP',
        ];
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'nama_perusahaan' => 'required',
            'keterangan.*' => 'required',
            'nominal.*' => 'required|numeric|gte:0',
            'dp' => 'nullable|numeric|gte:0',
        ], $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal mengupdate invoice.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $faktur_pajak = $request->input('faktur_pajak');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo') ? date('Y-m-d', strtotime($request->input('tanggal_jatuh_tempo'))) : null;
        $nama_perusahaan = $request->input('nama_perusahaan');
        $alamat = $request->input('alamat');
        $telp = $request->input('telp');
        $npwp = $request->input('npwp');
        $dp = $request->input('dp');
        $nominal = $request->input('nominal');
        $ket = $request->input('keterangan');

        $proyek = $request->input('proyek');
        $iddetail = $request->input('id_detail');

        $dbproyek = Proyek::where('id',$cek->proyek)->first();

        if($ket){
            if(count($ket) > 1){
                $keterangan = '';
                $w = '';
                $total = 0;
                for ($i = 0; $i < count($ket); $i++){
                    $total += $nominal[$i];
                    if ($i == 0){
                        $w = $ket[$i];
                    } elseif($i == count($ket) - 1){
                        $w = ' dan '.$ket[$i];
                    } else {
                        $w = ', '.$ket[$i];
                    }
                    $keterangan = $keterangan.$w;
                }
                $cek_pajak = Proyek::where('id', $proyek)->first();
                $pajak = Pajak::first();
        
                if ($faktur_pajak && $cek_pajak->pajak === 1) {
                    $total2 = $total * (100 + $pajak->pajak) / 100;
                } else {
                    $total2 = $total;
                }
        
                $start = Carbon::parse($tanggal)->startOfMonth();
                $end = Carbon::parse($tanggal)->endOfMonth();
        
                $oldno = explode("/", $cek->no_invoice);

                $month = Helper::numberToRoman(Carbon::parse($tanggal)->month);
                $year = Carbon::parse($tanggal)->year;

                if ($oldno[1] == $month && $oldno[3] == $year){
                    $no_invoice = $cek->no_invoice;
                } else {
                    $no = Invoice::where('tanggal', '>=', $start)->where('tanggal', '<=', $end)->count() + 1;

                    if (strlen($no) == 1) {
                        $no = "0" . $no;
                    }
            
                    $no_invoice = $no . "/" . $month . "/SG/" . $year;
                }


                Bukukas::where('id',$cek->bukukas)->update([
                    'proyek' => $proyek,
                    'tanggal' => $tanggal,
                    'uraian' => $keterangan,
                    'masuk' => $total,
                    'kreator' => Session::get('id'),
                ]);

                Proyek::where('id', $cek->proyek)->update([
                    'nilai' => $dbproyek->nilai - $cek->subtotal - $cek->dp
                ]);

                $newproyek = Proyek::where('id',$proyek)->first();

                Proyek::where('id', $proyek)->update([
                    'nilai' => $newproyek->nilai + $total
                ]);
        
                Invoice::where('id',$id)->update([
                    'no_invoice' => $no_invoice,
                    'faktur_pajak' => $faktur_pajak,
                    'tanggal' => $tanggal,
                    'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo,
                    'nama_perusahaan' => $nama_perusahaan,
                    'alamat' => $alamat,
                    'telp' => $telp,
                    'npwp' => $npwp,
                    'dp' => $dp,
                    'subtotal' => $total - $dp,
                    'total' => $total2,
                    'keterangan' => $keterangan,
                    'proyek' => $proyek,
                    'kreator' => Session::get('id'),
                ]);
                for ($i = 0; $i < count($ket); $i++){
                    if($iddetail[$i]){
                        InvoiceDetail::where('id',$iddetail[$i])->update([
                            'keterangan' => $ket[$i],
                            'nominal' => $nominal[$i],
                        ]);
                    } else {
                        InvoiceDetail::create([
                            'invoice' => $id,
                            'keterangan' => $ket[$i],
                            'nominal' => $nominal[$i],
                        ]);
                    }
                }
            } else {
                $total = $nominal[0];
                $keterangan = $ket[0];
                $cek_pajak = Proyek::where('id', $proyek)->first();
                $pajak = Pajak::first();
        
                if ($faktur_pajak && $cek_pajak->pajak === 1) {
                    $total2 = $total * (100 + $pajak->pajak) / 100;
                } else {
                    $total2 = $total;
                }
        
                $start = Carbon::parse($tanggal)->startOfMonth();
                $end = Carbon::parse($tanggal)->endOfMonth();
        
                $oldno = explode("/", $cek->no_invoice);

                $month = Helper::numberToRoman(Carbon::parse($tanggal)->month);
                $year = Carbon::parse($tanggal)->year;
                
                if ($oldno[1] == $month && $oldno[3] == $year){
                    $no_invoice = $cek->no_invoice;
                } else {
                    $no = Invoice::where('tanggal', '>=', $start)->where('tanggal', '<=', $end)->count() + 1;

                    if (strlen($no) == 1) {
                        $no = "0" . $no;
                    }
            
                    $no_invoice = $no . "/" . $month . "/SG/" . $year;
                }

                Bukukas::where('id',$cek->bukukas)->update([
                    'proyek' => $proyek,
                    'tanggal' => $tanggal,
                    'uraian' => $keterangan,
                    'masuk' => $total,
                    'kreator' => Session::get('id'),
                ]);

                Proyek::where('id', $cek->proyek)->update([
                    'nilai' => $dbproyek->nilai - $cek->subtotal - $cek->dp
                ]);

                $newproyek = Proyek::where('id',$proyek)->first();

                Proyek::where('id', $proyek)->update([
                    'nilai' => $newproyek->nilai + $total
                ]);
        
                Invoice::where('id',$id)->update([
                    'no_invoice' => $no_invoice,
                    'faktur_pajak' => $faktur_pajak,
                    'tanggal' => $tanggal,
                    'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo,
                    'nama_perusahaan' => $nama_perusahaan,
                    'alamat' => $alamat,
                    'telp' => $telp,
                    'npwp' => $npwp,
                    'dp' => $dp,
                    'subtotal' => $total - $dp,
                    'total' => $total2,
                    'keterangan' => $keterangan,
                    'proyek' => $proyek,
                    'kreator' => Session::get('id'),
                ]);
            }
        }

        notify()->success('Invoice berhasil diupdate.');
        return redirect('/dashboard/invoice');
    }

    public function invoice_hapus($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $cek = Invoice::where('id',$id)->first();
        if (Session::get('tahun') != Carbon::parse(now())->year || $cek->tahun !== Carbon::parse(now())->year) {
            notify()->error('Akses dilarang.');
            return back();
        }
        Invoice::where('id', $id)->delete();
        InvoiceDetail::where('invoice',$id)->delete();
        notify()->success('Invoice berhasil dihapus.');
        return redirect('/dashboard/invoice');
    }

    public function invoice_cetak($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['invoice'] = Invoice::where('id', $id)->first();
        return view('/dashboard/invoice_cetak', $data);
    }

    public function stok()
    {
        if (Session::get('role') === 'supervisor' || Session::get('role') === 'manager') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['stok'] = Stok::where('kuantitas', '>', 0)->orderBy('tanggal', 'asc')->get();
        return view('dashboard.stok', $data);
    }

    public function stok_tambah()
    {
        if (Session::get('role') !== 'owner' && Session::get('role') !== 'admin') {
            notify()->error('Akses dilarang.');
            return back();
        }
        return view('dashboard.stok_tambah');
    }

    public function stok_aksi(Request $request)
    {
        if (Session::get('role') !== 'owner' && Session::get('role') !== 'admin') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'mimes' => ':attribute harus jpg, jpeg atau png.',
            'numeric' => ':attribute harus berupa angka.',
            'gt' => ':attribute harus lebih dari 0.',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
            'max' => ':attribute melebihi :max kB.',
        ];
        $attribute = [
            'tanggal' => 'Tanggal pembelian barang',
            'barang' => 'Nama barang',
            'nota' => 'Nota',
            'bukti' => 'Nomor Nota',
            'kuantitas' => 'Kuantitas barang',
            'satuan' => 'Satuan jumlah barang',
            'harga' => 'Harga barang',
        ];
        $rules = [
            'tanggal' => 'required',
            'barang' => 'required',
            'kuantitas' => 'required|numeric|gt:0',
            'nota' => 'mimes:jpg,jpeg,png|max:10240',
            'satuan' => 'required',
            'harga' => 'required|numeric|gte:0',
        ];
        if ($request->hasFile('nota')) {
            $rules['bukti'] = 'required';
        }
        $validator = Validator::make($request->all(), $rules, $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal menambahkan stok.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $barang = $request->input('barang');
        $kuantitas = $request->input('kuantitas');
        $satuan = $request->input('satuan');
        $harga = $request->input('harga');
        $bukti = $request->input('bukti');

        $stok = Stok::create([
            'tanggal' => $tanggal,
            'barang' => $barang,
            'kuantitas' => $kuantitas,
            'satuan' => $satuan,
            'harga' => $harga,
            'kreator' => Session::get('id'),
        ]);

        $idstok = $stok->id;

        if ($request->hasFile('nota')) {
            $gbr = $request->file('nota');
            $ext = $request->nota->extension();
            $slug = Str::slug($bukti, '-');
            $gbrnama = $slug . '-' . rand(99, 999) . '.' . $ext;
            $path = public_path('/images/nota/' . $gbrnama);
            $gbresize = Image::make($gbr->path());
            $width = $gbresize->width();
            $height = $gbresize->height();
            if ($width > $height) {
                $gbresize->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path);
            } else {
                $gbresize->resize(null, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path);
            }

            Stok::where('id', $idstok)->update([
                'nota' => $gbrnama,
                'no_bukti' => $bukti,
            ]);
        }

        notify()->success('Stok berhasil ditambahkan.');
        return redirect('/dashboard/stok');
    }

    public function stok_edit($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['stok'] = stok::where('id', $id)->first();
        return view('dashboard.stok_edit', $data);
    }

    public function stok_update(Request $request)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'mimes' => ':attribute harus jpg, jpeg atau png.',
            'numeric' => ':attribute harus berupa angka.',
            'gt' => ':attribute harus lebih dari 0.',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
            'max' => ':attribute melebihi :max kB.',
        ];
        $attribute = [
            'tanggal' => 'Tanggal pembelian barang',
            'barang' => 'Nama barang',
            'kuantitas' => 'Kuantitas barang',
            'satuan' => 'Satuan jumlah barang',
            'harga' => 'Harga barang',
            'nota' => 'Nota',
            'bukti' => 'Nomor Nota'
        ];
        $rules = [
            'tanggal' => 'required',
            'barang' => 'required',
            'kuantitas' => 'required|numeric|gt:0',
            'nota' => 'mimes:jpg,jpeg,png|max:10240',
            'satuan' => 'required',
            'harga' => 'required|numeric|gte:0',
        ];
        if ($request->hasFile('nota')) {
            $rules['bukti'] = 'required';
        }
        $validator = Validator::make($request->all(), $rules, $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal mengupdate stok.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $id = $request->input('id');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $barang = $request->input('barang');
        $kuantitas = $request->input('kuantitas');
        $satuan = $request->input('satuan');
        $harga = $request->input('harga');
        $bukti = $request->input('bukti');

        Stok::where('id', $id)->update([
            'tanggal' => $tanggal,
            'barang' => $barang,
            'kuantitas' => $kuantitas,
            'satuan' => $satuan,
            'harga' => $harga,
            'kreator' => Session::get('id'),
        ]);

        $nota = Stok::where('id', $id)->first();

        if ($request->input('d_nota') === 'hapus') {
            unlink(public_path('/images/nota/' . $nota->nota));
            Stok::where('id', $id)->update([
                'nota' => '',
                'no_bukti' => '',
            ]);
        }

        if ($request->hasFile('nota')) {
            if ($nota->nota) {
                unlink(public_path('/images/nota/' . $nota->nota));
            }

            $gbr = $request->file('nota');
            $ext = $request->nota->extension();
            $slug = Str::slug($bukti, '-');
            $gbrnama = $slug . '-' . rand(99, 999) . '.' . $ext;
            $path = public_path('/images/nota/' . $gbrnama);
            $gbresize = Image::make($gbr->path());
            $width = $gbresize->width();
            $height = $gbresize->height();
            if ($width > $height) {
                $gbresize->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path);
            } else {
                $gbresize->resize(null, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path);
            }

            Stok::where('id', $id)->update([
                'nota' => $gbrnama,
                'no_bukti' => $bukti,
            ]);
        }

        notify()->success('Stok berhasil diupdate.');
        return redirect('/dashboard/stok');
    }

    public function stok_hapus($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $nota = Stok::where('id', $id)->first();
        if ($nota->nota) {
            if (file_exists(public_path('/images/nota/' . $nota->nota))) {
                unlink(public_path('/images/nota/' . $nota->nota));
            }
        }

        stok::where('id', $id)->delete();

        notify()->success('Stok berhasil dihapus.');
        return redirect('/dashboard/stok');
    }

    public function bukukas_search(Request $request)
    {
        if (Session::get('role') === 'supervisor' || Session::get('role') === 'manager') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $search = htmlentities(trim($request->input('search')) ? trim($request->input('search')) : '');
        $data['search'] = $search;

        $data['proyek'] = Proyek::where(function ($query) {
            if (Session::get('role') === 'operator') {
                $query->where('proyek.pajak', 1);
            }
        })->where('tahun', Session::get('tahun'))
            ->orderBy('nama', 'asc')->get();
        $data['kategori'] = Kategori::where(function ($query) {
            $query->where('id', '!=', 2);
        })->orderBy('nama', 'asc')->get();

        $data_query = Bukukas::where('bukukas.tahun', Session::get('tahun'))
        ->where(function ($query) use ($search) {
            $query
                ->where('keterangan', 'like', '%' . $search . '%')
                ->orWhere('uraian', 'like', '%' . $search . '%')
                ->orWhere('no_nota', 'like', '%' . $search . '%')
                ->orWhere('masuk', 'like', '%' . $search . '%')
                ->orWhere('keluar', 'like', '%' . $search . '%');
        })
            ->where(function ($query) {
                if (Session::get('kategori')) :
                    $query->where('kategori', Session::get('kategori'));
                endif;
            })
            ->where(function ($query) {
                if (Session::get('proyek')) :
                    $query->where('proyek', Session::get('proyek'));
                endif;
            })
            ->where(function ($query) {
                if (Session::get('bulan')) :
                    $sesi = Session::get('bulan');
                    $start = Carbon::parse($sesi)->startOfMonth();
                    $end = Carbon::parse($sesi)->endOfMonth();
                    $query->where('tanggal', '>=', $start)
                        ->where('tanggal', '<=', $end);
                elseif (Session::get('mulai') || Session::get('selesai')) :
                    $mulai = Session::get('mulai');
                    $selesai = Session::get('selesai');
                    if ($mulai && $selesai) :
                        $start = Carbon::parse($mulai)->startOfDay();
                        $end = Carbon::parse($selesai)->endOfDay();
                        $query->where('tanggal', '>=', $start)
                            ->where('tanggal', '<=', $end);
                    elseif ($mulai) :
                        $start = Carbon::parse($mulai)->startOfDay();
                        $query->where('tanggal', '>=', $start);
                    elseif ($selesai) :
                        $end = Carbon::parse($selesai)->endOfDay();
                        $query->where('tanggal', '<=', $end);
                    endif;
                elseif (Session::get('tahun')) :
                    $sesi = Carbon::create(Session::get('tahun'), 1, 31, 12, 0, 0);
                    $start = Carbon::parse($sesi)->startOfYear();
                    $end = Carbon::parse($sesi)->endOfYear();
                    $query->where('tanggal', '>=', $start)
                        ->where('tanggal', '<=', $end);
                endif;
            })
            ->join('kategori', 'bukukas.kategori', '=', 'kategori.id')
            ->join('proyek', 'bukukas.proyek', '=', 'proyek.id')
            ->select('bukukas.*', 'proyek.nama as namaproyek', 'kategori.nama as namakategori');

        if (Session::get('sort_bukti')) {
            if (Session::get('sort_bukti') === 'asc') {
                $data_query2 = $data_query->orderBy('bukukas.no_bukti', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('bukukas.no_bukti', 'desc');
            }
        }

        if (Session::get('sort_kategori')) {
            if (Session::get('sort_kategori') === 'asc') {
                $data_query2 = $data_query->orderBy('kategori.nama', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('kategori.nama', 'desc');
            }
        }

        if (Session::get('sort_masuk')) {
            if (Session::get('sort_masuk') === 'asc') {
                $data_query2 = $data_query->orderBy('bukukas.masuk', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('bukukas.masuk', 'desc');
            }
        }

        if (Session::get('sort_keluar')) {
            if (Session::get('sort_keluar') === 'asc') {
                $data_query2 = $data_query->orderBy('bukukas.keluar', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('bukukas.keluar', 'desc');
            }
        }

        if (Session::get('sort_proyek')) {
            if (Session::get('sort_proyek') === 'asc') {
                $data_query2 = $data_query->orderBy('proyek.nama', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('proyek.nama', 'desc');
            }
        }

        if (Session::get('sort_tanggal')) {
            if (Session::get('sort_tanggal') === 'asc') {
                $data_query2 = $data_query->orderBy('bukukas.tanggal', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('bukukas.tanggal', 'desc');
            }
        } else {
            $data_query2 = $data_query->orderBy('bukukas.tanggal', 'asc');
        }

        $data_query3 = Bukukas::where('bukukas.tahun', Session::get('tahun'))
        ->where('keterangan', 'like', '%' . $search . '%')
            ->orWhere('uraian', 'like', '%' . $search . '%')
            ->orWhere('no_nota', 'like', '%' . $search . '%')
            ->orWhere('masuk', 'like', '%' . $search . '%')
            ->orWhere('keluar', 'like', '%' . $search . '%')
            ->join('kategori', 'bukukas.kategori', '=', 'kategori.id')
            ->join('proyek', 'bukukas.proyek', '=', 'proyek.id')
            ->where(function ($query) {
                if (Session::get('role') === 'operator') {
                    $query->where('proyek.pajak', 1);
                }
            })
            ->select('bukukas.*', 'proyek.nama as namaproyek', 'kategori.nama as namakategori');

        $data['bukukas'] = $data_query2->paginate(100);

        $data['keluar'] = $data_query3->sum('bukukas.keluar');
        $data['masuk'] = $data_query3->sum('bukukas.masuk');
        return view('dashboard.bukukas', $data);
    }

    public function invoice_search(Request $request)
    {
        if (Session::get('role') === 'supervisor' || Session::get('role') === 'manager') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $search = htmlentities(trim($request->input('search')) ? trim($request->input('search')) : '');
        $data['search'] = $search;

        $data_query = Invoice::where('invoice.tahun', Session::get('tahun'))
        ->where(function ($query) use ($search) {
            $query
                ->where('keterangan', 'like', '%' . $search . '%')
                ->orWhere('no_invoice', 'like', '%' . $search . '%')
                ->orWhere('total', 'like', '%' . $search . '%')
                ->orWhere('nama_perusahaan', 'like', '%' . $search . '%');
        })
            ->where(function ($query) {
                if (Session::get('bulan')) :
                    $sesi = Session::get('bulan');
                    $start = Carbon::parse($sesi)->startOfMonth();
                    $end = Carbon::parse($sesi)->endOfMonth();
                    $query->where('tanggal', '>=', $start)
                        ->where('tanggal', '<=', $end);
                elseif (Session::get('tahun')) :
                    $sesi = Carbon::create(Session::get('tahun'), 1, 31, 12, 0, 0);
                    $start = Carbon::parse($sesi)->startOfYear();
                    $end = Carbon::parse($sesi)->endOfYear();
                    $query->where('tanggal', '>=', $start)
                        ->where('tanggal', '<=', $end);
                elseif (Session::get('mulai') || Session::get('selesai')) :
                    $mulai = Session::get('mulai');
                    $selesai = Session::get('selesai');
                    if ($mulai && $selesai) :
                        $start = Carbon::parse($mulai)->startOfDay();
                        $end = Carbon::parse($selesai)->endOfDay();
                        $query->where('tanggal', '>=', $start)
                            ->where('tanggal', '<=', $end);
                    elseif ($mulai) :
                        $start = Carbon::parse($mulai)->startOfDay();
                        $query->where('tanggal', '>=', $start);
                    elseif ($selesai) :
                        $end = Carbon::parse($selesai)->endOfDay();
                        $query->where('tanggal', '<=', $end);
                    endif;
                endif;
            })
            ->join('proyek', 'invoice.proyek', '=', 'proyek.id')
            ->where(function ($query) {
                if (Session::get('role') === 'operator') {
                    $query->where('proyek.pajak', 1);
                }
            })
            ->select('invoice.*', 'proyek.nama as namaproyek');

        if (Session::get('sort_keluar')) {
            if (Session::get('sort_keluar') === 'asc') {
                $data_query2 = $data_query->orderBy('invoice.total', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('invoice.total', 'desc');
            }
        }

        if (Session::get('sort_proyek')) {
            if (Session::get('sort_proyek') === 'asc') {
                $data_query2 = $data_query->orderBy('invoice.nama_perusahaan', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('invoice.nama_perusahaan', 'desc');
            }
        }

        if (Session::get('sort_tanggal')) {
            if (Session::get('sort_tanggal') === 'asc') {
                $data_query2 = $data_query->orderBy('invoice.tanggal', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('invoice.tanggal', 'desc');
            }
        } else {
            $data_query2 = $data_query->orderBy('invoice.tanggal', 'asc');
        }

        $data['invoice'] = $data_query2->paginate(50);
        return view('dashboard.invoice', $data);
    }

    public function proyek_search(Request $request)
    {
        if (Session::get('role') === 'supervisor' || Session::get('role') === 'manager') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $search = htmlentities(trim($request->input('search')) ? trim($request->input('search')) : '');
        $data['search'] = $search;

        $data_query = Proyek::where(function ($query) {
            if (Session::get('role') === 'operator') :
                $query->where('pajak', 1);
            endif;
        })->where('proyek.tahun', Session::get('tahun'))
        ->where(function ($query) use ($search) {
            $query
                ->where('kode', 'like', '%' . $search . '%')
                ->orWhere('nama', 'like', '%' . $search . '%')
                ->orWhere('nilai', 'like', '%' . $search . '%');
        });

        if (Session::get('sort_kategori')) {
            if (Session::get('sort_kategori') === 'asc') {
                $data_query2 = $data_query->orderBy('kode', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('kode', 'desc');
            }
        }

        if (Session::get('sort_keluar')) {
            if (Session::get('sort_keluar') === 'asc') {
                $data_query2 = $data_query->orderBy('nilai', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('nilai', 'desc');
            }
        }

        if (Session::get('sort_proyek')) {
            if (Session::get('sort_proyek') === 'asc') {
                $data_query2 = $data_query->orderBy('nama', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('nama', 'desc');
            }
        }

        if (Session::get('sort_tanggal')) {
            if (Session::get('sort_tanggal') === 'asc') {
                $data_query2 = $data_query->orderBy('id', 'asc');
            } else {
                $data_query2 = $data_query->orderBy('id', 'desc');
            }
        } else {
            $data_query2 = $data_query->orderBy('id', 'asc');
        }

        $data['proyek'] = $data_query2->paginate(50);

        return view('dashboard.proyek', $data);
    }
    public function bukukas_refresh(Request $request, $link)
    {
        $request->session()->forget([
            'proyek',
            'kategori',
            'bulan',
            'mulai',
            'selesai',
            'sort_tanggal',
            'sort_proyek',
            'sort_kategori',
            'sort_bukti',
            'sort_masuk',
            'sort_keluar',
        ]);

        switch ($link):
            case "bukukas":
                return redirect('/dashboard/bukukas');
                break;
            case "proyek":
                return redirect('/dashboard/proyek');
                break;
            case "invoice":
                return redirect('/dashboard/invoice');
                break;
            default:
                return redirect('/dashboard/bukukas');
        endswitch;
    }
}
