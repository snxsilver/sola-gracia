@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon');
@inject('helper', 'App\Helpers\Helper');

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12 target-invoice">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12 mandor-cetak">
              <div class="mandor-body">
                <div class="row">
                  <div class="col-12">
                    @php($tanggalacuan = $mandor->tanggal)
                    @php($subnum = $carbon->parse($tanggalacuan)->getDaysFromStartOfWeek())
                    @php($tanggal = $carbon->parse($tanggalacuan)->subDays($subnum))
                    @php($tanggal1 = $carbon->parse($carbon->parse($tanggal)->addDays(0))->locale('id'))
                    @php($tanggal1->settings(['formatFunction' => 'translatedFormat']))
                    @php($tanggal2 = $carbon->parse($carbon->parse($tanggal)->addDays(6))->locale('id'))
                    @php($tanggal2->settings(['formatFunction' => 'translatedFormat']))
                    @php($query_px = DB::table('proyek')->where('id',$xyzproyek[0])->first())
                    <p class="mb-2">Mandor : {{ucwords($mandor->nama)}} (@if(count($xyzproyek) == 1){{$query_px->nama}}@endif {{ $tanggal1->format('j F').' - '.$tanggal2->format('j F Y')}})</p>
                    <p style="font-size: 12px">Daftar Hadir Karyawan</p>
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
                          <td rowspan="2" width="4%">UM</td>
                          <td rowspan="2" width="4%">Transport</td>
                          <td rowspan="2" width="4%">Jumlah</td>
                          @if(count($xyzproyek) > 1)
                          <td rowspan="2" width="4%">Jumlah per Orang</td>
                          @endif
                        </tr>
                        <tr>
                          <td width="2%">hr</td>
                          <td width="2%">jam</td>
                          <td width="2%">UM</td>
                          <td width="2%">hr</td>
                          <td width="2%">jam</td>
                          <td width="2%">UM</td>
                          <td width="2%">hr</td>
                          <td width="2%">jam</td>
                          <td width="2%">UM</td>
                          <td width="2%">hr</td>
                          <td width="2%">jam</td>
                          <td width="2%">UM</td>
                          <td width="2%">hr</td>
                          <td width="2%">jam</td>
                          <td width="2%">UM</td>
                          <td width="2%">hr</td>
                          <td width="2%">jam</td>
                          <td width="2%">UM</td>
                          <td width="2%">hr</td>
                          <td width="2%">jam</td>
                          <td width="2%">UM</td>
                          <td width="2%">hr</td>
                          <td width="2%">jam</td>
                          <td width="2%">Tr</td>
                          <td width="2%">UM</td>
                          <td width="4%">Hr</td>
                          <td width="4%">Jam</td>
                        </tr>
                        @php($tanggalacuan = $mandor->tanggal)
                        @php($subnum = $carbon->parse($tanggalacuan)->getDaysFromStartOfWeek())
                        @php($tanggal = $carbon->parse($tanggalacuan)->subDays($subnum))
                        @php($f = 1)
                        @foreach($tukang as $t)
                        <tr>
                          <td>{{$f++}}</td>
                          <td colspan="28">{{ucwords($t->nama)}}</td>
                          @php($proyek = DB::table('gaji_mandor_tukang')->where('mandor_tukang',$t->id)->orderBy('proyek','asc')->get())
                          <td></td>
                          @if(count($xyzproyek) > 1)
                          <td></td>
                          @endif
                        </tr>
                        @php($abcproyek = [])
                        @php($varp = null)
                        @foreach($proyek as $p)
                        @if($p->proyek !== $varp)
                        @php($abcproyek[] = $p->proyek)
                        @endif
                        @php($varp = $p->proyek)
                        @endforeach
                        @foreach($abcproyek as $a)
                        <tr>
                          @php($query = DB::table('gaji_mandor_tukang')->where('mandor_tukang',$t->id)->where('proyek',$a)->orderBy('tanggal','asc'))
                          @php($bayar = $query->get())
                          @php($jam = $query->sum('jam_lembur'))
                          @php($hr = $query->count())
                          @php($tr = $query->where('uang_transport','!=','0')->count())
                          @php($mk = $query->where('uang_makan','!=','0')->count())
                          @if (count($bayar) > 0)
                            @php($i = 0)
                            @for($x = 0; $x < 7; $x++)
                              @php($cektanggal = date('Y-m-d', strtotime($carbon->parse($tanggal)->addDays($x))))
                              @if($cektanggal == $bayar[$i]->tanggal)
                                @php($transport = DB::table('setting_tunjangan')->where('id',$bayar[$i]->transport)->first())
                                <td>{{$helper->toHuruf(array_search($bayar[$i]->proyek,$xyzproyek)).$transport->level}}</td>
                                <td>{{$bayar[$i]->jam_lembur}}</td>
                                @php($makan = DB::table('setting_tunjangan')->where('id',$bayar[$i]->makan)->first())
                                <td>{{$makan->level != 0 ? $makan->level : ''}}</td>
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
                            <td>{{$jam !== 0 ? $jam : ''}}</td>
                            <td>{{$tr !== 0 ? $tr : ''}}</td>
                            <td>{{$mk !== 0 ? $mk : ''}}</td>
                            <td>{{number_format($bayar[0]->uang_pokok)}}</td>
                            <td>{{number_format($bayar[0]->uang_lembur)}}</td>
                            @if($mk !== 0)
                            @php($query_mx = DB::table('gaji_mandor_tukang')->where('mandor_tukang',$t->id)->where('proyek',$a)->where('uang_makan','!=','0')->first())
                            @php($uang_makan = $query_mx->uang_makan)
                            <td>{{number_format($query_mx->uang_makan)}}</td>
                            @else
                            @php($uang_makan = 0)
                            <td></td>
                            @endif
                            @if($tr !== 0)
                            @php($query_tx = DB::table('gaji_mandor_tukang')->where('mandor_tukang',$t->id)->where('proyek',$a)->where('uang_transport','!=','0')->first())
                            @php($uang_transport = $query_tx->uang_transport)
                            <td>{{number_format($query_tx->uang_transport)}}</td>
                            @else
                            @php($uang_transport = 0)
                            <td></td>
                            @endif
                            @php($tt = ($hr * $bayar[0]->uang_pokok) + ($bayar[0]->uang_lembur * $jam) + ($uang_makan * $mk) + ($uang_transport * $tr))
                            <td>{{number_format($tt)}}</td>
                            @if(count($xyzproyek) > 1)
                            <td></td>
                            @endif
                          @endif
                        </tr>
                        @endforeach
                        <tr>
                          @for($x = 0; $x < 30; $x++)
                          <td style="height: 20px"></td>
                          @endfor
                          @if(count($xyzproyek) > 1)
                          @php($total = DB::table('gaji_mandor_tukang')->where('mandor_tukang',$t->id)->sum('total'))
                          <td>{{number_format($total)}}</td>
                          @endif
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    @if(count($xyzproyek) > 1)
                    <div class="row mandor-footer">
                      @for($x = 0; $x < count($xyzproyek); $x++)
                      <div class="col-3">
                        <div class="d-flex">
                          <p style="width: 25px">{{$helper->toHuruf($x)}}</p>
                          <p>:</p>
                          @php($query_p = DB::table('proyek')->where('id',$xyzproyek[$x])->first())
                          <p class="ml-1">{{$query_p->nama}}</p>
                        </div>
                      </div>
                      @endfor
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @if($mandor->approved === 1)
      <div class="col-12 no-print">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12">
              <div class="d-flex justify-content-start">
                <button class="btn btn-sm btn-primary print-invoice"><i class="fa fa-print"></i> Print Invoice</button>
                <a href="{{ url('/dashboard/tukang_mandor_ekspor/' . $mandor->id) }}" class="btn btn-sm btn-success ml-2 text-white" style="cursor: pointer"><i class="fa fa-file-excel-o"></i> Export Excel</a>
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
