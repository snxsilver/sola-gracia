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
use App\Models\Borongan;
use App\Models\BoronganBayar;
use App\Models\GajiMandor;
use App\Models\GajiMandorTukang;
use App\Models\Harian;
use App\Models\HarianBayar;
use App\Models\Mandor;
use App\Models\MandorProyek;
use App\Models\MandorTukang;
use App\Models\Pajak;
use App\Models\Tukang;
use App\Models\Tunjangan;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GajiController extends Controller
{
    public function tukang_borongan()
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'admin') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['borongan'] = Borongan::where('borongan.tahun', Session::get('tahun'))->join('proyek', 'borongan.proyek', '=', 'proyek.id')
            ->select('borongan.*', 'proyek.nama as namaproyek')->get();
        return view('dashboard.borongan', $data);
    }

    public function tukang_borongan_tambah()
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'admin') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->orderBy('nama', 'asc')->get();

        return view('dashboard.borongan_tambah', $data);
    }

    public function tukang_borongan_aksi(Request $request)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'admin') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
        ];
        $attribute = [
            'proyek' => 'Proyek',
            'nama' => 'Nama',
            'tanggal.*' => 'Tanggal',
            'nominal.*' => 'Nominal',
        ];

        $rules = [
            'proyek' => 'required',
            'nama' => 'required',
            'tanggal.*' => 'required',
            'nominal.*' => 'required|numeric|gte:0',
        ];

        $validator = Validator::make($request->all(), $rules, $message, $attribute);


        if ($validator->fails()) {
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $proyek = $request->input('proyek');
        $nama = $request->input('nama');

        $borongan = Borongan::create([
            'tahun' => Carbon::parse(now())->year,
            'proyek' => $proyek,
            'nama' => $nama,
            'kreator' => Session::get('id'),
        ]);

        $idborongan = $borongan->id;
        $dbproyek = Proyek::where('id', $proyek)->first();
        $namaproyek = $dbproyek->nama;

        $tanggal = $request->input('tanggal');
        $nominal = $request->input('nominal');

        for ($i = 0; $i < count($tanggal); $i++) {
            if (Carbon::parse($tanggal[$i])->year < Carbon::parse(now())->year){
                $convtgl = Carbon::parse(now())->startOfYear();
            } else if(Carbon::parse($tanggal[$i])->year > Carbon::parse(now())->year){
                $convtgl = Carbon::parse(now())->endOfYear();
            } else {
                $convtgl = $tanggal[$i];
            }
            $bukukas = Bukukas::create([
                'tanggal' => date('Y-m-d', strtotime($convtgl)),
                'uraian' => 'Pembayaran ke ' . $borongan->nama . ' untuk mengerjakan Proyek ' . $namaproyek . '.',
                'keluar' => $nominal[$i],
                'kategori' => 3,
                'proyek' => $proyek,
                'kreator' => Session::get('id'),
                'ambil_stok' => 3,
            ]);

            BoronganBayar::create([
                'borongan' => $idborongan,
                'bukukas' => $bukukas->id,
                'tanggal' => date('Y-m-d', strtotime($tanggal[$i])),
                'nominal' => $nominal[$i],
                'kreator' => Session::get('id'),
            ]);
        }

        return redirect('/dashboard/tukang_borongan');
    }

    public function tukang_borongan_update(Request $request)
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
            'proyek' => 'Proyek',
            'nama' => 'Nama',
            'tanggal.*' => 'Tanggal',
            'nominal.*' => 'Nominal',
            'bayartanggal.*' => 'Tanggal',
            'bayarnominal.*' => 'Nominal'
        ];

        $rules = [
            'proyek' => 'required',
            'nama' => 'required',
            'tanggal.*' => 'required',
            'nominal.*' => 'required|numeric|gte:0',
        ];

        if ($request->bayartanggal || $request->bayarnominal) {
            $rules['bayartanggal.*'] = 'required';
            $rules['bayarnominal.*'] = 'required|numeric|gte:0';
        }

        $validator = Validator::make($request->all(), $rules, $message, $attribute);


        if ($validator->fails()) {
            notify()->error('Gagal mengubah transaksi.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $id = $request->input('id');
        $proyek = $request->input('proyek');
        $nama = $request->input('nama');

        Borongan::where('id', $id)->update([
            'proyek' => $proyek,
            'nama' => $nama,
            'kreator' => Session::get('id'),
        ]);

        $dbproyek = Proyek::where('id', $proyek)->first();
        $namaproyek = $dbproyek->nama;

        $tanggal = $request->input('tanggal');
        $nominal = $request->input('nominal');

        if ($tanggal) {
            for ($i = 0; $i < count($tanggal); $i++) {
                $bukukas = Bukukas::create([
                    'tanggal' => date('Y-m-d', strtotime($tanggal[$i])),
                    'keterangan' => 'Pembayaran ke ' . $nama . ' untuk mengerjakan Proyek ' . $namaproyek . '.',
                    'keluar' => $nominal[$i],
                    'kategori' => 3,
                    'proyek' => $proyek,
                    'kreator' => Session::get('id'),
                    'ambil_stok' => 3,
                ]);

                BoronganBayar::create([
                    'borongan' => $id,
                    'bukukas' => $bukukas->id,
                    'tanggal' => date('Y-m-d', strtotime($tanggal[$i])),
                    'nominal' => $nominal[$i],
                    'kreator' => Session::get('id'),
                ]);
            }
        }

        $bayarid = $request->input('bayarid');
        $bayartanggal = $request->input('bayartanggal');
        $bayarnominal = $request->input('bayarnominal');

        if ($bayarid) {
            for ($i = 0; $i < count($bayarid); $i++) {
                $data = BoronganBayar::where('id', $bayarid[$i])->first();
                $idbukukas = $data->bukukas;

                $cek = Bukukas::where('id', $idbukukas)->first();
                if ($cek) {
                    Bukukas::where('id', $idbukukas)->update([
                        'tanggal' => date('Y-m-d', strtotime($bayartanggal[$i])),
                        'keluar' => $bayarnominal[$i],
                        'kategori' => 3,
                        'kreator' => Session::get('id'),
                        'ambil_stok' => 3,
                    ]);
                }

                BoronganBayar::where('id', $bayarid[$i])->update([
                    'tanggal' => date('Y-m-d', strtotime($bayartanggal[$i])),
                    'nominal' => $bayarnominal[$i],
                    'kreator' => Session::get('id'),
                ]);
            }
        }

        $hapusid = $request->input('hapusid');
        if ($hapusid) {
            for ($i = 0; $i < count($hapusid); $i++) {
                $data = BoronganBayar::where('id', $hapusid[$i])->first();
                $idbukukas = $data->bukukas;

                $cek = Bukukas::where('id', $idbukukas)->first();
                if ($cek) {
                    Bukukas::where('id', $idbukukas)->delete();
                }
                BoronganBayar::where('id', $hapusid[$i])->delete();
            }
        }

        return redirect('/dashboard/tukang_borongan');
    }

    public function tukang_borongan_edit($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
          }
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->orderBy('nama', 'asc')->get();
        $data['borongan'] = Borongan::where('id', $id)->first();
        $data['bayar'] = BoronganBayar::where('borongan', $id)->orderBy('tanggal', 'asc')->get();

        return view('dashboard.borongan_edit', $data);
    }

    public function tukang_borongan_hapus($id)
    {
        if (Session::get('role') !== 'owner') {
            notify()->error('Akses dilarang.');
            return back();
          }
        $data = BoronganBayar::where('borongan', $id)->get();
        foreach ($data as $d) {
            $idbukukas = $d->bukukas;

            Bukukas::where('id', $idbukukas)->delete();
        }

        Bukukas::where('id', $idbukukas)->delete();
        Borongan::where('id', $id)->delete();
        BoronganBayar::where('borongan', $id)->delete();

        return redirect('/dashboard/tukang_borongan');
    }

    public function tukang_borongan_cetak($id)
    {
        if (Session::get('role') !== 'owner' || Session::get('role') !== 'admin') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['borongan'] = Borongan::where('borongan.id', $id)->join('proyek', 'borongan.proyek', '=', 'proyek.id')->select('borongan.*', 'proyek.nama as namaproyek')->first();
        $query = BoronganBayar::where('borongan', $id);
        $data['bayar'] = $query->orderBy('tanggal', 'asc')->get();
        $query_t = $query->orderBy('tanggal', 'desc')->first();
        $data['nominal'] = $query_t->nominal;
        $data['total'] = $query->sum('nominal');
        return view('dashboard.borongan_cetak', $data);
    }

    public function tukang_borongan_ekspor($id)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'admin') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $borongan = Borongan::where('borongan.id', $id)->join('proyek', 'borongan.proyek', '=', 'proyek.id')->select('borongan.*', 'proyek.nama as namaproyek')->first();
        $query = BoronganBayar::where('borongan', $id);
        $bayar = $query->orderBy('tanggal', 'asc')->get();
        $query_t = $query->orderBy('tanggal', 'desc')->first();
        $nominal = $query_t->nominal;
        $total = $query->sum('nominal');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('A2', 'Proyek');
        $sheet->setCellValue('A3', 'Jumlah yang diminta');
        $sheet->setCellValue('C1', ':');
        $sheet->setCellValue('C2', ':');
        $sheet->setCellValue('C3', ':');
        $sheet->setCellValue('D1', $borongan->nama);
        $sheet->setCellValue('D2', $borongan->namaproyek);
        $sheet->setCellValue('D3', $nominal);

        $sheet->setCellValue('A5', 'REKAP PEMBAYARAN');
        $sheet->setCellValue('A6', 'No.');
        $sheet->setCellValue('B6', 'Tanggal');
        $sheet->setCellValue('C6', 'Nominal');
        $sheet->mergeCells('C6:D6');

        $x = 7;
        $no = 0;
        foreach ($bayar as $b) :
            $sheet->setCellValue('A' . $x, $no++);
            $tanggal = Carbon::parse($b->tanggal)->locale('id');
            $tanggal->settings(['formatFunction' => 'translatedFormat']);
            $sheet->setCellValue('B' . $x, $tanggal->format('j F Y'));
            $sheet->setCellValue('C' . $x, $b->nominal);

            $sheet->mergeCells('C' . $x . ':D' . $x);
        endforeach;
        for ($no; $no < 16; $no++) :
            $sheet->setCellValue('A' . $x, $no);
            $sheet->mergeCells('C' . $x . ':D' . $x);
            $x++;
        endfor;

        $sheet->mergeCells('A5:D5');

        $sheet->setCellValue('A' . $x, 'TOTAL');
        $sheet->setCellValue('C' . $x, $total);
        $sheet->mergeCells('A' . $x . ':B' . $x);
        $sheet->mergeCells('C' . $x . ':D' . $x);

        $sheet->getColumnDimension('A')->setWidth(35, 'px');
        $sheet->getColumnDimension('B')->setWidth(150, 'px');
        $sheet->getColumnDimension('C')->setWidth(15, 'px');
        $sheet->getColumnDimension('D')->setWidth(150, 'px');

        $sheet->getStyle('A2:C2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $sheet->getStyle('D2')->getAlignment()->setWrapText(true);

        $centerBold = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => 'center',
            ]
        ];
        $center = [
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
        $bold = [
            'font' => [
                'bold' => true,
            ],
        ];

        $sheet->getStyle('A5:C6')->applyFromArray($centerBold);
        $sheet->getStyle('A7:B' . $x - 1)->applyFromArray($center);
        $sheet->getStyle('A' . $x)->applyFromArray($rightBold);

        $sheet->getStyle('C7:C' . $x)->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)');
        $sheet->getStyle('D3')->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)');

        $borderHead = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ],
            ],
        ];
        $borderCel = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $borderCol = [
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ],
            ],
        ];

        $sheet->getStyle('A5:D6')->applyFromArray($borderHead);
        $sheet->getStyle('A' . $x . ':D' . $x)->applyFromArray($borderHead);
        $sheet->getStyle('A7:D' . $x - 1)->applyFromArray($borderCel);

        $sheet->getStyle('A7:A' . $x - 1)->applyFromArray($borderCol);
        $sheet->getStyle('B7:B' . $x - 1)->applyFromArray($borderCol);
        $sheet->getStyle('C7:D' . $x - 1)->applyFromArray($borderCol);

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Tukang Borongan.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function pengaturan_tunjangan()
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['tunjangan'] = Tunjangan::orderBy('jenis', 'asc')->orderBy('nominal', 'asc')->get();
        return view('dashboard.tunjangan', $data);
    }

    public function pengaturan_tunjangan_tambah()
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        return view('dashboard.tunjangan_tambah');
    }

    public function pengaturan_tunjangan_aksi(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
        ];
        $attribute = [
            'level' => 'Level',
            'nominal' => 'Nominal',
        ];
        $validator = Validator::make($request->all(), [
            'level' => 'required',
            'nominal' => 'required|numeric|gte:0',
        ], $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal menambahkan pengaturan.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $jenis = $request->input('jenis');
        $level = $request->input('level');
        $nominal = $request->input('nominal') ?? 0;

        Tunjangan::create([
            'jenis' => $jenis,
            'level' => $level,
            'nominal_baru' => $nominal,
            'approved' => 0,
            'kreator' => Session::get('id'),
        ]);

        return redirect('/dashboard/pengaturan_tunjangan');
    }

    public function pengaturan_tunjangan_edit($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['tunjangan'] = Tunjangan::where('id', $id)->first();

        return view('dashboard.tunjangan_edit', $data);
    }

    public function pengaturan_tunjangan_update(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
        ];
        $attribute = [
            'level' => 'Level',
            'nominal' => 'Nominal',
        ];
        $validator = Validator::make($request->all(), [
            'level' => 'required',
            'nominal' => 'nullable|numeric|gte:0',
        ], $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal menambahkan pengaturan.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $id = $request->input('id');
        $level = $request->input('level');
        $nominal = $request->input('nominal');

        Tunjangan::where('id', $id)->update([
            'level' => $level,
            'kreator' => Session::get('id'),
        ]);

        if ($nominal != null) {
            Tunjangan::where('id', $id)->update([
                'nominal_baru' => $nominal,
                'approved' => 0,
            ]);
        }

        return redirect('/dashboard/pengaturan_tunjangan');
    }

    public function pengaturan_tunjangan_hapus($id)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['tunjangan'] = Tunjangan::where('id', $id)->delete();

        return redirect('/dashboard/pengaturan_tunjangan');
    }

    public function pengaturan_tunjangan_approve($id, $aksi)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        if ($aksi === "yes") {
            $nominal = Tunjangan::where('id', $id)->first();
            Tunjangan::where('id', $id)->update([
                'approved' => 1,
                'approver' => Session::get('id'),
                'nominal_baru' => null,
                'nominal' => $nominal->nominal_baru,
                'tanggal' => date('Y-m-d', strtotime(now()))
            ]);
        } else {
            Tunjangan::where('id', $id)->update([
                'nominal_baru' => null,
            ]);
        }

        return redirect('/dashboard/pengaturan_tunjangan');
    }

    public function tukang_harian()
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data1 = Harian::where('tahun', Session::get('tahun'))->where(function($query){
            if(Session::get('role') === 'supervisor'){
                $query->where('tukang.mandor', '0')
                ->where('tukang.kreator',Session::get('id'));
            }
        })->join('tukang','harian.tukang','=','tukang.id')->select('harian.*','tukang.kreator as spv');
        $data2 = Harian::where('tahun', Session::get('tahun'))->where(function($query){
            if(Session::get('role') === 'supervisor'){
                $query->where('tukang.mandor','!=','0')
                ->where('mandor.supervisor',Session::get('id'));
            }
        })->join('tukang','harian.tukang','=','tukang.id')->join('mandor','tukang.mandor','=','mandor.id')->select('harian.*','mandor.supervisor as spv');
        $data['harian'] = $data1->union($data2)->orderBy('id','desc')->get();

        return view('dashboard.harian', $data);
    }

    public function tukang_harian_tambah()
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['tukang_harian'] = Tukang::where('mandor', '0')->orderBy('nama', 'asc')->get();
        $data['tukang_mandor'] = Tukang::where('mandor', '!=', '0')->join('mandor', 'tukang.mandor', '=', 'mandor.id')->select('tukang.*', 'mandor.nama as namamandor')->orderBy('namamandor', 'asc')->orderBy('nama', 'asc')->get();
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->orderBy('nama', 'asc')->get();
        $data['makan'] = Tunjangan::where('jenis', 'uang_makan')->where('approved', '1')->orderBy('nominal', 'asc')->get();
        $data['transport'] = Tunjangan::where('jenis', 'transport')->where('approved', '1')->orderBy('nominal', 'asc')->get();

        return view('dashboard.harian_tambah', $data);
    }

    public function tukang_harian_aksi(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
        ];
        $attribute = [
            'pokok' => 'Pokok',
            'insentif' => 'Insentif',
            'lembur' => 'Lembur',
            'jam_lembur.*' => 'Jam lembur',
            'proyek.*' => 'Apabila terdapat pekerjaan, Proyek',
            'keterangan.*' => 'Apablia terdapat pekerjaan, Keterangan',
        ];

        $rules = [
            'pokok' => 'required|numeric|gte:0',
            'insentif' => 'nullable|numeric|gte:0',
            'lembur' => 'required|numeric|gte:0',
            'jam_lembur.*' => 'nullable|numeric|gte:0',
        ];

        if (!$request->tukang_harian && !$request->tukang_mandor) {
            $rules['nama'] = 'required';
        };

        $tanggal = $request->input('tanggal');
        $proyek = $request->input('proyek');
        $keterangan = $request->input('keterangan');

        if ($tanggal) {
            for ($i = 0; $i < count($tanggal); $i++) {
                if ($proyek[$i] != '') {
                    $rules['keterangan.' . $i] = 'required';
                    $attribute['keterangan.' . $i] = 'Apablia terdapat pekerjaan, Keterangan';
                }
                if ($keterangan[$i] != '') {
                    $rules['proyek.' . $i] = 'required';
                    $attribute['proyek.' . $i] = 'Apablia terdapat pekerjaan, Proyek';
                }
            }
        }

        $validator = Validator::make($request->all(), $rules, $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $idtukang1 = $request->input('tukang_harian');
        $idtukang2 = $request->input('tukang_mandor');

        if ($idtukang1 || $idtukang2) {
            if ($idtukang1) {
                $idtukang = $idtukang1;
            } else {
                $idtukang = $idtukang2;
            }
            $query = Tukang::where('id', $idtukang)->first();
            $nama = $query->nama;
        } else {
            $nama = $request->input('nama');
            $alamat = $request->input('alamat');
            $hp = $request->input('telp');

            $tukang = Tukang::create([
                'nama' => $nama,
                'alamat' => $alamat,
                'hp' => $hp,
                'mandor' => 0,
                'kreator' => Session::get('id'),
            ]);

            $idtukang = $tukang->id;
        }

        $pokok = $request->input('pokok');
        $insentif = $request->input('insentif');
        $lembur = $request->input('lembur');

        $harian = Harian::create([
            'tahun' => Carbon::parse(now())->year,
            'nama' => $nama,
            'tukang' => $idtukang,
            'pokok' => $pokok,
            'insentif' => $insentif,
            'lembur' => $lembur,
            'kreator' => Session::get('id'),
            'approved' => 0,
            'total' => 0,
        ]);

        $idharian = $harian->id;
        $jam_lembur = $request->input('jam_lembur');
        // $makan = $request->input('uang_makan');
        $makan = 0;
        $transport = $request->input('transport');

        if ($tanggal) {
            for ($i = 0; $i < count($tanggal); $i++) {
                if ($proyek[$i] && $keterangan[$i]) {
                    // $query_makan = Tunjangan::where('id', $makan[$i])->first();
                    $query_transport = Tunjangan::where('id', $transport[$i])->first();
                    // $uang_makan = $query_makan->nominal;
                    $uang_transport = $query_transport->nominal;

                    $total = $pokok + ($lembur * $jam_lembur[$i]) + $uang_transport;

                    HarianBayar::create([
                        'harian' => $idharian,
                        'tanggal' => date('Y-m-d', strtotime($tanggal[$i])),
                        'proyek' => $proyek[$i],
                        'keterangan' => $keterangan[$i],
                        'jam' => $jam_lembur[$i],
                        'makan' => $makan,
                        'uang_makan' => $makan,
                        'transport' => $transport[$i],
                        'uang_transport' => $uang_transport,
                        'total' => $total,
                        'kreator' => Session::get('id'),
                    ]);
                }
            }
        }

        $totalbayar = HarianBayar::where('harian', $idharian)->sum('total');

        Harian::where('id', $idharian)->update([
            'total' => $totalbayar
        ]);

        notify()->success('Transaksi berhasil ditambahkan.');
        return redirect('/dashboard/tukang_harian');
    }

    public function tukang_harian_update(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $idharian = $request->input('id');
        $cek = Harian::where('id', $idharian)->first();
        if ($cek->approved === 1) {
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors([
                'nowork' => 'Transaksi yang sudah disetujui tidak dapat diedit.'
            ])->withInput();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
        ];
        $attribute = [
            'pokok' => 'Pokok',
            'insentif' => 'Insentif',
            'lembur' => 'Lembur',
            'jam_lembur.*' => 'Jam lembur',
            'proyek.*' => 'Apabila terdapat pekerjaan, Proyek',
            'keterangan.*' => 'Apablia terdapat pekerjaan, Keterangan',
        ];

        $rules = [
            'pokok' => 'required|numeric|gte:0',
            'insentif' => 'nullable|numeric|gte:0',
            'lembur' => 'required|numeric|gte:0',
            'jam_lembur.*' => 'nullable|numeric|gte:0',
        ];

        if (!$request->tukang_harian && !$request->tukang_mandor) {
            $rules['nama'] = 'required';
        };

        $tanggal = $request->input('tanggal');
        $proyek = $request->input('proyek');
        $keterangan = $request->input('keterangan');

        if ($tanggal) {
            for ($i = 0; $i < count($tanggal); $i++) {
                if ($proyek[$i] != '') {
                    $rules['keterangan.' . $i] = 'required';
                    $attribute['keterangan.' . $i] = 'Apablia terdapat pekerjaan, Keterangan';
                }
                if ($keterangan[$i] != '') {
                    $rules['proyek.' . $i] = 'required';
                    $attribute['proyek.' . $i] = 'Apablia terdapat pekerjaan, Proyek';
                }
            }
        }

        $validator = Validator::make($request->all(), $rules, $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $idtukang1 = $request->input('tukang_harian');
        $idtukang2 = $request->input('tukang_mandor');

        if ($idtukang1 || $idtukang2) {
            if ($idtukang1) {
                $idtukang = $idtukang1;
            } else {
                $idtukang = $idtukang2;
            }
            $query = Tukang::where('id', $idtukang)->first();
            $nama = $query->nama;
        } else {
            $nama = $request->input('nama');
            $alamat = $request->input('alamat');
            $hp = $request->input('telp');

            $tukang = Tukang::create([
                'nama' => $nama,
                'alamat' => $alamat,
                'hp' => $hp,
                'mandor' => 0,
                'kreator' => Session::get('id'),
            ]);

            $idtukang = $tukang->id;
        }

        $pokok = $request->input('pokok');
        $insentif = $request->input('insentif');
        $lembur = $request->input('lembur');

        Harian::where('id', $idharian)->update([
            'nama' => $nama,
            'tukang' => $idtukang,
            'pokok' => $pokok,
            'insentif' => $insentif,
            'lembur' => $lembur,
            'kreator' => Session::get('id'),
            'approved' => 0,
            'total' => 0,
        ]);

        $bayarid = $request->input('bayarid');
        $jam_lembur = $request->input('jam_lembur');
        // $makan = $request->input('uang_makan');
        $makan = 0;
        $transport = $request->input('transport');

        if ($tanggal) {
            for ($i = 0; $i < count($tanggal); $i++) {
                if ($proyek[$i] && $keterangan[$i]) {
                    // $query_makan = Tunjangan::where('id', $makan[$i])->first();
                    $query_transport = Tunjangan::where('id', $transport[$i])->first();
                    // $uang_makan = $query_makan->nominal;
                    $uang_transport = $query_transport->nominal;

                    $total = $pokok + ($lembur * $jam_lembur[$i]) + $uang_transport;

                    if ($bayarid[$i] != '') {
                        HarianBayar::where('id', $bayarid[$i])->update([
                            'harian' => $idharian,
                            'tanggal' => date('Y-m-d', strtotime($tanggal[$i])),
                            'proyek' => $proyek[$i],
                            'keterangan' => $keterangan[$i],
                            'jam' => $jam_lembur[$i],
                            'makan' => $makan,
                            'uang_makan' => $makan,
                            'transport' => $transport[$i],
                            'uang_transport' => $uang_transport,
                            'total' => $total,
                            'kreator' => Session::get('id'),
                        ]);
                    } else {
                        HarianBayar::create([
                            'harian' => $idharian,
                            'tanggal' => date('Y-m-d', strtotime($tanggal[$i])),
                            'proyek' => $proyek[$i],
                            'keterangan' => $keterangan[$i],
                            'jam' => $jam_lembur[$i],
                            'makan' => $makan,
                            'uang_makan' => $makan,
                            'transport' => $transport[$i],
                            'uang_transport' => $uang_transport,
                            'total' => $total,
                            'kreator' => Session::get('id'),
                        ]);
                    }
                }
            }
        }

        $totalbayar = HarianBayar::where('harian', $idharian)->sum('total');

        Harian::where('id', $idharian)->update([
            'total' => $totalbayar
        ]);

        notify()->success('Transaksi berhasil ditambahkan.');
        return redirect('/dashboard/tukang_harian');
    }

    public function tukang_harian_edit($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['tukang_harian'] = Tukang::where('mandor', '0')->orderBy('nama', 'asc')->get();
        $data['tukang_mandor'] = Tukang::where('mandor', '!=', '0')->join('mandor', 'tukang.mandor', '=', 'mandor.id')->select('tukang.*', 'mandor.nama as namamandor')->orderBy('namamandor', 'asc')->orderBy('nama', 'asc')->get();
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->orderBy('nama', 'asc')->get();
        $data['makan'] = Tunjangan::where('jenis', 'uang_makan')->where('approved', '1')->orderBy('nominal', 'asc')->get();
        $data['transport'] = Tunjangan::where('jenis', 'transport')->where('approved', '1')->orderBy('nominal', 'asc')->get();
        $data['harian'] = Harian::where('id', $id)->first();

        return view('dashboard.harian_edit', $data);
    }

    public function tukang_harian_hapus($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $cek = Harian::where('id', $id)->first();
        if ($cek->approved === 1) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data = HarianBayar::where('harian', $id)->get();
        foreach ($data as $d) {
            $idbukukas = $d->bukukas;

            Bukukas::where('id', $idbukukas)->delete();
        }

        Harian::where('id', $id)->delete();
        HarianBayar::where('harian', $id)->delete();

        return redirect('/dashboard/tukang_harian');
    }

    public function tukang_harian_approve($id)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $harian = Harian::where('id', $id)->first();
        $bayar = HarianBayar::where('harian', $id)->join('proyek', 'harian_bayar.proyek', '=', 'proyek.id')->select('harian_bayar.*', 'proyek.nama as namaproyek')->orderBy('tanggal', 'asc')->get();
        foreach ($bayar as $b) {
            $bukukas = Bukukas::create([
                'tanggal' => $b->tanggal,
                'keterangan' => 'Pembayaran ke ' . $harian->nama . ' untuk mengerjakan Proyek ' . $b->namaproyek . '.',
                'keluar' => $b->total,
                'kategori' => 3,
                'proyek' => $b->proyek,
                'kreator' => Session::get('id'),
                'ambil_stok' => 3,
            ]);

            $idbukukas = $bukukas->id;

            HarianBayar::where('id', $b->id)->update([
                'bukukas' => $idbukukas
            ]);
        }

        Harian::where('id', $id)->update([
            'approved' => 1,
            'approver' => Session::get('id'),
        ]);

        return redirect('/dashboard/tukang_harian');
    }

    public function tukang_harian_disapprove($id)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $bayar = HarianBayar::where('harian', $id)->orderBy('tanggal', 'asc')->get();
        foreach ($bayar as $b) {
            Bukukas::where('id', $b->bukukas)->delete();

            HarianBayar::where('id', $b->id)->update([
                'bukukas' => null
            ]);
        }

        Harian::where('id', $id)->update([
            'approved' => 0,
            'approver' => null,
        ]);

        return back();
    }

    public function tukang_harian_cetak($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['harian'] = Harian::where('id', $id)->first();

        return view('dashboard.harian_cetak', $data);
    }

    public function tukang_harian_ekspor($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $cek = Harian::where('id', $id)->first();
        if ($cek->approved !== 1) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $harian = Harian::where('id', $id)->first();
        $query = DB::table('harian_bayar')->where('harian', $harian->id)->orderBy('tanggal', 'desc')->first();
        $query_b = DB::table('harian_bayar')->where('harian', $harian->id)->join('proyek', 'harian_bayar.proyek', '=', 'proyek.id')->select('harian_bayar.*', 'proyek.nama')->orderBy('tanggal', 'asc');
        $bayar = $query_b->get();
        $hr = $query_b->count();
        $total_jam = $query_b->sum('jam');
        $total_transport = $query_b->sum('uang_transport');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $query = DB::table('harian_bayar')->where('harian', $harian->id)->orderBy('tanggal', 'desc')->first();
        $tanggalacuan = $query->tanggal;
        $subnum = Carbon::parse($tanggalacuan)->getDaysFromStartOfWeek();
        $date = Carbon::parse($tanggalacuan)->subDays($subnum);

        $tanggal = Carbon::parse(Carbon::parse($date))->locale('id');
        $tanggal->settings(['formatFunction' => 'translatedFormat']);
        $tanggal2 = Carbon::parse(Carbon::parse(date('Y-m-d', strtotime(Carbon::parse($date)->addDays(6)))))->locale('id');
        $tanggal2->settings(['formatFunction' => 'translatedFormat']);

        $sheet->setCellValue('A1', 'Nama : ' . $harian->nama);
        $sheet->setCellValue('A2', 'Pokok : Rp ' . number_format($harian->pokok));
        $sheet->setCellValue('C2', 'Insentif : Rp ' . number_format($harian->insentif));
        $sheet->setCellValue('D1', 'Tanggal : ' . $tanggal->format('j M') . ' - ' . $tanggal2->format('j M Y'));
        $sheet->setCellValue('D2', 'Lembur / jam : Rp ' . number_format($harian->lembur));
        $sheet->setCellValue('A3', 'Hari');
        $sheet->setCellValue('B3', 'Proyek');
        $sheet->setCellValue('C3', 'Keterangan Pekerjaan');
        $sheet->setCellValue('D3', 'Hr');
        $sheet->setCellValue('E3', 'Jam');
        $sheet->setCellValue('F3', 'Transport');
        $sheet->setCellValue('G3', 'Total');

        $query_b = DB::table('harian_bayar')->where('harian', $harian->id)->join('proyek', 'harian_bayar.proyek', '=', 'proyek.id')->select('harian_bayar.*', 'proyek.nama')->orderBy('tanggal', 'asc');
        $bayar = $query_b->get();
        $hr = $query_b->count();
        $total_jam = $query_b->sum('jam');
        $total_transport = $query_b->sum('uang_transport');
        $total = $query_b->sum('total');


        $x = 4;
        $i = 0;
        for ($i; $i < 7; $i++) :
            $tanggal = Carbon::parse(Carbon::parse($date))->locale('id');
            $tanggal->settings(['formatFunction' => 'translatedFormat']);
            $sheet->setCellValue('A' . $x, $tanggal->format('l, j M'));
            $sheet->setCellValue('B' . $x, $bayar[$i]->nama);
            $sheet->setCellValue('C' . $x, $bayar[$i]->keterangan);
            $sheet->setCellValue('D' . $x, '1');
            $sheet->setCellValue('E' . $x, $bayar[$i]->jam == 0 ? '' : $bayar[$i]->jam);
            $sheet->setCellValue('F' . $x, $bayar[$i]->uang_transport == 0 ? '' : $bayar[$i]->uang_transport);
            $sheet->setCellValue('G' . $x, $bayar[$i]->total);
            $x++;
        endfor;
        $sheet->setCellValue('A' . $x, 'Jumlah');
        $sheet->setCellValue('D' . $x, $hr);
        $sheet->setCellValue('E' . $x, $total_jam == 0 ? '' : $total_jam);
        $sheet->setCellValue('F' . $x, $total_transport == 0 ? '' : $total_transport);
        $sheet->setCellValue('G' . $x, $total);

        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('D1:G1');
        $sheet->mergeCells('D2:G2');

        $sheet->getColumnDimension('A')->setWidth(150, 'px');
        $sheet->getColumnDimension('B')->setWidth(150, 'px');
        $sheet->getColumnDimension('C')->setWidth(300, 'px');
        $sheet->getColumnDimension('D')->setWidth(40, 'px');
        $sheet->getColumnDimension('E')->setWidth(40, 'px');
        $sheet->getColumnDimension('F')->setWidth(90, 'px');
        $sheet->getColumnDimension('G')->setWidth(100, 'px');

        $center = [
            'alignment' => [
                'horizontal' => 'center',
            ]
        ];

        $sheet->getStyle('A3:G3')->applyFromArray($center);

        $sheet->getStyle('G4:G' . $x)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');

        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A1:G' . $x)->applyFromArray($border);

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Tukang Harian.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function tukang_mandor()
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['mandor'] = Mandor::orderBy('nama', 'asc')->get();
        $data['proyek'] = MandorProyek::where('tahun', Session::get('tahun'))->where(function($query){
            if(Session::get('role') === 'supervisor'){
                $query->where('mandor.supervisor', Session::get('id'));
            }
        })->join('mandor','mandor_proyek.mandor','=','mandor.id')->orderBy('tanggal', 'desc')->get();

        return view('dashboard.tukangmandor', $data);
    }

    public function tukang_mandor_tambah($id, $date, $ot = null)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        // $tukang = Tukang::where('mandor', $id)->orderBy('nama', 'asc')->get();
        $data['mandor'] = $id;
        $data['tanggal'] = $date;

        if ($ot) {
            $lain = explode('-', $ot);
            $tukang = Tukang::whereIn('id', $lain)->orderBy('nama', 'asc');
            $data['tukang'] = Tukang::where('mandor', $id)->orderBy('nama', 'asc')->union($tukang)->get();
        } else {
            $data['tukang'] = Tukang::where('mandor', $id)->orderBy('nama', 'asc')->get();
        }

        return view('dashboard.tukangmandor_tambah', $data);
    }

    public function tukang_mandor_tambah_b($id, $date)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['tukang'] = Tukang::where('mandor', '!=', $id)
        ->where(function($query){
            if(Session::get('role') === 'supervisor'){
                $query_m = Mandor::where('supervisor',Session::get('id'))->get();
                $mandor = [];
                foreach($query_m as $q){
                    $mandor[] = $q->id;
                }
                $query->whereIn('mandor',$mandor)->orWhere('kreator',Session::get('id'));
            }
        })
        ->orderBy('mandor', 'asc')->orderBy('nama', 'asc')->get();
        $data['mandor'] = $id;
        $data['tanggal'] = $date;

        return view('dashboard.tukangmandor_lain', $data);
    }

    public function tukang_mandor_tambah_c($id, $date)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $mandor = MandorProyek::where('id', $id)->first();
        if ($mandor->approved === 1) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $tukang = MandorTukang::where('mandor_proyek', $id)->get();
        $lain = [];
        foreach ($tukang as $t) {
            $lain[] = $t->tukang;
        }
        $data['tukang'] = Tukang::whereNotIn('id', $lain)->where(function($query){
            if(Session::get('role') === 'supervisor'){
                $query_m = Mandor::where('supervisor',Session::get('id'))->get();
                $mandor = [];
                foreach($query_m as $q){
                    $mandor[] = $q->id;
                }
                $query->whereIn('mandor',$mandor)->orWhere('kreator',Session::get('id'));
            }
        })->orderBy('mandor', 'asc')->orderBy('nama', 'asc')->get();
        $data['proyek'] = $id;
        $data['mandor'] = $mandor->mandor;
        $data['tanggal'] = $date;

        return view('dashboard.tukangmandor_edit', $data);
    }

    public function tukang_mandor_before(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $id = $request->input('mandor');
        $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
        return redirect('/dashboard/tukang_mandor_tambah/' . $id . '/' . $tanggal);
    }

    public function tukang_mandor_aksi_a(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $tukang = $request->input('tukang');
        $mandor = $request->input('mandor');
        $tanggal = $request->input('tanggal');

        if (!$tukang) {
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors([
                'nowork' => 'Masukkan minimal 1 tukang.'
            ])->withInput();
        }

        $query = Mandor::where('id', $mandor)->first();
        $nama = $query->nama;
        $mandorproyek = MandorProyek::create([
            'tahun' => Carbon::parse(now())->year,
            'tanggal' => date('Y-m-d', strtotime($tanggal)),
            'nama' => $nama,
            'mandor' => $mandor,
            'approved' => 0,
            'kreator' => Session::get('id'),
        ]);

        $id = $mandorproyek->id;

        foreach ($tukang as $t) {
            $namatukang = Tukang::where('id', $t)->first();
            MandorTukang::create([
                'mandor_proyek' => $id,
                'tukang' => $t,
                'nama' => $namatukang->nama,
                'kreator' => Session::get('id'),
            ]);
        }

        return redirect('/dashboard/tukang_mandor_daftar_a/' . $id);
    }

    public function tukang_mandor_aksi_b(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $tukang = $request->input('tukang');
        $mandor = $request->input('mandor');
        $tanggal = $request->input('tanggal');

        if ($tukang) {
            $ot = implode('-', $tukang);
        } else {
            $ot = null;
        }

        return redirect('/dashboard/tukang_mandor_tambah/' . $mandor . '/' . $tanggal . '/' . $ot);
    }

    public function tukang_mandor_aksi_c(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $tukang = $request->input('tukang');
        $id = $request->input('proyek');

        $mandor = MandorProyek::where('id', $id)->first();
        if ($mandor->approved === 1) {
            notify()->error('Akses dilarang.');
            return back();
        }

        if ($tukang) {
            foreach ($tukang as $t) {
                $namatukang = Tukang::where('id', $t)->first();

                MandorTukang::create([
                    'mandor_proyek' => $id,
                    'tukang' => $t,
                    'nama' => $namatukang->nama,
                    'kreator' => Session::get('id'),
                ]);
            }
        }

        return redirect('/dashboard/tukang_mandor_edit/' . $id);
    }

    public function tukang_mandor_daftar_a($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['mandor'] = MandorProyek::where('id', $id)->first();
        $data['tukang'] = MandorTukang::where('mandor_proyek', $id)->join('tukang', 'mandor_tukang.tukang', '=', 'tukang.id')->select('mandor_tukang.*', 'tukang.nama as namatukang', 'tukang.alamat', 'tukang.hp')->orderBy('nama', 'asc')->get();

        return view('dashboard.tukangmandor_daftar', $data);
    }

    public function tukang_mandor_daftar_b($id, $ic)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['mandor'] = MandorProyek::where('id', $id)->first();
        $data['gaji'] = GajiMandor::where('mandor', $data['mandor']->mandor)->orderBy('pokok', 'asc')->get();
        $data['tukang'] = MandorTukang::where('mandor_tukang.id', $ic)->join('tukang', 'mandor_tukang.tukang', '=', 'tukang.id')->select('mandor_tukang.*', 'tukang.nama as namatukang')->orderBy('nama', 'asc')->first();
        $data['proyek'] = Proyek::where('tahun', Session::get('tahun'))->orderBy('nama', 'asc')->get();
        $data['makan'] = Tunjangan::where('jenis', 'uang_makan')->where('approved', '1')->orderBy('nominal', 'asc')->get();
        $data['transport'] = Tunjangan::where('jenis', 'transport')->where('approved', '1')->orderBy('nominal', 'asc')->get();

        return view('dashboard.tukangmandor_tukang', $data);
    }

    public function tukang_mandor_aksi(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
        ];

        $rules = [];
        $attribute = [];

        $tanggal = $request->input('tanggal');
        $proyek = $request->input('proyek');
        $bayarid = $request->input('bayarid');
        $mandorid = $request->input('mandorid');

        $mandor = MandorProyek::where('id', $mandorid)->first();
        if ($mandor->approved === 1) {
            notify()->error('Akses dilarang.');
            return back();
        }

        if (count($tanggal) > 1) {
            for ($i = 1; $i < count($tanggal); $i++) {
                if ($proyek[$i] != '') {
                    $rules['jam_lembur.' . $i] = 'nullable|numeric|gte:0';
                    $attribute['jam_lembur.' . $i] = 'Jam lembur';
                }

                $cek = GajiMandorTukang::where('mandor_tukang', $mandorid)->where('tanggal', $tanggal[$i])->where('proyek', $proyek[$i])->first();

                if ($cek) {
                    notify()->error('Gagal menambahkan transaksi.');
                    return Redirect::back()->withErrors([
                        'nowork' => 'Tidak boleh 1 tukang mengerjakan proyek yang sama di tanggal yang sama.'
                    ])->withInput();
                }
            }

            $cek = Helper::validasiDuaArray($tanggal, $proyek);

            if (!$cek) {
                notify()->error('Gagal menambahkan transaksi.');
                return Redirect::back()->withErrors([
                    'nowork' => 'Tidak boleh 1 tukang mengerjakan proyek yang sama di tanggal yang sama.'
                ])->withInput();
            }
        }

        if (!$bayarid && count($tanggal) === 1) {
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors([
                'nowork' => 'Harus ada minimal 1 pekerjaan untuk 1 tukang.'
            ])->withInput();
        }

        $validator = Validator::make($request->all(), $rules, $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal menambahkan transaksi.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $proyekid = $request->input('proyekid');
        $gaji = $request->input('gaji_mandor');
        $jam_lembur = $request->input('jam_lembur');
        $makan = $request->input('uang_makan');
        $transport = $request->input('transport');

        // $query_mt = MandorTukang::where('id', $mandorid)->first();
        // $tukang = $query_mt->tukang;
        // $query_t = Tukang::where('id', $tukang)->first();
        $gaji_mandor = GajiMandor::where('id', $gaji)->first();

        if (count($tanggal) > 1 && $proyek) {
            for ($i = 1; $i < count($tanggal); $i++) {
                if ($proyek[$i] != '') {
                    $uang_pokok = $gaji_mandor->pokok;
                    $uang_lembur = $gaji_mandor->lembur;
                    $tunjangan_m = Tunjangan::where('id', $makan[$i])->first();
                    $tunjangan_t = Tunjangan::where('id', $transport[$i])->first();
                    $uang_makan = $tunjangan_m->nominal;
                    $uang_transport = $tunjangan_t->nominal;
                    $total = $uang_pokok + ($jam_lembur[$i] * $uang_lembur) + $uang_makan + $uang_transport;

                    GajiMandorTukang::create([
                        'tanggal' => date('Y-m-d', strtotime($tanggal[$i])),
                        // 'nama' => $query_t->nama,
                        'mandor_tukang' => $mandorid,
                        'proyek' => $proyek[$i],
                        'gaji_mandor' => $gaji,
                        'uang_pokok' => $uang_pokok,
                        'jam_lembur' => $jam_lembur[$i],
                        'uang_lembur' => $uang_lembur,
                        'makan' => $makan[$i],
                        'uang_makan' => $uang_makan,
                        'transport' => $transport[$i],
                        'uang_transport' => $uang_transport,
                        'total' => $total,
                        'kreator' => Session::get('id'),
                    ]);
                }
            }
        }

        $bayartanggal = $request->input('bayartanggal');
        $bayarproyek = $request->input('bayarproyek');
        $bayarjam_lembur = $request->input('bayarjam_lembur');
        $bayarmakan = $request->input('bayaruang_makan');
        $bayartransport = $request->input('bayartransport');

        if ($bayarid) {
            for ($i = 0; $i < count($bayarid); $i++) {
                $uang_pokok = $gaji_mandor->pokok;
                $uang_lembur = $gaji_mandor->lembur;
                $tunjangan_m = Tunjangan::where('id', $bayarmakan[$i])->first();
                $tunjangan_t = Tunjangan::where('id', $bayartransport[$i])->first();
                $uang_makan = $tunjangan_m->nominal;
                $uang_transport = $tunjangan_t->nominal;
                $total = $uang_pokok + ($bayarjam_lembur[$i] * $uang_lembur) + $uang_makan + $uang_transport;

                GajiMandorTukang::where('id', $bayarid[$i])->update([
                    'tanggal' => date('Y-m-d', strtotime($bayartanggal[$i])),
                    // 'nama' => $query_t->nama,
                    'mandor_tukang' => $mandorid,
                    'proyek' => $bayarproyek[$i],
                    'gaji_mandor' => $gaji,
                    'uang_pokok' => $uang_pokok,
                    'jam_lembur' => $bayarjam_lembur[$i],
                    'uang_lembur' => $uang_lembur,
                    'makan' => $bayarmakan[$i],
                    'uang_makan' => $uang_makan,
                    'transport' => $bayartransport[$i],
                    'uang_transport' => $uang_transport,
                    'total' => $total,
                    'kreator' => Session::get('id'),
                ]);
            }
        }

        $hapusid = $request->input('hapusid');

        if ($hapusid) {
            foreach ($hapusid as $h) {
                GajiMandorTukang::where('id', $h)->delete();
            }
        }

        notify()->success('Transaksi berhasil ditambahkan.');
        return redirect('/dashboard/tukang_mandor_daftar_a/' . $proyekid);
    }

    public function tukang_mandor_edit($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['mandor'] = MandorProyek::where('id', $id)->first();
        $data['tukang'] = MandorTukang::where('mandor_proyek', $id)->join('tukang', 'mandor_tukang.tukang', '=', 'tukang.id')->select('mandor_tukang.*', 'tukang.nama as namatukang', 'tukang.alamat', 'tukang.hp')->orderBy('nama', 'asc')->get();

        return view('dashboard.tukangmandor_daftar', $data);
    }

    public function tukang_mandor_hapus_a($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $mandor = MandorTukang::where('id', $id)->first();

        if ($mandor->approved === 1) {
            notify()->error('Akses dilarang.');
            return back();
        }

        $proyek = MandorProyek::where('id', $mandor->mandor_proyek)->first();
        MandorTukang::where('id', $id)->delete();
        GajiMandorTukang::where('mandor_tukang', $id)->delete();

        return redirect('/dashboard/tukang_mandor_edit/' . $proyek->id);
    }

    public function tukang_mandor_hapus($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $mandor = MandorProyek::where('id', $id)->first();
        if ($mandor->approved === 1) {
            notify()->error('Akses dilarang.');
            return back();
        }

        $idgaji = MandorTukang::where('mandor_proyek', $id)->get();

        MandorProyek::where('id', $id)->delete();
        MandorTukang::where('mandor_proyek', $id)->delete();
        foreach ($idgaji as $i) {
            GajiMandorTukang::where('mandor_tukang', $i)->delete();
        }

        return redirect('/dashboard/tukang_mandor');
    }

    public function tukang_mandor_approve($id)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $idgaji = MandorTukang::where('mandor_proyek', $id)->get();
        foreach ($idgaji as $i) {
            $gaji = GajiMandorTukang::where('mandor_tukang', $i->id)->join('proyek', 'gaji_mandor_tukang.proyek', '=', 'proyek.id')->select('gaji_mandor_tukang.*', 'proyek.nama as namaproyek')->get();
            foreach ($gaji as $g) {
                $bukukas = Bukukas::create([
                    'tanggal' => $g->tanggal,
                    'keterangan' => 'Pembayaran ke ' . $g->nama . ' untuk mengerjakan Proyek ' . $g->namaproyek . '.',
                    'keluar' => $g->total,
                    'kategori' => 3,
                    'proyek' => $g->proyek,
                    'kreator' => Session::get('id'),
                    'ambil_stok' => 3,
                ]);

                $idbukukas = $bukukas->id;

                GajiMandorTukang::where('id', $g->id)->update([
                    'bukukas' => $idbukukas
                ]);
            }
        }

        MandorProyek::where('id', $id)->update([
            'approved' => 1,
            'approver' => Session::get('id'),
        ]);

        return redirect('/dashboard/tukang_mandor');
    }

    public function tukang_mandor_disapprove($id)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $idgaji = MandorTukang::where('mandor_proyek', $id)->get();
        foreach ($idgaji as $i) {
            $gaji = GajiMandorTukang::where('mandor_tukang', $i->id)->join('proyek', 'gaji_mandor_tukang.proyek', '=', 'proyek.id')->select('gaji_mandor_tukang.*', 'proyek.nama as namaproyek')->get();
            foreach ($gaji as $g) {
                Bukukas::where('id', $g->bukukas)->delete();

                GajiMandorTukang::where('id', $g->id)->update([
                    'bukukas' => null
                ]);
            }
        }

        MandorProyek::where('id', $id)->update([
            'approved' => 0,
            'approver' => null,
        ]);

        return back();
    }

    public function tukang_mandor_cetak($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['mandor'] = MandorProyek::where('id', $id)->first();
        $data['tukang'] = MandorTukang::where('mandor_proyek', $id)->get();
        $tukang = [];
        foreach ($data['tukang'] as $d) {
            $tukang[] = $d->id;
        }

        $proyek = GajiMandorTukang::whereIn('mandor_tukang', $tukang)->orderBy('proyek', 'asc')->get();
        $a = [];
        $b = null;
        foreach ($proyek as $p) {
            if ($b !== $p->proyek) {
                $a[] = $p->proyek;
            }
            $b = $p->proyek;
        }
        $data['xyzproyek'] = $a;

        return view('dashboard.tukangmandor_cetak', $data);
    }

    public function tukang_mandor_ekspor($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $cek = MandorProyek::where('id', $id)->first();
        if ($cek->approved !== 1) {
            notify()->error('Akses dilarang.');
            return back();
        }
        $mandor = MandorProyek::where('id', $id)->first();
        $tukang = MandorTukang::where('mandor_proyek', $id)->get();
        $tukangx = [];
        foreach ($tukang as $t) {
            $tukangx[] = $t->id;
        }

        $proyek = GajiMandorTukang::whereIn('mandor_tukang', $tukangx)->orderBy('proyek', 'asc')->get();
        $a = [];
        $b = null;
        foreach ($proyek as $p) {
            if ($b !== $p->proyek) {
                $a[] = $p->proyek;
            }
            $b = $p->proyek;
        }
        $xyzproyek = $a;

        $tanggalacuan = $mandor->tanggal;
        $subnum = Carbon::parse($tanggalacuan)->getDaysFromStartOfWeek();
        $tanggal = Carbon::parse($tanggalacuan)->subDays($subnum);
        $tanggal1 = Carbon::parse(Carbon::parse($tanggal)->addDays(0))->locale('id');
        $tanggal1->settings(['formatFunction' => 'translatedFormat']);
        $tanggal2 = Carbon::parse(Carbon::parse($tanggal)->addDays(6))->locale('id');
        $tanggal2->settings(['formatFunction' => 'translatedFormat']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if (count($xyzproyek) > 1) :
            $sheet->setCellValue('A1', 'Mandor : ' . ucwords($mandor->nama) . ' (' . $tanggal1->format('j F') . ' - ' . $tanggal2->format('j F Y') . ')');
        else :
            $query_px = DB::table('proyek')->where('id', $xyzproyek[0])->first();
            $sheet->setCellValue('A1', 'Mandor : ' . ucwords($mandor->nama) . ' (' . $query_px->nama . $tanggal1->format('j F') . ' - ' . $tanggal2->format('j F Y') . ')');
        endif;
        $sheet->setCellValue('A3', 'Daftar Gaji Karyawan');
        $sheet->setCellValue('A4', 'Minggu');
        $sheet->setCellValue('D4', 'Senin');
        $sheet->setCellValue('G4', 'Selasa');
        $sheet->setCellValue('J4', 'Rabu');
        $sheet->setCellValue('M4', 'Kamis');
        $sheet->setCellValue('P4', 'Jumat');
        $sheet->setCellValue('S4', 'Sabtu');
        for ($x = 0; $x < 21; $x++) :
            $sheet->setCellValue(Helper::toHuruf($x++) . '5', 'hr');
            $sheet->setCellValue(Helper::toHuruf($x++) . '5', 'jam');
            $sheet->setCellValue(Helper::toHuruf($x) . '5', 'UM');
        endfor;
        $sheet->setCellValue('V4', 'Jumlah');
        $sheet->setCellValue('Z4', 'Gaji');
        $sheet->setCellValue('AB4', 'UM');
        $sheet->setCellValue('AC4', 'Transport');
        $sheet->setCellValue('AD4', 'jumlah');
        $sheet->setCellValue('AE4', 'jumlah per Orang');

        $sheet->setCellValue('V5', 'hr');
        $sheet->setCellValue('W5', 'jam');
        $sheet->setCellValue('X5', 'Tr');
        $sheet->setCellValue('Y5', 'UM');
        $sheet->setCellValue('Z5', 'hr');
        $sheet->setCellValue('AA5', 'jam');

        $tanggalacuan = $mandor->tanggal;
        $subnum = Carbon::parse($tanggalacuan)->getDaysFromStartOfWeek();
        $tanggal = Carbon::parse($tanggalacuan)->subDays($subnum);
        $f = 1;
        $c = 6;
        foreach ($tukang as $t) :
            $sheet->setCellValue('A' . $c, $f++);
            $sheet->setCellValue('B' . $c, ucwords($t->nama));
            $sheet->mergeCells('B' . $c . ':AC' . $c);
            $proyek = DB::table('gaji_mandor_tukang')->where('mandor_tukang', $t->id)->orderBy('proyek', 'asc')->get();
            $abcproyek = [];
            $varp = null;
            foreach ($proyek as $p) :
                if ($p->proyek !== $varp) :
                    $abcproyek[] = $p->proyek;
                endif;
                $varp = $p->proyek;
            endforeach;
            $c++;
            foreach ($abcproyek as $a) :
                $query = DB::table('gaji_mandor_tukang')->where('mandor_tukang', $t->id)->where('proyek', $a)->orderBy('tanggal', 'asc');
                $bayar = $query->get();
                $jam = $query->sum('jam_lembur');
                $hr = $query->count();
                $tr = $query->where('uang_transport', '!=', '0')->count();
                $mk = $query->where('uang_makan', '!=', '0')->count();
                if (count($bayar) > 0) :
                    $i = 0;
                    $d = 0;
                    for ($x = 0; $x < 21; $x++) :
                        $cektanggal = date('Y-m-d', strtotime(Carbon::parse($tanggal)->addDays($d)));
                        if ($cektanggal == $bayar[$i]->tanggal) :
                            $transport = DB::table('setting_tunjangan')->where('id', $bayar[$i]->transport)->first();
                            $sheet->setCellValue(Helper::toHuruf($x++) . $c, Helper::toHuruf(array_search($bayar[$i]->proyek, $xyzproyek)) . $transport->level);
                            $sheet->setCellValue(Helper::toHuruf($x++) . $c, $bayar[$i]->jam_lembur);
                            $makan = DB::table('setting_tunjangan')->where('id', $bayar[$i]->makan)->first();
                            $sheet->setCellValue(Helper::toHuruf($x) . $c, $makan->level != 0 ? $makan->level : '');
                        else :
                            $x += 2;
                        endif;
                        if ($cektanggal == $bayar[$i]->tanggal && $i < count($bayar) - 1) :
                            $i++;
                        endif;
                        $d++;
                    endfor;
                    $sheet->setCellValue(Helper::toHuruf($x++) . $c, $hr);
                    $sheet->setCellValue(Helper::toHuruf($x++) . $c, $jam !== 0 ? $jam : '');
                    $sheet->setCellValue(Helper::toHuruf($x++) . $c, $tr !== 0 ? $tr : '');
                    $sheet->setCellValue(Helper::toHuruf($x++) . $c, $mk !== 0 ? $mk : '');
                    $sheet->setCellValue(Helper::toHuruf($x++) . $c, $bayar[0]->uang_pokok);
                    $sheet->setCellValue(Helper::toHuruf($x++) . $c, $bayar[0]->uang_lembur);
                    if ($mk !== 0) :
                        $query_mx = DB::table('gaji_mandor_tukang')->where('mandor_tukang', $t->id)->where('proyek', $a)->where('uang_makan', '!=', '0')->first();
                        $uang_makan = $query_mx->uang_makan;
                        $sheet->setCellValue(Helper::toHuruf($x++) . $c, $uang_makan);
                    else :
                        $uang_makan = 0;
                        $x++;
                    endif;
                    if ($tr !== 0) :
                        $query_tx = DB::table('gaji_mandor_tukang')->where('mandor_tukang', $t->id)->where('proyek', $a)->where('uang_transport', '!=', '0')->first();
                        $uang_transport = $query_tx->uang_transport;
                        $sheet->setCellValue(Helper::toHuruf($x++) . $c, $uang_transport);
                    else :
                        $uang_transport = 0;
                        $x++;
                    endif;
                    $tt = ($hr * $bayar[0]->uang_pokok) + ($bayar[0]->uang_lembur * $jam) + ($uang_makan * $mk) + ($uang_transport * $tr);
                    $sheet->setCellValue(Helper::toHuruf($x++) . $c, $tt);
                endif;
                $c++;
            endforeach;
            $total = DB::table('gaji_mandor_tukang')->where('mandor_tukang', $t->id)->sum('total');
            $sheet->setCellValue(Helper::toHuruf($x++) . $c, $total);
            $c++;
        endforeach;

        $e = $c - 1;
        if (count($xyzproyek) > 1) :
            for ($x = 0; $x < count($xyzproyek); $x++) :
                if ($x % 3 == 0) {
                    $g = 0;
                    $e++;
                } elseif ($x % 3 == 1) {
                    $g = 12;
                } else {
                    $g = 24;
                }
                $sheet->setCellValue(Helper::toHuruf($g) . $e, Helper::toHuruf($x));
                $query_p = DB::table('proyek')->where('id', $xyzproyek[$x])->first();
                $sheet->setCellValue(Helper::toHuruf($g + 1) . $e, $query_p->nama);
            endfor;
        endif;


        $sheet->mergeCells('A4:C4');
        $sheet->mergeCells('D4:F4');
        $sheet->mergeCells('G4:I4');
        $sheet->mergeCells('J4:L4');
        $sheet->mergeCells('M4:O4');
        $sheet->mergeCells('P4:R4');
        $sheet->mergeCells('S4:U4');
        $sheet->mergeCells('V4:Y4');
        $sheet->mergeCells('Z4:AA4');
        $sheet->mergeCells('AB4:AB5');
        $sheet->mergeCells('AC4:AC5');
        $sheet->mergeCells('AD4:AD5');
        $sheet->mergeCells('AE4:AE5');

        for ($v = 0; $v < 25; $v++) :
            $sheet->getColumnDimension(Helper::toHuruf($v))->setWidth(35, 'px');
        endfor;
        for ($v = 25; $v < 31; $v++) :
            $sheet->getColumnDimension(Helper::toHuruf($v))->setWidth(80, 'px');
        endfor;

        $sheet->getStyle('AE4')->getAlignment()->setWrapText(true);

        $center = [
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ]
        ];

        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A4:AE' . $c - 1)->applyFromArray($border);
        $sheet->getStyle('AB4:AE4')->applyFromArray($center);

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Tukang Mandor.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function daftar_mandor()
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['mandor'] = Mandor::where(function($query){
            if (Session::get('role') === 'supervisor'){
                $query->where('supervisor',Session::get('id'));
            }
        })->join('users', 'mandor.supervisor', '=', 'users.id')->select('mandor.*', 'users.username')->orderBy('nama', 'asc')->get();
        return view('dashboard.mandor', $data);
    }

    public function daftar_mandor_tambah()
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['user'] = User::where('role', 'supervisor')->orderBy('username', 'asc')->get();

        return view('dashboard.mandor_tambah', $data);
    }

    public function daftar_mandor_aksi(Request $request)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'nama' => 'Nama',
        ];

        $rules = [
            'nama' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $message, $attribute);


        if ($validator->fails()) {
            notify()->error('Gagal menambahkan mandor.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $nama = $request->input('nama');
        $alamat = $request->input('alamat');
        $hp = $request->input('telp');
        $supervisor = $request->input('supervisor');

        Mandor::create([
            'nama' => $nama,
            'alamat' => $alamat,
            'hp' => $hp,
            'supervisor' => $supervisor,
            'kreator' => Session::get('id'),
        ]);

        notify()->success('Mandor berhasil ditambahkan.');
        return redirect('/dashboard/daftar_mandor');
    }

    public function daftar_mandor_update(Request $request)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'nama' => 'Nama',
        ];

        $rules = [
            'nama' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $message, $attribute);


        if ($validator->fails()) {
            notify()->error('Gagal menambahkan mandor.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $id = $request->input('id');
        $nama = $request->input('nama');
        $alamat = $request->input('alamat');
        $hp = $request->input('telp');
        $supervisor = $request->input('supervisor');

        Mandor::where('id', $id)->update([
            'nama' => $nama,
            'alamat' => $alamat,
            'hp' => $hp,
            'supervisor' => $supervisor,
            'kreator' => Session::get('id'),
        ]);

        notify()->success('Mandor berhasil diubah.');
        return redirect('/dashboard/daftar_mandor');
    }

    public function daftar_mandor_edit($id)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['user'] = User::where('role', 'supervisor')->orderBy('username', 'asc')->get();
        $data['mandor'] = Mandor::where('id', $id)->first();

        return view('dashboard.mandor_edit', $data);
    }

    public function daftar_mandor_hapus($id)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        Mandor::where('id', $id)->delete();

        return redirect('/dashboard/daftar_mandor');
    }

    public function daftar_tukang()
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['tukang'] = Tukang::where(function($query){
            if(Session::get('role') === 'supervisor'){
                $query_m = Mandor::where('supervisor',Session::get('id'))->get();
                $mandor = [];
                foreach($query_m as $q){
                    $mandor[] = $q->id;
                }
                $query->whereIn('mandor',$mandor)->orWhere('kreator',Session::get('id'));
            }
        })
        ->orderBy('nama', 'asc')->get();

        return view('dashboard.tukang', $data);
    }

    public function daftar_tukang_tambah()
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['mandor'] = Mandor::orderBy('nama', 'asc')->get();

        return view('dashboard.tukang_tambah', $data);
    }

    public function daftar_tukang_aksi(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'nama' => 'Nama',
        ];

        $rules = [
            'nama' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $message, $attribute);


        if ($validator->fails()) {
            notify()->error('Gagal menambahkan tukang.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $nama = $request->input('nama');
        $alamat = $request->input('alamat');
        $hp = $request->input('telp');
        $mandor = $request->input('mandor');

        Tukang::create([
            'nama' => $nama,
            'alamat' => $alamat,
            'hp' => $hp,
            'mandor' => $mandor,
            'kreator' => Session::get('id'),
        ]);

        notify()->success('Tukang berhasil ditambahkan.');
        return redirect('/dashboard/daftar_tukang');
    }

    public function daftar_tukang_update(Request $request)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
        ];
        $attribute = [
            'nama' => 'Nama',
        ];

        $rules = [
            'nama' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $message, $attribute);


        if ($validator->fails()) {
            notify()->error('Gagal menambahkan tukang.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $id = $request->input('id');
        $nama = $request->input('nama');
        $alamat = $request->input('alamat');
        $hp = $request->input('telp');
        $mandor = $request->input('mandor');

        Tukang::where('id', $id)->update([
            'nama' => $nama,
            'alamat' => $alamat,
            'hp' => $hp,
            'mandor' => $mandor,
            'kreator' => Session::get('id'),
        ]);

        notify()->success('Tukang berhasil diubah.');
        return redirect('/dashboard/daftar_tukang');
    }

    public function daftar_tukang_edit($id)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['mandor'] = Mandor::orderBy('nama', 'asc')->get();
        $data['tukang'] = Tukang::where('id', $id)->first();

        return view('dashboard.tukang_edit', $data);
    }

    public function daftar_tukang_hapus($id)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        Tukang::where('id', $id)->delete();

        return redirect('/dashboard/daftar_tukang');
    }

    public function gaji_mandor()
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['gaji'] = GajiMandor::orderBy('mandor', 'asc')->orderBy('pokok', 'asc')->get();

        return view('dashboard.gajimandor', $data);
    }

    public function gaji_mandor_tambah()
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['mandor'] = Mandor::orderBy('nama', 'asc')->get();
        return view('dashboard.gajimandor_tambah', $data);
    }

    public function gaji_mandor_aksi(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
        ];
        $attribute = [
            'level' => 'Level',
            'pokok' => 'Gaji pokok',
            'lembur' => 'Uang Lembur',
        ];
        $validator = Validator::make($request->all(), [
            'level' => 'required',
            'pokok' => 'required|numeric|gte:0',
            'lembur' => 'required|numeric|gte:0',
        ], $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal menambahkan pengaturan.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $mandor = $request->input('mandor');
        $level = $request->input('level');
        $pokok = $request->input('pokok');
        $lembur = $request->input('lembur');

        GajiMandor::create([
            'mandor' => $mandor,
            'level' => $level,
            'pokok_baru' => $pokok,
            'lembur_baru' => $lembur,
            'approved' => 0,
            'kreator' => Session::get('id'),
        ]);

        return redirect('/dashboard/gaji_mandor');
    }

    public function gaji_mandor_edit($id)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $data['gaji'] = GajiMandor::where('id', $id)->first();
        $data['mandor'] = Mandor::where('id', $data['gaji']->mandor)->first();

        return view('dashboard.gajimandor_edit', $data);
    }

    public function gaji_mandor_update(Request $request)
    {
        if (Session::get('role') === 'admin' || Session::get('role') === 'operator') {
            notify()->error('Akses dilarang.');
            return back();
        }
        $message = [
            'required' => ':attribute tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka',
            'gte' => ':attribute harus lebih dari atau sama dengan 0.',
        ];
        $attribute = [
            'level' => 'Level',
            'pokok' => 'Gaji pokok',
            'lembur' => 'Uang Lembur',
        ];
        $validator = Validator::make($request->all(), [
            'level' => 'required',
            'pokok' => 'nullable|numeric|gte:0',
            'lembur' => 'nullable|numeric|gte:0',
        ], $message, $attribute);

        if ($validator->fails()) {
            notify()->error('Gagal menambahkan pengaturan.');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $id = $request->input('id');
        $level = $request->input('level');
        $pokok = $request->input('pokok');
        $lembur = $request->input('lembur');

        GajiMandor::where('id', $id)->update([
            'level' => $level,
            'pokok_baru' => $pokok,
            'lembur_baru' => $lembur,
            'kreator' => Session::get('id'),
        ]);

        if ($pokok || $lembur) {
            GajiMandor::where('id', $id)->update([
                'approved' => 0
            ]);
        }

        return redirect('/dashboard/gaji_mandor');
    }

    public function gaji_mandor_hapus($id)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        GajiMandor::where('id', $id)->delete();

        return redirect('/dashboard/gaji_mandor');
    }

    public function gaji_mandor_approve($id, $aksi)
    {
        if (Session::get('role') === 'owner' || Session::get('role') === 'manager') {
        } else {
            notify()->error('Akses dilarang.');
            return back();
        }
        if ($aksi === "yes") {
            $data = GajiMandor::where('id', $id)->first();
            GajiMandor::where('id', $id)->update([
                'approved' => 1,
                'approver' => Session::get('id'),
                'pokok_baru' => null,
                'lembur_baru' => null,
                'tanggal' => date('Y-m-d', strtotime(now()))
            ]);
            if ($data->pokok_baru != null) {
                GajiMandor::where('id', $id)->update([
                    'pokok' => $data->pokok_baru
                ]);
            }
            if ($data->lembur_baru != null) {
                GajiMandor::where('id', $id)->update([
                    'lembur' => $data->lembur_baru
                ]);
            }
        } else {
            GajiMandor::where('id', $id)->update([
                'pokok_baru' => null,
                'lembur_baru' => null,
            ]);
        }

        return redirect('/dashboard/gaji_mandor');
    }
}
