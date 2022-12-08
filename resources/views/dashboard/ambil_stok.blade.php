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
                <h3>Ambil Stok</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{ url('/dashboard/bukukas') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{ url('/dashboard/ambil_stok_aksi') }}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Proyek</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select id="role" class="form-control" name="proyek">
                      @foreach ($proyek as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
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
                    <input type="text" readonly class="form-control" placeholder="Masukkan Kode Proyek" name="tanggal"
                      value={{date_format(now(), 'd-M-Y')}}>
                    @error('tanggal')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Kategori</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select id="role" class="form-control" name="kategori">
                      @foreach ($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                      @endforeach
                    </select>
                    @error('kategori')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Stok</label>
                  <div class="col-md-9 col-sm-9 ">
                    <select id="role" class="form-control" name="stok">
                      @foreach ($stok as $s)
                        <option value="{{ $s->id }}">{{ $s->barang . ' - ' . $s->kuantitas . ' ' . $s->satuan }}</option>
                      @endforeach
                    </select>
                    @error('stok')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Jumlah</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" placeholder="Masukkan Jumlah Ambil Stok" name="kuantitas">
                    <small>*Jumlah tidak boleh melebihi stok</small>
                    @error('kuantitas')
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
