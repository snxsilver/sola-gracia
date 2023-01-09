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
                <h3>Edit Invoice</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{url('/dashboard/invoice')}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{url('/dashboard/invoice_update')}}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Faktur Pajak</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="hidden" name="id" value={{$invoice->id}}>
                    <input type="text" class="form-control" placeholder="Masukkan Faktur Pajak" name="faktur_pajak" value="{{old('faktur_pajak') ?? $invoice->faktur_pajak}}">
                    <small class="warning">Note: Wajib diisi apabila menggunakan pajak</small>
                    @error('faktur_pajak')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tanggal</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" readonly style="background: transparent" class="form-control b-datepicker" placeholder="Masukkan Tanggal" name="tanggal"
                    value={{ old('tanggal') ? $carbon->parse(old('tanggal'))->format('d-M-Y') : $carbon->parse($invoice->tanggal)->format('d-M-Y') }}>
                    @error('tanggal')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tanggal Jatuh Tempo</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" readonly style="background: transparent" class="form-control b-datepicker" placeholder="Masukkan Tanggal Jatuh Tempo" name="tanggal_jatuh_tempo"
                    value={{ old('tanggal_jatuh_tempo') ? $carbon->parse(old('tanggal_jatuh_tempo'))->format('d-M-Y') : ($invoice->tanggal_jatuh_tempo ? $carbon->parse($invoice->tanggal_jatuh_tempo)->format('d-M-Y') : '') }}>
                    @error('tanggal_jatuh_tempo')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nama Perusahaan</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Nama Perusahaan (Wajib diisi)" name="nama_perusahaan" value="{{old('nama_perusahaan') ?? $invoice->nama_perusahaan}}">
                    <small class="warning">Note: Wajib diisi.</small>
                    @error('nama_perusahaan')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Alamat</label>
                  <div class="col-md-9 col-sm-9 ">
                    <textarea name="alamat" class="form-control" placeholder="Masukkan Alamat Perusahaan" id="" cols="30" rows="3">{{old('alamat') ?? $invoice->alamat}}</textarea>
                    @error('alamat')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Nomor Telepon</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Nomor Telepon Perusahaan" name="telp" value="{{old('telp') ?? $invoice->telp}}">
                    @error('telp')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">NPWP</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan NPWP Perusahaan" name="npwp" value="{{old('npwp') ?? $invoice->npwp}}">
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
                      <input type="number" class="form-control" placeholder="Masukkan DP" name="dp" value={{old('dp') ?? $invoice->dp}}>
                    </div>
                    <small class="warning">Note: DP harus berupa angka bulat bukan desimal.</small>
                    @error('dp')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Total</label>
                  <div class="col-md-9 col-sm-9 ">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="number" class="form-control" placeholder="Masukkan Total Invoice (Wajib diisi)" name="total" value={{old('total') ?? $invoice->total}}>
                    </div>
                    <small class="warning">Note: Wajib diisi dengan total pembayaran harus berupa angka bulat bukan desimal.</small>
                    @error('total')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Keterangan</label>
                  <div class="col-md-9 col-sm-9 ">
                    <textarea name="keterangan" class="form-control" placeholder="Masukkan Keterangan (Wajib diisi)" id="" cols="30" rows="3">{{old('keterangan') ?? $invoice->keterangan}}</textarea>
                    <small class="warning">Note: Wajib diisi.</small>
                    @error('keterangan')<small>*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Proyek</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select name="proyek" class="form-control" id="">
                      @foreach ($proyek as $p)
                        <option value={{ $p->id }} @if (old('proyek') ? old('proyek') == $p->id : $invoice->proyek == $p->id) selected @endif>
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
