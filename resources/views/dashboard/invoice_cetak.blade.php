@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon')

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12">
              <div class="my-3 invoice-cetak">
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
                <div class="px-5">
                  <div class="d-flex justify-content-left">
                    <p>No: 02/I/SG/2019</p>
                  </div>
                  <div class="row">
                    <div class="col-3">
                      <p>Telah Terima dari</p>
                    </div>
                    <div class="col-9">
                      <p>PT Waskita Karya (Persero), Tbk.</p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-3">
                      <p>Uang Sejumlah</p>
                    </div>
                    <div class="col-9">
                      <div class="kotak-terbilang">
                        <p>#Tujuhbelas Juta Delapan Ratus Enam Puluh Tiga Ribu Sembilan Ratus Delapan Puluh TujuhRupiah#</p>
                      </div>
                    </div>
                  </div>
                  <div class="row">
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
      <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
          <div class="x_content">
            <div class="col-12">
              <div class="my-3 invoice-cetak">
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
                <div class="px-5">
                  <div class="d-flex justify-content-left">
                    <p>No: 02/I/SG/2019</p>
                  </div>
                  <div class="row">
                    <div class="col-3">
                      <p>Telah Terima dari</p>
                    </div>
                    <div class="col-9">
                      <p>PT Waskita Karya (Persero), Tbk.</p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-3">
                      <p>Uang Sejumlah</p>
                    </div>
                    <div class="col-9">
                      <div class="kotak-terbilang">
                        <p>#Tujuhbelas Juta Delapan Ratus Enam Puluh Tiga Ribu Sembilan Ratus Delapan Puluh TujuhRupiah#</p>
                      </div>
                    </div>
                  </div>
                  <div class="row">
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
    </div>
  </div>
  <!-- /page content -->
@endsection
