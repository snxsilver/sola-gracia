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
                <h3>Tambah Invoice</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{url('/dashboard/invoice')}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{url('/dashboard/invoice_aksi')}}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Faktur Pajak</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Faktur Pajak" name="faktur_pajak">
                    @error('faktur_pajak')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tanggal</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" readonly class="form-control b-datepicker" placeholder="Masukkan Tanggal" name="tanggal" value={{date('d-M-Y', strtotime(now()))}}>
                    @error('tanggal')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tanggal Jatuh Tempo</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" readonly class="form-control b-datepicker" placeholder="Masukkan Tanggal Jatuh Tempo" name="tanggal_jatuh_tempo">
                    @error('tanggal_jatuh_tempo')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nama Perusahaan</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Nama Perusahaan" name="nama_perusahaan">
                    @error('nama_perusahaan')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Alamat</label>
                  <div class="col-md-9 col-sm-9 ">
                    <textarea name="alamat" class="form-control" placeholder="Masukkan Alamat Perusahaan" id="" cols="30" rows="3"></textarea>
                    {{-- <input type="text" class="form-control" placeholder="Masukkan Alamat Perusahaan" name="alamat"> --}}
                    @error('alamat')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nomor Telepon</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Nomor Telepon Perusahaan" name="telp">
                    @error('telp')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">NPWP</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan NPWP Perusahaan" name="npwp">
                    @error('npwp')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">DP</label>
                  <div class="col-md-9 col-sm-9 ">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="number" class="form-control" placeholder="Masukkan DP" name="dp">
                    </div>
                    @error('dp')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                {{-- <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Subtotal</label>
                  <div class="col-md-9 col-sm-9 ">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="number" class="form-control" placeholder="Masukkan Subtotal" name="subtotal">
                    </div>
                    @error('subtotal')<small>*{{$message}}</small>@enderror
                  </div>
                </div> --}}
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Total</label>
                  <div class="col-md-9 col-sm-9 ">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="number" class="form-control" placeholder="Masukkan Total Invoice" name="total">
                    </div>
                    @error('total')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Keterangan</label>
                  <div class="col-md-9 col-sm-9 ">
                    <textarea name="keterangan" class="form-control" placeholder="Masukkan Keterangan" id="" cols="30" rows="3"></textarea>
                    {{-- <input type="text" class="form-control" placeholder="Masukkan Keterangan" name="keterangan"> --}}
                    @error('keterangan')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Proyek</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select name="proyek" class="form-control" id="">
                      @foreach ($proyek as $p)
                        <option value={{ $p->id }} @if (Session::get('proyek') == $p->id) selected @endif>
                          {{ $p->nama }}</option>
                      @endforeach
                    </select>
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
