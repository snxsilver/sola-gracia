@extends('dashboard.header');

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12">
        <div class="x_panel">
          <div class="x_content">
            <div class="row">
              <div class="col-12">
                <div class="d-flex flex-column justify-content-center align-items-center" style="height: 70vh">
                  <div class="d-flex align-items-center">
                    <h3>WELCOME TO</h3>
                    <div class="align-items-center ml-5">
                      <img src="{{ asset('/images/sola-gracia.png') }}" style="width: 150px; margin-bottom: 10px"
                        alt="">
                      <div class="text-center">
                        <p style="font-size: 20px; color: black">CV. Sola Gracia</p>
                      </div>
                    </div>
                  </div>
                  {{-- <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; border: 1px solid black; border-radius: 100px">
                      <span class="d-flex justify-content-center align-items-center" style="height: 40px; font-size: 30px">
                        <i class="fa fa-user"></i>
                      </span>
                    </div>
                    <div class="align-items-center ml-2">
                      <h2 style="margin: 0; color: black">{{Session::get('username')}}</h2>
                      <span style="color: gray">{{ucwords(Session::get('role'))}}</span>
                    </div>
                  </div> --}}
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
