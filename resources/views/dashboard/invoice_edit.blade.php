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
                  <label class="col-form-label col-md-3 col-sm-3 ">Nama Perusahaan<span class="x-alert">*</span></label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan Nama Perusahaan (Wajib diisi)" name="nama_perusahaan" value="{{old('nama_perusahaan') ?? $invoice->nama_perusahaan}}">
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
                  <label class="col-form-label col-md-3 col-sm-3 ">Detail Pembayaran</label>
                  <div class="col-md-9 col-sm-9">
                    <div class="invoice-field">
                      <div class="row">
                        <div class="col-8">
                          <label class="col-form-label">Keterangan<span class="x-alert">*</span></label>
                        </div>
                        <div class="col-4">
                          <label class="col-form-label">Nominal</label>
                        </div>
                      </div>
                      @php($x = 0)
                      @php($detail = DB::table('invoice_detail')->where('invoice', $invoice->id)->get())
                      @if(old('keterangan'))
                      @for($x; $x < count(old('keterangan')); $x++)
                      <div class="row @if($x !== 0) mt-3 @endif">
                        <div class="col-8">
                          <input type="hidden" name="id_detail" value="{{old('id_detail')}}">
                          <textarea name="keterangan[]" class="form-control" placeholder="Masukkan Keterangan" id=""
                          cols="30" rows="3">{{old('keterangan.'.$x)}}</textarea>
                          @error('keterangan.'.$x)
                          <small>*{{$message}}</small>
                          @enderror
                        </div>
                        <div class="col-4">
                          <div class="d-flex align-items-center">
                            <div class="input-group" style="margin-bottom: 0">
                              <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                              </div>
                              <input type="number" class="form-control" placeholder="Masukkan Nominal Pembayaran" name="nominal[]" value="{{old('nominal.'.$x)}}">
                            </div>
                            @if($x == count(old('keterangan')) - 1)
                            <button type="button" class="btn btn-primary ml-2 tambah-invoice" style="margin: 0"><i class="fa fa-plus"></i></button>
                            @else
                            <button type="button" class="btn btn-danger ml-2 hapus-invoice" style="margin: 0"><i class="fa fa-minus"></i></button>
                            @endif
                          </div>
                          @error('nominal.'.$x)
                          <small>*{{$message}}</small>
                          @enderror
                        </div>
                      </div>
                      @endfor
                      @elseif(count($detail) > 1)
                      @foreach($detail as $d)
                      <div class="row @if($x !== 0) mt-3 @endif">
                        <div class="col-8">
                          <input type="hidden" name="id_detail[]" value="{{$d->id}}">
                          <textarea name="keterangan[]" class="form-control" placeholder="Masukkan Keterangan" id=""
                          cols="30" rows="3">{{$d->keterangan}}</textarea>
                        </div>
                        <div class="col-4">
                          <div class="d-flex align-items-center">
                            <div class="input-group" style="margin-bottom: 0">
                              <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                              </div>
                              <input type="number" class="form-control" placeholder="Masukkan Nominal Pembayaran" name="nominal[]" value="{{$d->nominal}}">
                            </div>
                            @if($x == count($detail) - 1)
                            <button type="button" class="btn btn-primary ml-2 tambah-invoice" style="margin: 0"><i class="fa fa-plus"></i></button>
                            @else
                            <button type="button" class="btn btn-danger ml-2 hapus-invoice" style="margin: 0"><i class="fa fa-minus"></i></button>
                            @endif
                          </div>
                        </div>
                      </div>
                      @php($x++)
                      @endforeach
                      @else
                      <div class="row">
                        <div class="col-8">
                          <textarea name="keterangan[]" class="form-control" placeholder="Masukkan Keterangan" id=""
                          cols="30" rows="3">{{$invoice->keterangan}}</textarea>
                        </div>
                        <div class="col-4">
                          <div class="d-flex align-items-center">
                            <div class="input-group" style="margin-bottom: 0">
                              <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                              </div>
                              <input type="number" class="form-control" placeholder="Masukkan Nominal Pembayaran" name="nominal[]" value="{{$invoice->total}}">
                            </div>
                            <button type="button" class="btn btn-primary ml-2 tambah-invoice" style="margin: 0"><i class="fa fa-plus"></i></button>
                          </div>
                        </div>
                      </div>
                      @endif
                    </div>
                    <small class="warning ml-2">Note: Nominal pembayaran harus berupa angka bulat bukan desimal.</small>
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
