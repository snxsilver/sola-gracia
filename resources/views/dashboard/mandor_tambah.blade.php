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
                <h3>Tambah Mandor</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{ url('/dashboard/daftar_mandor') }}" class="btn btn-primary"><i
                      class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{ url('/dashboard/daftar_mandor_aksi') }}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nama<span class="x-alert">*</span></label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Nama Mandor" name="nama"
                      value="{{ old('nama') }}">
                    @error('nama')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Alamat</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input name="alamat" class="form-control" placeholder="Masukkan Alamat Mandor" value="{{ old('alamat') }}">
                    @error('alamat')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nomor HP</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Nomor HP Mandor"
                      name="telp" value="{{ old('telp') }}">
                    @error('telp')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Supervisor</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select name="supervisor" class="form-control" id="">
                      @foreach ($user as $u)
                        <option value={{ $u->id }} @if (old('supervisor') == $u->id) selected @endif>
                          {{ $u->username }}</option>
                      @endforeach
                    </select>
                    <small>
                      Note: *wajib diisi.
                    </small>
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
