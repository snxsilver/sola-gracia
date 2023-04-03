@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon');
@inject('terbilang', 'App\Helpers\Helper');

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12 target-invoice">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12 invoice-cetak">
              <div class="invoice-body">
                <div class="d-flex align-items-end">
                  <img src="{{ asset('/images/solagracia.png') }}" style="height: 70px; margin-bottom: 0.4rem" alt="">
                  <div class="align-items-center ml-3">
                    <h4 class="font-weight-bold">CV. Sola Gracia</h4>
                    <p>Jl. Kimar III No. 10, Semarang</p>
                    <p>Telp. (024) 6721445 / (024) 76728535 - Fax. (024) 6721445</p>
                  </div>
                </div>
                <div class="d-flex justify-content-center mt-3">
                  <h3>INVOICE</h3>
                </div>
                <div class="">
                  <div class="row">
                    <div class="col-8">
                      @if (!$invoice->alamat)
                        <p>Kepada : {{ $invoice->nama_perusahaan }}</p>
                      @else
                        <p>Kepada</p>
                        <p>{{ $invoice->nama_perusahaan }}</p>
                        <p>{{ $invoice->alamat }}</p>
                      @endif
                      <p>{{ $invoice->telp ? 'Telp : ' . $invoice->telp : '' }}</p>
                      <p>{{ $invoice->npwp ? 'NPWP : ' . $invoice->npwp : '' }}</p>
                    </div>
                    <div class="col-4">
                      <p>No Invoice : {{ $invoice->no_invoice }}</p>
                      <p>{{ $invoice->faktur_pajak ? 'No Faktur Pajak : ' . $invoice->faktur_pajak : '' }}</p>
                      @php($tanggal = $carbon->parse($invoice->tanggal_jatuh_tempo)->locale('id'))
                      @php($tanggal->settings(['formatFunction' => 'translatedFormat']))
                      <p>{{ $invoice->tanggal_jatuh_tempo ? 'Tgl Jatuh Tempo : ' . $tanggal->format('j F Y') : '' }}</p>
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="col-12">
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Nama Pekerjaan</th>
                            <th style="text-align: right">Nominal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td style="white-space: pre-line">{{ $invoice->keterangan }}</td>
                            <td style="text-align: right">
                              <div class="d-flex justify-content-end">
                                <div class="d-flex justify-content-between" @if($invoice->subtotal > 999999999) style="width: 120px" @else style="width: 100px" @endif>
                                  <p>Rp</p>
                                  <p>{{number_format($invoice->subtotal)}}</p>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                      <div class="" style="margin-right: 0.3rem">
                        <div class="d-flex justify-content-between align-items-center">
                          <p>DP :</p>
                          <div class="d-flex justify-content-between" @if($invoice->subtotal > 999999999) style="width: 120px" @else style="width: 100px" @endif>
                            <p>Rp</p>
                            <p>{{number_format($invoice->dp ?? 0)}}</p>
                          </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                          <p>Dasar Pengenaan Pajak :</p>
                          <div class="d-flex justify-content-between" @if($invoice->subtotal > 999999999) style="width: 120px" @else style="width: 100px" @endif>
                            <p>Rp</p>
                            <p>{{number_format($invoice->subtotal)}}</p>
                          </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                          <p>PPN :</p>
                          <div class="d-flex justify-content-between" @if($invoice->subtotal > 999999999) style="width: 120px" @else style="width: 100px" @endif>
                            <p>Rp</p>
                            <p>{{number_format($invoice->total - $invoice->subtotal)}}</p>
                          </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                          <p>Total :</p>
                          <div class="d-flex justify-content-between" @if($invoice->subtotal > 999999999) style="width: 120px" @else style="width: 100px" @endif>
                            <p>Rp</p>
                            <p>{{number_format($invoice->total)}}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col">
                      <div class="d-flex">
                        <p class="mr-2">Terbilang</p>
                        <p>#{{ ucwords($terbilang->terbilang($invoice->total)) }} Rupiah#</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="invoice-footer">
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
                  <div class="col-4">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class=""></div>
                      <div class="align-items-center">
                        <div class="align-items-center signature">
                          @php($tanggal = $carbon->parse($invoice->tanggal)->locale('id'))
                          @php($tanggal->settings(['formatFunction' => 'translatedFormat']))
                          <p>Semarang, {{ $tanggal->format('j F Y') }}</p>
                          <p>CV. Sola Gracia</p>
                        </div>
                        <p>Roriyanto</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 no-print">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12">
              <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-primary print-invoice"><i class="fa fa-print"></i> Print Invoice</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 target-kwitansi">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12 invoice-cetak">
              <div class="invoice-body">
                <div class="d-flex align-items-end">
                  <img src="{{ asset('/images/solagracia.png') }}" style="height: 70px; margin-bottom: 0.45rem" alt="">
                  <div class="align-items-center ml-3">
                    <h4 class="font-weight-bold">CV. Sola Gracia</h4>
                    <p>Jl. Kimar III No. 10, Semarang</p>
                    <p>Telp. (024) 6721445 / (024) 76728535 - Fax. (024) 6721445</p>
                  </div>
                </div>
                <div class="mt-3 d-flex justify-content-center">
                  <h3>KWITANSI</h3>
                </div>
                <div class="mb-1 kwitansi-cetak px-5" style="margin-right: 100px">
                  <div class="d-flex justify-content-left">
                    <p>No: <span style="text-decoration:underline">{{ $invoice->no_invoice }}</span></p>
                  </div>
                  <div class="row my-2">
                    <div class="col-3">
                      <p>Telah Terima dari</p>
                    </div>
                    <div class="col-9">
                      <p>{{ $invoice->nama_perusahaan }}</p>
                    </div>
                  </div>
                  <div class="row my-2">
                    <div class="col-3">
                      <p>Uang Sejumlah</p>
                    </div>
                    <div class="col-9">
                      <div class="kotak-terbilang">
                        <p>#{{ ucwords($terbilang->terbilang($invoice->total)) }} Rupiah#</p>
                      </div>
                    </div>
                  </div>
                  <div class="row my-2">
                    <div class="col-3">
                      <p>Untuk Pembayaran</p>
                    </div>
                    <div class="col-9">
                      <p style="white-space: pre-line">{{ $invoice->keterangan }}</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="invoice-footer kwitansi-cetak">
                <div class="d-flex justify-content-between align-items-end mt-8 signature-box">
                  <div class="d-flex align-items-center">
                    <p class="mr-2">Rp</p>
                    <div class="py-1 px-2" style="background: #d6d6d6 !important">
                      <p class="font-weight-bold">{{ '#' . number_format($invoice->total) }}#</p>
                    </div>
                  </div>
                  <div class="align-items-center">
                    <div class="align-items-center signature" style="margin-bottom: 120px">
                      @php($tanggal = $carbon->parse($invoice->tanggal)->locale('id'))
                      @php($tanggal->settings(['formatFunction' => 'translatedFormat']))
                      <p>Semarang, {{ $tanggal->format('j F Y') }}</p>
                      <p>CV. Sola Gracia</p>
                    </div>
                    <p>Roriyanto</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 no-print">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12">
              <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-primary print-kwitansi"><i class="fa fa-print"></i> Print Kwitansi</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->
@endsection
