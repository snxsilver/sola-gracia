@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon')

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3>Edit Stok</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{url('/dashboard/stok')}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{url('/dashboard/stok_update')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tanggal</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="hidden" name="id" value={{$stok->id}}>
                    <input type="text" style="background: transparent" readonly class="form-control b-datepicker" placeholder="Masukkan Tanggal Beli Stok" name="tanggal" value={{old('tanggal') ? $carbon::parse(old('tanggal'))->format('d-M-Y') : $carbon::parse($stok->tanggal)->format('d-M-Y')}}>
                    @error('tanggal')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nama Barang</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Nama Barang" name="barang" value="{{old('barang') ?? $stok->barang}}">
                    @error('barang')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Kuantitas</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" placeholder="Masukkan Jumlah Barang" name="kuantitas" value="{{old('kuantitas') ?? $stok->kuantitas}}" step="0.1">
                    <small class="warning">Note: Kuantitas dapat diisi angka desimal maksimal 1 digit.</small>
                    @error('kuantitas')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Satuan</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Satuan Jumlah Barang" name="satuan" value="{{old('satuan') ?? $stok->satuan}}">
                    @error('satuan')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Harga</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" placeholder="Masukkan Harga Barang" name="harga" value="{{old('harga') ?? $stok->harga}}">
                    <small class="warning">Note: Harga harus berisi angka bulat bukan desimal.</small>
                    @error('harga')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">No Bukti</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="textr" class="form-control" placeholder="Masukkan No Bukti Pembelian Barang (Opsional)" name="bukti" value="{{old('bukti') ?? $stok->no_bukti ?? ''}}">
                    @error('bukti')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nota</label>
                  <div class="col-md-9">
                    <input type="file" class="form-control-file" name="nota" id="imgload">
                    <small class="warning">(opsional)</small>
                    @error('nota')<small>{{$message}}</small>@enderror
                    @if($stok->nota)
                    <div class="my-2 ml-2">
                      <div class="d-flex align-items-center">
                        <input type="checkbox" name="d_nota" value="hapus" style="cursor: pointer">
                        <label for="d_nota" class="ml-2 hapus-nota" style="margin-bottom: 0; cursor: pointer">Hapus Nota</label>
                      </div>
                    </div>
                    @endif
                    <img src="{{$stok->nota ? asset('/images/nota/'.$stok->nota) : ''}}" alt="" id="imgshowa">
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
