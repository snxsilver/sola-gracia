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
                    <table class="table">
                      <tbody>
                        @php($query = DB::table('harian_bayar')->where('harian', $harian->id)->orderBy('tanggal', 'desc')->first())
                        @php($tanggalacuan = $query->tanggal)
                        @php($subnum = $carbon->parse($tanggalacuan)->getDaysFromStartOfWeek())
                        @php($date = $carbon->parse($tanggalacuan)->subDays($subnum))
                        <tr>
                          <td colspan="3">
                            <div class="d-flex">
                              <p>Nama</p>
                              <p class="ml-3">: {{ $harian->nama }}</p>
                            </div>
                          </td>
                          <td colspan="4">
                            <div class="d-flex">
                              <p>Tanggal</p>
                              @php($tanggal = $carbon->parse($carbon->parse($date))->locale('id'))
                              @php($tanggal->settings(['formatFunction' => 'translatedFormat']))
                              @php($tanggal2 = $carbon->parse($carbon->parse(date('Y-m-d', strtotime($carbon->parse($date)->addDays(6)))))->locale('id'))
                              @php($tanggal2->settings(['formatFunction' => 'translatedFormat']))
                              <p class="ml-3">: {{$tanggal->format('j M').' - '.$tanggal2->format('j M Y')}}</p>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <div class="d-flex">
                              <p>Pokok</p>
                              <p class="ml-3">: Rp {{ number_format($harian->pokok) }}</p>
                            </div>
                          </td>
                          <td>
                            <div class="d-flex">
                              <p>Insentif</p>
                              <p class="ml-3">: Rp {{ number_format($harian->insentif) }}</p>
                            </div>
                          </td>
                          <td colspan="4">
                            <div class="d-flex">
                              <p>Lembur / jam</p>
                              <p class="ml-3">: Rp {{ number_format($harian->lembur) }}</p>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td width="15%">Hari</td>
                          <td>Proyek</td>
                          <td>Keterangan Pekerjaan</td>
                          <td>Hr</td>
                          <td>Jam</td>
                          <td>Transport</td>
                          <td>Total</td>
                        </tr>
                        @php($query_b = DB::table('harian_bayar')->where('harian', $harian->id)->join('proyek','harian_bayar.proyek','=','proyek.id')->select('harian_bayar.*','proyek.nama')->orderBy('tanggal', 'asc'))
                        @php($bayar = $query_b->get())
                        @php($hr = $query_b->count())
                        @php($total_jam = $query_b->sum('jam'))
                        @php($total_transport = $query_b->sum('uang_transport'))
                        @php($total = $query_b->sum('total'))
                        @php($i = 0)
                        @for ($x = 0; $x < 7; $x++)
                          @php($cektanggal = date('Y-m-d', strtotime($carbon->parse($date)->addDays($x))))
                          @php($tanggal = $carbon->parse($carbon->parse($date)->addDays($x))->locale('id'))
                          @php($tanggal->settings(['formatFunction' => 'translatedFormat']))
                          <tr>
                            <td>{{ $tanggal->format('l, j M') }}</td>
                            @if($cektanggal == $bayar[$i]->tanggal)
                            <td>{{$bayar[$i]->nama}}</td>
                            <td>{{ $bayar[$i]->keterangan }}</td>
                            <td>1</td>
                            <td>{{$bayar[$i]->jam == 0 ? '' : $bayar[$i]->jam}}</td>
                            <td>{{$bayar[$i]->uang_transport == 0 ? '' : $bayar[$i]->uang_transport}}</td>
                            <td>Rp {{number_format($bayar[$i]->total)}}</td>
                            @else
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @endif
                          </tr>
                          @if ($cektanggal == $bayar[$i]->tanggal && $i < count($bayar) - 1)
                            @php($i++)
                          @endif
                        @endfor
                        <tr>
                          <td colspan="3">Jumlah</td>
                          <td>{{$hr}}</td>
                          <td>{{$total_jam == 0 ? '' : $total_jam}}</td>
                          <td>{{$total_transport == 0 ? '' : $total_transport}}</td>
                          <td>Rp {{number_format($total)}}</td>
                        </tr>
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
      @if($harian->approved === 1)
      <div class="col-12 no-print">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12">
              <div class="d-flex justify-content-start">
                <button class="btn btn-sm btn-primary print-invoice"><i class="fa fa-print"></i> Print Invoice</button>
                <a href="{{ url('/dashboard/tukang_harian_ekspor/' . $harian->id) }}" class="btn btn-sm btn-success ml-2 text-white" style="cursor: pointer"><i class="fa fa-file-excel-o"></i> Export Excel</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
  <!-- /page content -->
@endsection
