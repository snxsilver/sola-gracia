@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon');

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12 col-lg-6 col-sm-8 target-invoice">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12 borongan-cetak">
              <div class="borongan-body">
                <div class="row my-2">
                  <div class="col-6">
                    <p>Nama</p>
                  </div>
                  <div class="col-6">
                    <div class="d-flex">
                      <p class="mr-2">: </p>
                      <p>{{$borongan->nama}}</p>
                    </div>
                  </div>
                </div>
                <div class="row my-2">
                  <div class="col-6">
                    <p>Proyek</p>
                  </div>
                  <div class="col-6">
                    <div class="d-flex">
                      <p class="mr-2">: </p>
                      <p>{{$borongan->namaproyek}}</p>
                    </div>
                  </div>
                </div>
                <div class="row my-2">
                  <div class="col-6">
                    <p>Jumlah yang diminta</p>
                  </div>
                  <div class="col-6">
                    <div class="d-flex justify-content-between">
                      <p class="mr-2">: Rp</p>
                      <p>{{number_format($nominal)}},00</p>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-12">
                    <table class="table">
                      <thead>
                        <tr>
                          <th colspan="3">REKAP PEMBAYARAN</th>
                        </tr>
                        <tr>
                          <th width="5%">No</th>
                          <th width="45%">Tanggal</th>
                          <th width="50%">Nominal</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php($no = 1)
                        @foreach($bayar as $b)
                        <tr>
                          <td>{{$no++}}</td>
                          @php($tanggal = $carbon->parse($b->tanggal)->locale('id'))
                          @php($tanggal->settings(['formatFunction' => 'translatedFormat']))
                          <td>{{$tanggal->format('j F Y')}}</td>
                          <td>
                            <div class="d-flex justify-content-between">
                              <p>Rp</p>
                              <p>{{number_format($b->nominal)}},00</p>
                            </div>
                          </td>
                        </tr>
                        @endforeach
                        @for($no;$no<16;$no++)
                        <tr>
                          <td>{{$no}}</td>
                          <td></td>
                          <td></td>
                        </tr>
                        @endfor
                      </tbody>
                      <thead>
                        <tr>
                          <th colspan="2" style="text-align: right">TOTAL</td>
                          <th>
                            <div class="d-flex justify-content-between">
                              <p>Rp</p>
                              <p>{{number_format($total)}},00</p>
                            </div>
                          </th>
                        </tr>
                      </thead>
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
      <div class="col-12 col-lg-6 col-sm-4 no-print">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12">
              <div class="d-flex justify-content-start">
                <button class="btn btn-sm btn-primary print-invoice"><i class="fa fa-print"></i> Print Invoice</button>
                <a href="{{ url('/dashboard/tukang_borongan_ekspor/' . $b->id) }}" class="btn btn-sm btn-success ml-2 text-white" style="cursor: pointer"><i class="fa fa-file-excel-o"></i> Export Excel</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->
@endsection
