@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon');

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12 target-invoice">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12 harian-cetak">
              <div class="harian-body">
                <div class="row">
                  <div class="col-12">
                    <p>Mandor : </p>
                    <p>Daftar Hadir Karyawan</p>
                    <table class="table">
                      <tbody>
                        <tr>
                          <td colspan="3">Minggu</td>
                          <td colspan="3">Senin</td>
                          <td colspan="3">Selasa</td>
                          <td colspan="3">Rabu</td>
                          <td colspan="3">Kamis</td>
                          <td colspan="3">Jumat</td>
                          <td colspan="3">Sabtu</td>
                          <td colspan="4">Jumlah</td>
                          <td colspan="2">Gaji</td>
                          <td rowspan="2">UM</td>
                          <td rowspan="2">Transport</td>
                          <td rowspan="2">Jumlah</td>
                        </tr>
                        <tr>
                          <td width="3%">hr</td>
                          <td width="3%">jam</td>
                          <td width="3%">UM</td>
                          <td width="3%">hr</td>
                          <td width="3%">jam</td>
                          <td width="3%">UM</td>
                          <td width="3%">hr</td>
                          <td width="3%">jam</td>
                          <td width="3%">UM</td>
                          <td width="3%">hr</td>
                          <td width="3%">jam</td>
                          <td width="3%">UM</td>
                          <td width="3%">hr</td>
                          <td width="3%">jam</td>
                          <td width="3%">UM</td>
                          <td width="3%">hr</td>
                          <td width="3%">jam</td>
                          <td width="3%">UM</td>
                          <td width="3%">hr</td>
                          <td width="3%">jam</td>
                          <td width="3%">UM</td>
                          <td width="3%">hr</td>
                          <td width="3%">jam</td>
                          <td width="3%">Tr</td>
                          <td width="6%">UM</td>
                          <td width="6%">Hr</td>
                          <td width="7%">Jam</td>
                        </tr>
                        @php($tanggalacuan = $mandor->tanggal)
                        @php($subnum = $carbon->parse($tanggalacuan)->getDaysFromStartOfWeek())
                        @php($tanggal = $carbon->parse($tanggalacuan)->subDays($subnum))
                        @foreach($tukang as $t)
                        <tr>
                          <td>11</td>
                          <td colspan="28">Saefudin</td>
                          @php($proyek = DB::table('gaji_mandor_tukang')->where('mandor_tukang',$t->id)->distinct('proyek')->get())
                          <td></td>
                        </tr>
                        <tr>
                          {{dd($proyek)}}
                        </tr>
                        <tr>
                          @php($query = DB::table('gaji_mandor_tukang')->where('mandor_tukang',$t->id)->orderBy('tanggal','asc'))
                          @php($bayar = $query->get())
                          @php($jam = $query->sum('jam_lembur'))
                          @php($hr = $query->count())
                          @if (count($bayar) > 0)
                            @php($i = 0)
                            @for($x = 0; $x < 7; $x++)
                              @php($cektanggal = date('Y-m-d', strtotime($carbon->parse($tanggal)->addDays($x))))
                              @if($cektanggal == $bayar[$i]->tanggal)
                                @php($transport = DB::table('setting_tunjangan')->where('id',$bayar[$i]->transport)->first())
                                <td>{{$bayar[$i]->proyek.$transport->level}}</td>
                                <td>{{$bayar[$i]->jam_lembur}}</td>
                                @php($makan = DB::table('setting_tunjangan')->where('id',$bayar[$i]->makan)->first())
                                <td>{{$makan->level}}</td>
                              @else
                                <td></td>
                                <td></td>
                                <td></td>
                              @endif
                                @if ($cektanggal == $bayar[$i]->tanggal && $i < count($bayar) - 1)
                                  @php($i++)
                                @endif
                            @endfor
                            <td>{{$hr}}</td>
                            <td>{{$jam}}</td>
                            <td></td>
                            <td></td>
                          @else
                            @for($x = 0; $x < 7; $x++)
                              <td>1</td>
                              <td></td>
                              <td></td>
                            @endfor
                          @endif
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              {{-- <div class="invoice-footer">
                <div class="row signature-box-2">
                  <div class="col-8">
                    <div class="d-flex align-items-end" style="height: 100%">
                      <div class="align-items-center">
                        <h5>PERHATIAN !</h5>
                        <small>*Pembayaran dapat dilakukan melalui Cheque / Giro ke Rek BCA 0093045278 a.n CV Sola
                          Gracia KCU Semarang Pemuda atau ke Rek BNI cabang Semarang 1228198449 a.n CV SOLA
                          GRACIA</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div> --}}
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 no-print">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12">
              <div class="d-flex justify-content-start">
                <button class="btn btn-sm btn-primary print-invoice"><i class="fa fa-print"></i> Print Invoice</button>
                {{-- <a href="{{ url('/dashboard/tukang_borongan_ekspor/' . $b->id) }}" class="btn btn-sm btn-success ml-2 text-white" style="cursor: pointer"><i class="fa fa-file-excel-o"></i> Export Excel</a> --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->
@endsection
