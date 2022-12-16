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
        $id = $request->input('id');

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
            return Redirect::back()->withErrors($validator);
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
        $pajak = $request->input('pajak');

        Proyek::create([
            'nama' => $nama,
            'kode' => $kode,
            'nilai' => $nilai,
            'pajak' => $pajak,
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
        $pajak = $request->input('pajak');

        Proyek::where('id', $id)->update([
            'nama' => $nama,
            'kode' => $kode,
            'nilai' => $nilai,
            'pajak' => $pajak,
            'kreator' => Session::get('id'),
        ]);

        return redirect('/dashboard/proyek');
    }

    public function proyek_hapus($id)
    {
        Proyek::where('id', $id)->delete();

        return redirect('/dashboard/proyek');
    }

    public function filter(Request $request)
    {
        $submit = $request->input('submit');
        if ($submit == "Filter") {
            $kategori = $request->input('kategori');
            $proyek = $request->input('proyek');
            $mulai = $request->input('mulai') != '-' ? $request->input('mulai') : '';
            $selesai = $request->input('selesai') != '-' ? $request->input('selesai') : '';
            $bulan = $request->input('bulan');

            $request->session()->put([
                'kategori' => $kategori,
                'proyek' => $proyek,
                'mulai' => $mulai,
                'selesai' => $selesai,
                'bulan' => $bulan,
            ]);

            return redirect('/dashboard/bukukas');
        } else {
            $request->session()->forget(['proyek', 'kategori', 'bulan', 'mulai', 'selesai']);

            return redirect('/dashboard/bukukas');
        }
    }

    public function export()
    {
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

        // return Excel::download(new UsersExport, 'users.xlsx');
        $sheet->setCellValue('A1', 'Laporan Keuangan Buku Kas CV. Sola Gracia');
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Proyek');
        $sheet->setCellValue('C3', 'Tanggal');
        $sheet->setCellValue('D3', 'Keterangan');
        $sheet->setCellValue('E3', 'Kategori');
        $sheet->setCellValue('F3', 'No Bukti');
        $sheet->setCellValue('G3', 'Masuk');
        $sheet->setCellValue('H3', 'Keluar');

        $no = 4;
        foreach ($bukukas as $b) {
            $sheet->setCellValue('A' . $no, $no - 3);
            $sheet->setCellValue('B' . $no, $b->namaproyek);
            $sheet->setCellValue('C' . $no, $b->tanggal);
            $sheet->setCellValue('D' . $no, $b->keterangan);
            $sheet->setCellValue('E' . $no, $b->namakategori);
            $sheet->setCellValue('F' . $no, $b->no_bukti ?? '-');
            $sheet->setCellValue('G' . $no, $b->masuk ?? '-');
            $sheet->setCellValue('H' . $no, $b->keluar ?? '-');
            $no++;
        }

        $sheet->setCellValue('A' . $no, 'Jumlah');
        $sheet->setCellValue('G' . $no, $masuk);
        $sheet->setCellValue('H' . $no, $keluar);

        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A' . $no . ':F' . $no);

        $sheet->getStyle('G4:H' . $no)->getNumberFormat()->setFormatCode('"Rp "#,##0');

        $sheet->getColumnDimension('A')->setWidth(25, 'px');
        $sheet->getColumnDimension('B')->setWidth(100, 'px');
        $sheet->getColumnDimension('C')->setWidth(100, 'px');
        $sheet->getColumnDimension('D')->setWidth(180, 'px');
        $sheet->getColumnDimension('E')->setWidth(90, 'px');
        $sheet->getColumnDimension('F')->setWidth(80, 'px');
        $sheet->getColumnDimension('G')->setWidth(120, 'px');
        $sheet->getColumnDimension('H')->setWidth(120, 'px');

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

        $sheet->getStyle('A1')->applyFromArray($centerBold);
        $sheet->getStyle('A3:F3')->applyFromArray($bold);
        $sheet->getStyle('G3:H3')->applyFromArray($rightBold);
        $sheet->getStyle('F4:F' . $no - 1)->applyFromArray($left);
        $sheet->getStyle('G4:H' . $no - 1)->applyFromArray($right);
        // $sheet->getStyle('A4:B'.($no-1))->applyFromArray($center);
        // $sheet->getStyle('D4:E'.($no-1))->applyFromArray($center);
        $sheet->getStyle('A' . $no . ':F' . $no)->applyFromArray($rightBold);
        $sheet->getStyle('G' . $no . ':H' . $no)->applyFromArray($rightBold);

        $sheet->getStyle('A3:H3')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A' . $no . ':H' . $no)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan Keuangan.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function bukukas_sort(Request $request){
        if (Session::get('sort')){
            $request->session()->forget(['sort']);
        } else {
            $request->session()->put([
                'sort' => 'sort'
            ]);
        }

        return redirect('/dashboard/bukukas');
    }

    public function bukukas()
    {
        $data['proyek'] = Proyek::get();
        $data['kategori'] = Kategori::get();
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
            ->select('bukukas.*', 'proyek.nama as namaproyek', 'kategori.nama as namakategori');

        if (Session::get('sort')){
            $data_query2 = $data_query->orderBy('bukukas.tanggal', 'desc');
        } else {
            $data_query2 = $data_query->orderBy('bukukas.tanggal', 'asc');
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
                endif;
            })
            ->join('kategori', 'bukukas.kategori', '=', 'kategori.id')
            ->join('proyek', 'bukukas.proyek', '=', 'proyek.id')
            ->select('bukukas.*', 'proyek.nama as namaproyek', 'kategori.nama as namakategori');
        
        $data['bukukas'] = $data_query2->paginate(100);
        $data['keluar'] = $data_query3->sum('bukukas.keluar');
        $data['masuk'] = $data_query3->sum('bukukas.masuk');
        return view('dashboard.bukukas', $data);
    }

    public function bukukas_tambah()
    {
        $data['proyek'] = Proyek::get();
        $data['kategori'] = Kategori::get();
        return view('dashboard.bukukas_tambah', $data);
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
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $proyek = $request->input('proyek');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $keterangan = $request->input('keterangan');
        $kategori = $request->input('kategori');
        $bukti = $request->input('bukti');
        $masuk = $request->input('masuk');
        $keluar = $request->input('keluar');

        $bukukas = Bukukas::create([
            'proyek' => $proyek,
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'kategori' => $kategori,
            'no_bukti' => $bukti,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'kreator' => Session::get('id'),
        ]);

        $idbukukas = $bukukas->id;

        if ($request->hasFile('nota')) {
            $gbr = $request->file('nota');
            $ext = $request->nota->extension();
            $slug = Str::slug($bukti, '-');
            $gbrnama = $slug . '-' . rand(99, 999) . '.' . $ext;
            $path = public_path('/images/nota/' . $gbrnama);
            $gbresize = Image::make($gbr->path());
            $width = $gbresize->width();
            $height = $gbresize->height();
            if ($width > $height){
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
                'nota' => $gbrnama
            ]);
        }

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
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $keterangan = $request->input('keterangan');
        $kategori = $request->input('kategori');
        $bukti = $request->input('bukti');
        $masuk = $request->input('masuk');
        $keluar = $request->input('keluar');

        Bukukas::where('id', $id)->update([
            'proyek' => $proyek,
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'kategori' => $kategori,
            'no_bukti' => $bukti,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'kreator' => Session::get('id'),
        ]);

        if ($request->hasFile('nota')) {
            $nota = Bukukas::where('id', $id)->first();
            if ($nota->nota){
                unlink(public_path('/images/nota/'.$nota->nota));
            }

            $gbr = $request->file('nota');
            $ext = $request->nota->extension();
            $slug = Str::slug($bukti, '-');
            $gbrnama = $slug . '-' . rand(99, 999) . '.' . $ext;
            $path = public_path('/images/nota/' . $gbrnama);
            $gbresize = Image::make($gbr->path());
            $width = $gbresize->width();
            $height = $gbresize->height();
            if ($width > $height){
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
                'nota' => $gbrnama
            ]);
        }

        return redirect('/dashboard/bukukas');
    }

    public function bukukas_hapus($id)
    {
        $nota = Bukukas::where('id', $id)->first();
        if($nota->nota){
            unlink(public_path('/images/nota/'.$nota->nota));
        }
        
        bukukas::where('id', $id)->delete();

        return redirect('/dashboard/bukukas');
    }

    public function ambil_stok()
    {
        $data['proyek'] = Proyek::get();
        $data['kategori'] = Kategori::get();
        $data['stok'] = Stok::where('kuantitas', '>', 0)->get();
        return view('dashboard.ambil_stok', $data);
    }

    public function ambil_stok_aksi(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
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
            'kuantitas' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $proyek = $request->input('proyek');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $kategori = $request->input('kategori');
        $idstok = $request->input('stok');
        $kuantitas = $request->input('kuantitas');

        $stok = Stok::where('id', $idstok)->first();

        if ($kuantitas > $stok->kuantitas) {
            return Redirect::back()->withErrors([
                'kuantitas' => 'Jumlah ambil stok melebihi stok yang tersedia.'
            ]);
        }

        $keterangan = $stok->barang . ' ' . $kuantitas . ' ' . $stok->satuan;
        $keluar = $kuantitas / $stok->kuantitas * $stok->harga;

        $bukukas = Bukukas::create([
            'proyek' => $proyek,
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
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

        return redirect('/dashboard/bukukas');
    }

    public function ambil_stok_update(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
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
            'kuantitas' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $id = $request->input('id');
        $proyek = $request->input('proyek');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $kategori = $request->input('kategori');
        $idstok = $request->input('stok');
        $kuantitas = $request->input('kuantitas');

        $stok = Stok::where('id', $idstok)->first();
        $ambil = Ambil::where('id', $id)->first();

        if ($kuantitas > $stok->kuantitas + $ambil->kuantitas) {
            return Redirect::back()->withErrors([
                'kuantitas' => 'Jumlah ambil stok melebihi stok yang tersedia.'
            ]);
        }

        $keterangan = $stok->barang . ' ' . $kuantitas . ' ' . $stok->satuan;
        $keluar = $kuantitas / $ambil->kuantitas * $ambil->harga;

        Bukukas::where('id', $ambil->bukukas)->update([
            'proyek' => $proyek,
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'kategori' => $kategori,
            'keluar' => $keluar,
            'kreator' => Session::get('id'),
            'ambil_stok' => 1,
        ]);

        Stok::where('id', $idstok)->update([
            'kuantitas' => $stok->kuantitas + $ambil->kuantitas - $kuantitas,
            'harga' => $stok->harga + $ambil->harga - $keluar,
        ]);

        Ambil::where('id', $id)->update([
            'tanggal' => $tanggal,
            'stok' => $idstok,
            'kuantitas' => $kuantitas,
            'harga' => $keluar,
            'bukukas' => $ambil->bukukas,
            'kreator' => Session::get('id'),
        ]);

        return redirect('/dashboard/bukukas');
    }

    public function ambil_stok_edit($id)
    {
        $data['ambil'] = Ambil::where('bukukas', $id)->first();
        $data['proyek'] = Proyek::get();
        $data['kategori'] = Kategori::get();
        $data['stok'] = Stok::where('id', $data['ambil']->stok)->first();
        return view('dashboard.ambil_stok_edit', $data);
    }

    public function ambil_stok_hapus($id)
    {
        $ambil = Ambil::where('bukukas', $id)->first();
        $stok = Stok::where('id', $ambil->stok)->first();

        Stok::where('id', $ambil->stok)->update([
            'kuantitas' => $stok->kuantitas + $ambil->kuantitas,
            'harga' => $stok->harga + $ambil->harga,
        ]);

        Ambil::where('bukukas', $id)->delete();

        Bukukas::where('id', $id)->delete();

        return redirect('/dashboard/bukukas');
    }

    public function invoice()
    {
        $data['invoice'] = Invoice::get();
        return view('dashboard.invoice', $data);
    }

    public function invoice_tambah()
    {
        return view('dashboard.invoice_tambah');
    }

    public function invoice_aksi(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'tanggal' => 'Tanggal Invoice',
            'nama_perusahaan' => 'Nama Perusahaan',
            'keterangan' => 'Keterangan',
            'total' => 'Total Invoice',
        ];
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'nama_perusahaan' => 'required',
            'keterangan' => 'required',
            'total' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $faktur_pajak = $request->input('faktur_pajak');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo') ? date('Y-m-d', strtotime($request->input('tanggal_jatuh_tempo'))) : null;
        $nama_perusahaan = $request->input('nama_perusahaan');
        $alamat = $request->input('alamat');
        $telp = $request->input('telp');
        $npwp = $request->input('npwp');
        $dp = $request->input('dp');
        $subtotal = $request->input('subtotal');
        $total = $request->input('total');
        $keterangan = $request->input('keterangan');

        $start = Carbon::parse($tanggal)->startOfMonth();
        $end = Carbon::parse($tanggal)->endOfMonth();

        $no = Invoice::where('tanggal', '>=', $start)->where('tanggal', '<=', $end)->count();
        if (strlen($no) == 1) {
            $no = "0" . $no;
        }
        $month = Helper::numberToRoman(Carbon::parse($tanggal)->month);
        $year = Carbon::parse($tanggal)->year;

        $no_invoice = $no . "/" . $month . "/SG/" . $year;

        Invoice::create([
            'no_invoice' => $no_invoice,
            'faktur_pajak' => $faktur_pajak,
            'tanggal' => $tanggal,
            'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo,
            'nama_perusahaan' => $nama_perusahaan,
            'alamat' => $alamat,
            'telp' => $telp,
            'npwp' => $npwp,
            'dp' => $dp,
            'subtotal' => $subtotal,
            'total' => $total,
            'keterangan' => $keterangan,
            'kreator' => Session::get('id'),
        ]);

        return redirect('/dashboard/invoice');
    }

    public function invoice_edit($id)
    {
        $data['invoice'] = Invoice::where('id', $id)->first();
        return view('dashboard.invoice_edit', $data);
    }

    public function invoice_update(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'tanggal' => 'Tanggal Invoice',
            'nama_perusahaan' => 'Nama Perusahaan',
            'keterangan' => 'Keterangan',
            'total' => 'Total Invoice',
        ];
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'nama_perusahaan' => 'required',
            'keterangan' => 'required',
            'total' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $id = $request->input('id');
        $faktur_pajak = $request->input('faktur_pajak');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        $tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo') ? date('Y-m-d', strtotime($request->input('tanggal_jatuh_tempo'))) : null;
        $nama_perusahaan = $request->input('nama_perusahaan');
        $alamat = $request->input('alamat');
        $telp = $request->input('telp');
        $npwp = $request->input('npwp');
        $dp = $request->input('dp');
        $subtotal = $request->input('subtotal');
        $total = $request->input('total');
        $keterangan = $request->input('keterangan');

        Invoice::where('id', $id)->update([
            'faktur_pajak' => $faktur_pajak,
            'tanggal' => $tanggal,
            'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo,
            'nama_perusahaan' => $nama_perusahaan,
            'alamat' => $alamat,
            'telp' => $telp,
            'npwp' => $npwp,
            'dp' => $dp,
            'subtotal' => $subtotal,
            'total' => $total,
            'keterangan' => $keterangan,
            'kreator' => Session::get('id'),
        ]);

        return redirect('/dashboard/invoice');
    }

    public function invoice_hapus($id)
    {
        invoice::where('id', $id)->delete();

        return redirect('/dashboard/invoice');
    }

    public function invoice_cetak($id)
    {
        $data['invoice'] = Invoice::where('id', $id)->first();
        return view('/dashboard/invoice_cetak', $data);
    }

    public function stok()
    {
        $data['stok'] = Stok::where('kuantitas', '>', 0)->orderBy('tanggal', 'asc')->get();
        return view('dashboard.stok', $data);
    }

    public function stok_tambah()
    {
        return view('dashboard.stok_tambah');
    }

    public function stok_aksi(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'tanggal' => 'Tanggal pembelian barang',
            'barang' => 'Nama barang',
            'kuantitas' => 'Jumlah barang',
            'satuan' => 'Satuan jumlah barang',
            'harga' => 'Harga barang',
        ];
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'barang' => 'required',
            'kuantitas' => 'required',
            'satuan' => 'required',
            'harga' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
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
            'no_bukti' => $bukti,
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
            if ($width > $height){
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
                'nota' => $gbrnama
            ]);
        }

        return redirect('/dashboard/stok');
    }

    public function stok_edit($id)
    {
        $data['stok'] = stok::where('id', $id)->first();
        return view('dashboard.stok_edit', $data);
    }

    public function stok_update(Request $request)
    {
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'tanggal' => 'Tanggal pembelian barang',
            'barang' => 'Nama barang',
            'kuantitas' => 'Jumlah barang',
            'satuan' => 'Satuan jumlah barang',
            'harga' => 'Harga barang',
        ];
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'barang' => 'required',
            'kuantitas' => 'required',
            'satuan' => 'required',
            'harga' => 'required',
        ], $message, $attribute);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
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
            'no_bukti' => $bukti,
            'kreator' => Session::get('id'),
        ]);

        if ($request->hasFile('nota')) {
            $nota = Stok::where('id', $id)->first();
            if ($nota->nota){
                unlink(public_path('/images/nota/'.$nota->nota));
            }

            $gbr = $request->file('nota');
            $ext = $request->nota->extension();
            $slug = Str::slug($bukti, '-');
            $gbrnama = $slug . '-' . rand(99, 999) . '.' . $ext;
            $path = public_path('/images/nota/' . $gbrnama);
            $gbresize = Image::make($gbr->path());
            $width = $gbresize->width();
            $height = $gbresize->height();
            if ($width > $height){
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
                'nota' => $gbrnama
            ]);
        }

        return redirect('/dashboard/stok');
    }

    public function stok_hapus($id)
    {
        $nota = Stok::where('id', $id)->first();
        if ($nota->nota){
            unlink(public_path('/images/nota/'.$nota->nota));
        }
        
        stok::where('id', $id)->delete();

        return redirect('/dashboard/stok');
    }
}
