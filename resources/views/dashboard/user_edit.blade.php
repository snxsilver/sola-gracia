@extends('dashboard.header');

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12">
        <div class="x_panel">

          <div class="x_title">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3>Edit User</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{url('/dashboard/user')}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{url('/dashboard/user_update')}}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Username</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Username" name="username" value="{{old('username') ?? $user->username}}">
                    <input type="hidden" class="form-control" placeholder="Masukkan Username" name="id" value="{{$user->id}}">
                    @error('username')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Password</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="password" class="form-control" placeholder="Masukkan Password" name="password">
                    <span class="unhide-pass up-2"><i class="fa fa-eye"></i></span>
                    @error('password')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Konfirmasi Password</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="password" class="form-control" placeholder="Masukkan Konfirmasi Password" name="confirm_password">
                    <span class="unhide-pass up-2"><i class="fa fa-eye"></i></span>
                    @error('confirm_password')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Role</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select id="role" class="form-control" required name="role">
                      <option value="admin" @if($user->role === "admin") selected @endif>Admin</option>
                      <option value="manager" @if($user->role === "manager") selected @endif>Manager</option>
                      <option value="operator" @if($user->role === "operator") selected @endif>Operator</option>
                      <option value="owner" @if($user->role === "owner") selected @endif>Owner</option>
                      <option value="supervisor" @if($user->role === "supervisor") selected @endif>Supervisor</option>
                    </select>
                    @error('role')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group row">
                  <div class="col-md-9 col-sm-9  offset-md-3">
                    <button type="submit" class="btn btn-success">Simpan</button>
                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->
@endsection
