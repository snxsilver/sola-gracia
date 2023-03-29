<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="images/favicon.ico" type="image/ico" />

  <title>CV. Sola Gracia</title>
  
  @notifyCss
  <!-- Bootstrap -->
  <link href="{{ asset('/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="{{ asset('/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
  <!-- bootstrap-progressbar -->
  <link href="{{ asset('/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="{{ asset('/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
  <link href="{{ asset('/vendors/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="{{ asset('/assets/css/custom.css') }}" rel="stylesheet">

</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col no-print">
        <div class="left_col scroll-view">
          <div class="navbar nav_title mb-3" style="border: 0;">
            <a href="{{url('/dashboard')}}" class="site_title"><i class="fa fa-gears"></i><span class="ml-2">CV Sola Gracia</span></a>
          </div>

          <div class="clearfix"></div>

          <!-- menu profile quick info -->
          <div class="profile clearfix ml-3">
            <div class="profile_pic">
              <div class="img-circle profile_img" style="width: 50px; height: 50px">
                <span class="d-flex justify-content-center align-items-center" style="height: 40px; font-size: 30px">
                  <i class="fa fa-user"></i>
                </span>
              </div>
              {{-- <img src="{{ asset('images/img.jpg') }}" alt="..." class="img-circle profile_img"> --}}
            </div>
            <div class="profile_info">
              <h2>{{Session::get('username')}}</h2>
              <span>{{ucwords(Session::get('role'))}}</span>
            </div>
          </div>
          <!-- /menu profile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
              <h3>General</h3>
              <ul class="nav side-menu">
                @if(Session::get('role') !== 'supervisor' && Session::get('role') !== 'manager')
                <li><a href="{{url('/dashboard/proyek')}}"><i class="fa fa-institution"></i> Proyek <span class="fa fa-chevron-right"></span></a></li>
                <li><a href="{{url('/dashboard/bukukas')}}"><i class="fa fa-book"></i> Buku Kas <span class="fa fa-chevron-right"></span></a>
                <ul class="nav child_menu">
                  <li><a href="{{url('/dashboard/bukukas')}}">Buku Kas</a></li>
                  <li><a href="{{url('/dashboard/kategori')}}">Kategori</a></li>
                </ul>
                </li>
                @endif
                @if(Session::get('role') !== 'operator')
                <li><a><i class="fa fa-list-alt"></i> Penggajian <span class="fa fa-chevron-right"></span></a>
                <ul class="nav child_menu">
                  @if(Session::get('role') !== 'admin')
                  <li><a href="{{url('/dashboard/tukang_mandor')}}">Tukang dengan Mandor</a></li>
                  <li><a href="{{url('/dashboard/tukang_harian')}}">Tukang Harian</a></li>
                  @endif
                  @if(Session::get('role') !== 'supervisor' && Session::get('role') !== 'manager')
                  <li><a href="{{url('/dashboard/tukang_borongan')}}">Tukang Borongan</a></li>
                  @endif
                </ul>
                </li>
                @if(Session::get('role') !== 'admin')
                <li><a><i class="fa fa-gear"></i> Pengaturan Gaji <span class="fa fa-chevron-right"></span></a>
                <ul class="nav child_menu">
                  <li><a href="{{url('/dashboard/pengaturan_tunjangan')}}">Tunjangan</a></li>
                  <li><a href="{{url('/dashboard/gaji_mandor')}}">Gaji per Mandor</a></li>
                  <li><a href="{{url('/dashboard/daftar_mandor')}}">Daftar Mandor</a></li>
                  <li><a href="{{url('/dashboard/daftar_tukang')}}">Daftar Tukang</a></li>
                </ul>
                </li>
                @endif
                @endif
                @if(Session::get('role') !== 'supervisor' && Session::get('role') !== 'manager')
                <li><a href="{{url('/dashboard/stok')}}"><i class="fa fa-dropbox"></i> Stok <span class="fa fa-chevron-right"></span></a></li>
                <li><a href="{{url('/dashboard/invoice')}}"><i class="fa fa-sticky-note"></i> Invoice <span class="fa fa-chevron-right"></span></a></li>
                @endif
                @if(Session::get('role') === 'owner')
                <li><a href="{{url('/dashboard/user')}}"><i class="fa fa-user"></i> User <span class="fa fa-chevron-right"></span></a></li>
                @endif
                <li><a href="{{url('/logout')}}"><i class="fa fa-sign-out"></i> Logout <span class="fa fa-chevron-right"></span></a></li>
                {{-- <li><a><i class="fa fa-edit"></i> Forms <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="form.html">General Form</a></li>
                    <li><a href="form_advanced.html">Advanced Components</a></li>
                    <li><a href="form_validation.html">Form Validation</a></li>
                    <li><a href="form_wizards.html">Form Wizard</a></li>
                    <li><a href="form_upload.html">Form Upload</a></li>
                    <li><a href="form_buttons.html">Form Buttons</a></li>
                  </ul>
                </li> --}}
              </ul>
            </div>
          </div>
          <!-- /sidebar menu -->

          <!-- /menu footer buttons -->
          {{-- <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
              <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
              <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
              <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
              <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
          </div> --}}
          <!-- /menu footer buttons -->
        </div>
      </div>

      <!-- top navigation -->
      <div class="top_nav no-print">
        <div class="nav_menu no-print">
          <div class="nav toggle py-3">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
          </div>
          <nav class="nav navbar-nav">
            <div class="navbar-right">
              <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                  <form action="{{ url('/dashboard/filter') }}" method="post">
                    @csrf
                    <div class="form-group row align-items-center" style="margin-bottom: 0">
                      <label class="col-form-label col-md-3 col-sm-3 col-3">Tahun</label>
                      <div class="col-md-6 col-sm-6 col-6">
                        <input type="text" readonly class="form-control b-yearpicker" name="tahun"
                        value={{ old('tahun') ?? Session::get('tahun') ?? '-' }}>
                      </div>
                      <div class="col-3" style="padding: 0">
                        <input type="submit" name="submit" value="Go" class="btn btn-success btn-sm text-sm" style="margin-bottom: 0; margin-right: 0">
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </nav>
        </div>
      </div>
      <!-- /top navigation -->

      @yield('halaman_admin')

      <!-- footer content -->
      <footer class="no-print">
        <div class="pull-right">
          {{-- Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a> --}}
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /footer content -->
    </div>
  </div>
  <x:notify-messages />

  <!-- jQuery -->
  <script src="{{ asset('/vendors/jquery/dist/jquery.min.js') }}"></script>
  <!-- Bootstrap -->
  <script src="{{ asset('/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <!-- bootstrap-progressbar -->
  <script src="{{ asset('/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
  <!-- DateJS -->
  <script src="{{ asset('/vendors/DateJS/build/date.js') }}"></script>
  <!-- JQVMap -->
  <script src="../vendors/jqvmap/dist/jquery.vmap.js"></script>
  <script src="../vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
  <script src="../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="{{ asset('/vendors/moment/min/moment.min.js') }}"></script>
  <script src="{{ asset('/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
  <script src="{{ asset('/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>

  <!-- Custom Theme Scripts -->
  <script src="{{ asset('/assets/js/custom2.js') }}"></script>
  <script src="{{ asset('/assets/js/custom.js') }}"></script>
  @notifyJs

</body>

</html>
