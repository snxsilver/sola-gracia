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
                <h3>Tambah Stok</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{url('/dashboard/stok')}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{url('/dashboard/stok_aksi')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tanggal</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" readonly class="form-control b-datepicker" style="background: transparent" placeholder="Masukkan Tanggal Beli Stok" name="tanggal" value={{date_format(now(), 'd-M-Y')}}>
                    @error('tanggal')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nama Barang</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Nama Barang" name="barang" value="{{old('barang')}}">
                    @error('barang')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Kuantitas</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" placeholder="Masukkan Kuantitas" name="kuantitas" value="{{old('kuantitas')}}" step="0.1">
                    <small class="warning">Note: Kuantitas dapat diisi angka desimal maksimal 1 digit.</small>
                    @error('kuantitas')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Satuan</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Satuan Barang" name="satuan" value="{{old('satuan')}}">
                    @error('satuan')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Harga</label>
                  <div class="col-md-9 col-sm-9 ">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="number" class="form-control" placeholder="Masukkan Harga Barang" name="harga" value="{{old('harga')}}">
                    </div>
                    <small class="warning">Note: Harga harus berisi angka bulat bukan desimal.</small>
                    @error('harga')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">No Bukti</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="textr" class="form-control" placeholder="Masukkan No Bukti Pembelian Barang (Opsional)" name="bukti" value="{{old('bukti')}}">
                    @error('bukti')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nota</label>
                  <div class="col-md-9">
                    <input type="file" class="form-control-file" name="nota" id="imgload">
                    <small class="warning">(opsional)</small>
                    @error('nota')<small>{{$message}}</small>@enderror
                    <img src="" alt="" id="imgshowa">
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
