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
                <h3>Tambah Pengaturan Gaji Mandor</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{ url('/dashboard/gaji_mandor') }}" class="btn btn-primary"><i
                      class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{ url('/dashboard/gaji_mandor_aksi') }}"
                method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Mandor</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select name="mandor" class="form-control" id="">
                      @foreach($mandor as $m)
                        <option value="{{$m->id}}" @if (old('mandor') == $m->id) selected @endif>{{$m->nama}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Level</label>
                  <div class="col-md-9 col-sm-9">
                    <input type="text" class="form-control" placeholder="Masukkan Nama Level" name="level"
                      value="{{ old('level') }}">
                    @error('level')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Gaji Pokok</label>
                  <div class="col-md-9 col-sm-9">
                    <input type="number" class="form-control" placeholder="Masukkan Nominal Gaji Pokok" name="pokok"
                      value="{{ old('pokok') }}">
                    @error('pokok')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Uang Lembur</label>
                  <div class="col-md-9 col-sm-9">
                    <input type="number" class="form-control" placeholder="Masukkan Nominal Uang Lembur" name="lembur"
                      value="{{ old('lembur') }}">
                    @error('lembur')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group row">
                  <div class="col-md-9 col-sm-9 offset-md-3">
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
