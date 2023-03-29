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
                <h3>Tambah Transaksi Buku Kas</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{url('/dashboard/bukukas')}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{url('/dashboard/bukukas_aksi')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Proyek</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select id="role" class="form-control" required name="proyek">
                      @foreach($proyek as $p)
                      <option value="{{$p->id}}" @if (old('proyek') == $p->id) selected @endif>{{$p->nama}}</option>
                      @endforeach
                    </select>
                    @error('proyek')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tanggal</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" readonly style="background: transparent" class="form-control b-datepicker" placeholder="Masukkan Kode Proyek" name="tanggal" value={{old('tanggal') ?? date_format(now(), 'd-M-Y')}}>
                    @error('tanggal')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Uraian</label>
                  <div class="col-md-9 col-sm-9 ">
                    <textarea name="uraian" class="form-control" placeholder="Masukkan Uraian" id="" cols="30" rows="3">{{old('uraian')}}</textarea>
                    @error('uraian')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Keterangan</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Keterangan" name="keterangan"  value="{{old('keterangan')}}">
                    <small class="warning">(opsional)</small>
                    @error('keterangan')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Kategori</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select id="role" class="form-control" required name="kategori">
                      @foreach($kategori as $k)
                      <option value="{{$k->id}}" @if (old('kategori') == $k->id) selected @endif>{{$k->nama}}</option>
                      @endforeach
                    </select>
                    @error('kategori')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Masuk</label>
                  <div class="col-md-9 col-sm-9 ">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="number" class="form-control" placeholder="Masukkan Uang Masuk" name="masuk" value="{{old('masuk')}}">
                    </div>
                    <small class="warning">Note: Uang masuk harus berisi angka bulat bukan desimal.</small>
                    @error('masuk')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Keluar</label>
                  <div class="col-md-9 col-sm-9 ">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="number" class="form-control" placeholder="Masukkan Uang Keluar" name="keluar" value="{{old('keluar')}}">
                    </div>
                    <small class="warning">Note: Uang keluar harus berisi angka bulat bukan desimal.</small>
                    @error('keluar')<small>*{{$message}}</small>@enderror
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
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nomor Nota</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Nomor Nota" name="no_nota"  value="{{old('no_nota')}}">
                    <small class="warning">(harus diisi apabila ada nota)</small>
                    @error('no_nota')<small>*{{$message}}</small>@enderror
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
