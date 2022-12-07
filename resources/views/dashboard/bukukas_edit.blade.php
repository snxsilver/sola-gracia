@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon')

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
          <div class="x_title">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3>Edit Transaksi Buku Kas</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{ url('/dashboard/bukukas') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{ url('/dashboard/bukukas_update') }}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Proyek</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="hidden" name="id" value="{{ $bukukas->id }}">
                    <select id="role" class="form-control" required name="proyek">
                      @foreach ($proyek as $p)
                        <option value="{{ $p->id }}" @if ($bukukas->proyek === $p->id) selected @endif>
                          {{ $p->nama }}</option>
                      @endforeach
                    </select>
                    @error('proyek')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tanggal</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" readonly class="form-control b-datepicker" placeholder="Masukkan Kode Proyek"
                      name="tanggal" value={{ $carbon->parse($bukukas->tanggal)->format('d-M-Y') }}>
                    @error('tanggal')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Keterangan</label>
                  <div class="col-md-9 col-sm-9 ">
                    <textarea name="keterangan" class="form-control" placeholder="Masukkan Keterangan" id="" cols="30"
                      rows="3">{{ $bukukas->keterangan }}</textarea>
                    {{-- <input type="text" class="form-control" placeholder="Masukkan Keterangan" name="keterangan"> --}}
                    @error('keterangan')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Kategori</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select id="role" class="form-control" required name="kategori">
                      @foreach ($kategori as $k)
                        <option value="{{ $k->id }}" @if ($bukukas->kategori === $k->id) selected @endif>
                          {{ $k->nama }}</option>
                      @endforeach
                    </select>
                    @error('kategori')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">No Bukti</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" placeholder="Masukkan No Bukti" name="bukti"
                      value="{{ $bukukas->no_bukti ?? '' }}">
                    @error('bukti')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Masuk</label>
                  <div class="col-md-9 col-sm-9 ">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="number" class="form-control" placeholder="Masukkan Uang Masuk" name="masuk"
                        value={{ $bukukas->masuk ?? '' }}>
                    </div>
                    @error('masuk')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Keluar</label>
                  <div class="col-md-9 col-sm-9 ">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="number" class="form-control" placeholder="Masukkan Uang Keluar" name="keluar"
                        value={{ $bukukas->keluar ?? '' }}>
                    </div>
                    @error('keluar')
                      <small>*{{ $message }}</small>
                    @enderror
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
