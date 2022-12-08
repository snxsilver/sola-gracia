@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon')

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-md-12 col-sm-12 target-invoice">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12">
              <div class="mt-3 mb-5 invoice-cetak">
                <div class="align-items-center d-flex">
                  <img src="{{asset('/images/solagracia.png')}}" height="100px" alt="">
                  <div class="align-items-center ml-3">
                    <h4 class="font-weight-bold">CV. Sola Gracia</h4>
                    <p>Jl. Kimar III No. 10, Semarang</p>
                    <p>Telp. (024) 6721445 / (024) 76728535 - Fax. (024) 6721445</p>
                  </div>
                </div>
                <div class="d-flex justify-content-center">
                  <h3>INVOICE</h3>
                </div>
                <div class="px-5">
                  <div class="row">
                    <div class="col-8">
                      <p>Kepada</p>
                      <p>PT Waskita Karya (Persero), Tbk.</p>
                      <p>Jl. MT. Haryono kav. no. 10, RT 011 RW 011, Cipinang Cempedak, Jatinegara, Jakarta Timur - DKI Jakarta 13340</p>
                      <p>NPWP : 01.001.614.5-093.000</p>
                      <p>NPWP : 01.001.614.5-093.000</p>
                    </div>
                    <div class="col-4">
                      <p>No Invoice : 02/I/SG/2019</p>
                      <p>No Faktur Pajak : 030.***-19.********</p>
                      <p>Tgl Jatuh Tempo : 11-01-2019</p>
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
                            <td>Retensi 5% Pek. Acrylic Penutup FCU Sesuai Surat Perjanjian No. 136/SPPP/WK/D.I/AY/2018 Tgl</td>
                            <td style="text-align: right">16,239,988</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                      <div class="d-flex justify-content-between align-items-center">
                        <p>DP :</p>
                        <p>0</p>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <p>Dasar Pengenaan Pajak :</p>
                        <p>16,239,988</p>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <p>PPN :</p>
                        <p>1,623,999</p>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <p>Total :</p>
                        <p>17,863,987</p>
                      </div>
                    </div>
                  </div>
                  <div class="row signature-box-2">
                    <div class="col-8 d-flex align-items-center">
                      <p>#Tujuhbelas Juta Delapan Ratus Enam Puluh Tiga Ribu Sembilan Ratus Delapan Puluh Tujuh</p>
                    </div>
                    <div class="col-4">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class=""></div>
                        <div class="align-items-center">
                          <div class="align-items-center signature">
                            <p>Semarang, 04 January 2019</p>
                            <p>CV. Sola Gracia</p>
                          </div>
                          <p>Roriyanto</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="signature-box-2">
                    <div class="row">
                      <div class="col-8">
                        <h5>PERHATIAN !</h5>
                        <small>*Pembayaran dapat dilakukan melalui Cheque / Giro ke Rek BCA 0093045278 a.n CV Sola Gracia KCU Semarang Pemuda atau ke Rek BNI cabang Semarang 1228198449 a.n CV SOLA GRACIA</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 no-print">
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
      <div class="col-md-12 col-sm-12 target-kwitansi">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12">
              <div class="mt-3 mb-5 invoice-cetak">
                <div class="align-items-center d-flex">
                  <img src="{{asset('/images/solagracia.png')}}" height="100px" alt="">
                  <div class="align-items-center ml-3">
                    <h4 class="font-weight-bold">CV. Sola Gracia</h4>
                    <p>Jl. Kimar III No. 10, Semarang</p>
                    <p>Telp. (024) 6721445 / (024) 76728535 - Fax. (024) 6721445</p>
                  </div>
                </div>
                <div class="d-flex justify-content-center">
                  <h3>KWITANSI</h3>
                </div>
                <div class="px-5 mb-1">
                  <div class="d-flex justify-content-left">
                    <p>No: 02/I/SG/2019</p>
                  </div>
                  <div class="row my-1">
                    <div class="col-3">
                      <p>Telah Terima dari</p>
                    </div>
                    <div class="col-9">
                      <p>PT Waskita Karya (Persero), Tbk.</p>
                    </div>
                  </div>
                  <div class="row my-1">
                    <div class="col-3">
                      <p>Uang Sejumlah</p>
                    </div>
                    <div class="col-9">
                      <div class="kotak-terbilang">
                        <p>#Tujuhbelas Juta Delapan Ratus Enam Puluh Tiga Ribu Sembilan Ratus Delapan Puluh TujuhRupiah#</p>
                      </div>
                    </div>
                  </div>
                  <div class="row my-1">
                    <div class="col-3">
                      <p>Untuk Pembayaran</p>
                    </div>
                    <div class="col-9">
                      <p>Retensi 5% Pek. Acrylic Penutup FCU Sesuai Surat Perjanjian No. 136/SPPP/WK/D.I/AY/2018 Tgl
                        14/09/2018 & Add. II No. 136/ADD.II/SPPP/WK/D.I/AY/2018 Tgl 13/12/2018. Proyek Bangunan
                        Terminal & Sarana Penunjang (Paket3) Bandara Ahmad Yani SMG</p>
                    </div>
                  </div>
                  <div class="d-flex justify-content-between align-items-end mt-8 signature-box">
                    <p>Rp #17,863,987#</p>
                    <div class="align-items-center">
                      <div class="align-items-center signature">
                        <p>Semarang, 04 January 2019</p>
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
      <div class="col-md-12 col-sm-12 no-print">
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
