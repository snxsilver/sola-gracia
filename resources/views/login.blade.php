<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Login | CV. Sola Gracia</title>

  <!-- Bootstrap -->
  <link href="{{ asset('/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="{{ asset('/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
  <!-- bootstrap-progressbar -->
  <link href="{{ asset('/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="{{ asset('/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="{{ asset('/assets/css/custom.css') }}" rel="stylesheet">
</head>

<body class="login">
  <div>
    <div class="login_wrapper pt-12">
      <div class="animate form login_form">
        <section class="login_content">
          <form method="post" action="{{ url('/login_aksi') }}">
            @csrf
            <h1>Login Form</h1>
            @if (!Session::get('errors'))
              @if (isset($alert))
                {!! "<small class='error'>" . $alert . '</small>' !!}
              @endif
            @endif
            @error('message')
              <small class="error">*{{ $message }}</small>
            @enderror
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Username" name="username" autocomplete="off" />
            </div>
            <div class="form-group">
              <input type="password" class="form-control" placeholder="Password" name="password" />
            </div>
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-success">Login</button>
            </div>

            <div class="clearfix"></div>

            <div class="separator">
              {{-- <p class="change_link">New to site?
                  <a href="#signup" class="to_register"> Create Account </a>
                </p> --}}

              <div class="clearfix"></div>
              <br />

              <div>
                <h1>CV. Sola Gracia</h1>
                <p>©2022 All Rights Reserved.</p>
              </div>
            </div>
          </form>
        </section>
      </div>
    </div>
  </div>
</body>

</html>
