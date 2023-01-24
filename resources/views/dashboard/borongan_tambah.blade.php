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
                <h3>Tambah Gaji Tukang Borongan</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{ url('/dashboard/tukang_borongan') }}" class="btn btn-primary"><i
                      class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{ url('/dashboard/tukang_borongan_aksi') }}"
                method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Proyek</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select name="proyek" class="form-control" id="">
                      @foreach ($proyek as $p)
                        <option value={{ $p->id }} @if (old('proyek') == $p->id) selected @endif>
                          {{ $p->nama }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nama</label>
                  <div class="col-md-9 col-sm-9">
                    <input type="text" class="form-control" placeholder="Masukkan Nama Tukang" name="nama"
                      value="{{ old('nama') }}">
                    @error('nama')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Pembayaran</label>
                  <div class="col-md-9 col-sm-9 borongan-field">
                    <div class="row">
                      <div class="col-4">
                        <label class="col-form-label">Tanggal</label>
                      </div>
                      <div class="col-8">
                        <label class="col-form-label">Nominal</label>
                      </div>
                    </div>
                    @php($x = 0)
                    @if(old('tanggal'))
                    @for($x; $x < count(old('tanggal')) ; $x++)
                    <div class="row @if($x !== 0) mt-3 @endif">
                      <div class="col-4">
                        <input type="text" class="form-control bo-datepicker" name="tanggal[]" readonly style="background: transparent" value="{{old('tanggal.'. $x)}}">
                        @error('tanggal.'. $x)
                        <small>*{{ $message }}</small>
                        @enderror
                      </div>
                      <div class="col-8">
                        <div class="d-flex align-items-center">
                          <input type="number" class="form-control" name="nominal[]" value="{{old('nominal.'. $x)}}">
                          @if($x == count(old('tanggal')) - 1)
                          <button type="button" class="btn btn-primary ml-2 tambah-borongan" style="margin: 0"><i class="fa fa-plus"></i></button>
                          @else
                          <button type="button" class="btn btn-danger ml-2 hapus-borongan" style="margin: 0"><i class="fa fa-minus"></i></button>
                          @endif
                        </div>
                        @error('nominal.'. $x)
                        <small>*{{ $message }}</small>
                        @enderror
                      </div>
                    </div>
                    @endfor
                    @else
                    <div class="row">
                      <div class="col-4">
                        <input type="text" class="form-control bo-datepicker block" name="tanggal[]" readonly style="background: transparent" value="{{date_format(now(), 'd-M-Y')}}">
                      </div>
                      <div class="col-8">
                        <div class="d-flex align-items-center">
                          <input type="number" class="form-control" name="nominal[]">
                          <button type="button" class="btn btn-primary ml-2 tambah-borongan" style="margin: 0"><i class="fa fa-plus"></i></button>
                        </div>
                      </div>
                    </div>
                    @endif
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
