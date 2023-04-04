<?php

namespace App\Http\Controllers;

use App\Models\Bukukas;
use App\Models\Invoice;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseController extends Controller
{
    // Invoice
    public function cron_3()
    {
        $sesi = Carbon::create(2023, 1, 31, 12, 0, 0);
        $start = Carbon::parse($sesi)->startOfYear();
        $query = DB::connection('mysql2')->table('invoice')->where('tanggal', '>=', $start)->where('proses', null)->orderBy('id')->limit(100);
        $jumlah = $query->count();
        $invoice = $query->get();
        foreach($invoice as $i){
            // $proyek = Proyek::where('old_id',$i->id_project)->where('tahun', 2022)->first();
            Invoice::create([
                'tahun' => 2023,
                // 'old_id' => $i->id_invoice,
                'no_invoice' => $i->no_invoice,
                'faktur_pajak' => $i->faktur_pajak,
                'tanggal' => $i->tanggal,
                'tanggal_jatuh_tempo' => $i->tanggal_jatuh_tempo,
                'nama_perusahaan' => $i->nama_perusahaan,
                'alamat' => $i->alamat,
                'telp' => $i->telp,
                'npwp' => $i->npwp,
                'dp' => $i->dp,
                'subtotal' => $i->subtotal,
                'total' => $i->total,
                'keterangan' => $i->keterangan,
                'proyek' => 1256,
                'kreator' => 1,
            ]);

            DB::connection('mysql2')->table('invoice')->where('id',$i->id)->update([
                'proses' => 1
            ]);
        }
        $sisa = DB::connection('mysql2')->table('invoice')->where('tanggal', '>=', $start)->where('proses', null)->count();

        $html = "<p>Konversi Invoice</p><p>konversi: ".$jumlah."</p><p>sisa: ".$sisa."</p>";
        echo $html;
    }

    // Bukukas
    public function cron_2()
    {
        $query = DB::connection('mysql2')->table('kas')->where('proses', null)->orderBy('id_trans')->limit(700);
        $jumlah = $query->count();
        $bukukas = $query->get();

        foreach($bukukas as $b){
            $proyek = Proyek::where('old_id',$b->id_proyek)->where('tahun', 2022)->first();
            if ($proyek){
                Bukukas::create([
                'tahun' => 2022,
                'old_id' => $b->id_trans,
                'tanggal' => $b->tgl,
                'uraian' => $b->uraian,
                'keterangan' => $b->keterangan,
                'keluar' => $b->kredit,
                'masuk' => $b->debet,
                'kategori' => 1,
                'proyek' => $proyek->id,
                'kreator' => 1,
                ]);
            }

            DB::connection('mysql2')->table('kas')->where('id_trans',$b->id_trans)->update([
                'proses' => 1
            ]);
        }
        $sisa = DB::connection('mysql2')->table('kas')->where('proses', null)->count();

        $html = "<p>Konversi Bukukas</p><p>konversi: ".$jumlah."</p><p>sisa: ".$sisa."</p>";
        echo $html;
    }

    // Proyek
    public function cron_1()
    {
        $query = DB::connection('mysql2')->table('proyek')->where('proses', 1)->orderBy('id_proyek')->limit(300);
        $jumlah = $query->count();
        $proyek = $query->get();
        foreach($proyek as $p){
            Proyek::create([
                'tahun' => 2022,
                'old_id' => $p->id_proyek,
                'kode' => $p->kode_proyek,
                'nama' => $p->nama_proyek,
                'nilai' => $p->nilai_proyek,
                'pajak' => 1,
                'kreator' => 1,
            ]);

            DB::connection('mysql2')->table('proyek')->where('id_proyek',$p->id_proyek)->update([
                'proses' => 2
            ]);
        }

        $sisa = DB::connection('mysql2')->table('proyek')->where('proses', 1)->count();

        $html = "<p>Konversi Proyek</p><p>konversi: ".$jumlah."</p><p>sisa: ".$sisa."</p>";
        echo $html;
    }

    // test
    public function cron_0()
    {
        DB::connection('mysql2')->table('trycon')->insert([
            'name' => 'check'
        ]);
    }

    public function cron_4(){
        $query = Proyek::where('proses', null)->orderBy('id','asc')->limit(300);
        $jumlah = $query->count();
        $proyek = $query->get();
        foreach($proyek as $p){
            Proyek::where('id',$p->id)->update([
                'proses' => 1,
                'pajak' => 0,
            ]);
        }

        $sisa = Proyek::where('proses', null)->count();

        $html = "<p>Konversi Proyek</p><p>konversi: ".$jumlah."</p><p>sisa: ".$sisa."</p>";
        echo $html;
    }

    public function cron_5(){
        $query = Invoice::where('proses', null)->orderBy('id','asc')->limit(300);
        $jumlah = $query->count();
        $invoice = $query->get();
        foreach($invoice as $i){
            if ($i->total != $i->subtotal){
                $proyek = Proyek::where('id',$i->proyek)->first();
                if ($proyek){
                    if ($proyek->pajak == 0){
                        Proyek::where('id',$i->proyek)->update([
                            'pajak' => 1,
                        ]);
                    }
                }
            }

            Invoice::where('id',$i->id)->update([
                'proses' => 1,
            ]);
        }

        $sisa = Invoice::where('proses', null)->count();

        $html = "<p>Konversi Invoice</p><p>konversi: ".$jumlah."</p><p>sisa: ".$sisa."</p>";
        echo $html;
    }

    public function cron_6(){
        $query = Invoice::where('tahun',2023)->where('proses', 1)->orderBy('id','asc')->limit(5);
        $jumlah = $query->count();
        $invoice = $query->get();
        foreach($invoice as $i){
            $bukukas = Bukukas::create([
                'tahun' => Carbon::parse(now())->year,
                'proyek' => $i->proyek,
                'tanggal' => $i->tanggal,
                'uraian' => $i->keterangan,
                'kategori' => 2,
                'masuk' => $i->subtotal + $i->dp,
                'kreator' => 1,
                'ambil_stok' => 2,
            ]);

            $dbproyek = Proyek::where('id', $i->proyek)->first();
            // if($dbproyek){   
                Proyek::where('id', $i->proyek)->update([
                    'nilai' => $dbproyek->nilai + $i->subtotal + $i->dp
                ]);
            // }

            Invoice::where('id',$i->id)->update([
                'bukukas' => $bukukas->id,
                'proses' => 2,
            ]);
        }

        $sisa = Invoice::where('tahun',2023)->where('proses', 1)->count();

        $html = "<p>Konversi Invoice</p><p>konversi: ".$jumlah."</p><p>sisa: ".$sisa."</p>";
        echo $html;
    }

    public function cron_7(){
        $query = Invoice::where('proses', 2)->orderBy('id','asc')->limit(2);
        $jumlah = $query->count();
        $invoice = $query->get();
        foreach($invoice as $i){
            // $bukukas = Bukukas::create([
            //     'tahun' => Carbon::parse(now())->year,
            //     'proyek' => $i->proyek,
            //     'tanggal' => $i->tanggal,
            //     'uraian' => $i->keterangan,
            //     'kategori' => 2,
            //     'masuk' => $i->subtotal + $i->dp,
            //     'kreator' => 1,
            //     'ambil_stok' => 2,
            // ]);
            Bukukas::where('id',$i->bukukas)->delete();

            $dbproyek = Proyek::where('id', $i->proyek)->first();

            Proyek::where('id', $i->proyek)->update([
                'nilai' => $dbproyek->nilai - $i->subtotal - $i->dp
            ]);

            Invoice::where('id',$i->id)->update([
                'bukukas' => null,
                'proses' => 1,
            ]);
        }

        $sisa = Invoice::where('proses', 2)->count();

        $html = "<p>Konversi Invoice</p><p>konversi: ".$jumlah."</p><p>sisa: ".$sisa."</p>";
        echo $html;
    }
}
