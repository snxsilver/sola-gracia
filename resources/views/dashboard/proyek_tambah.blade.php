@extends('dashboard.header');

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
          <div class="x_title">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3>Tambah Proyek</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{url('/dashboard/proyek')}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{url('/dashboard/proyek_aksi')}}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Kode</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Kode Proyek" name="kode">
                    @error('kode')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nama</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Nama Proyek" name="nama">
                    @error('nama')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nilai</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" placeholder="Masukkan Nilai Proyek" name="nilai">
                    @error('nilai')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                @if(Session::get('role') == "owner")
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Pajak</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select id="role" class="form-control" name="pajak">
                      <option value=1>Ya</option>
                      <option value=null>Tidak</option>
                    </select>
                    @error('role')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                @endif
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
