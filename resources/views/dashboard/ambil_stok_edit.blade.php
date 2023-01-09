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
                <h3>Edit Ambil Stok</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{ url('/dashboard/bukukas') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{ url('/dashboard/ambil_stok_update') }}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Proyek</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="hidden" name="id" value={{$ambil->bukukas}}>
                    <select id="role" class="form-control" name="proyek">
                      @foreach ($proyek as $p)
                        <option value="{{ $p->id }}" @if(old('proyek') ? old('proyek') == $p->id : $ambil->proyek == $p->id) selected @endif>{{ $p->nama }}</option>
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
                    <input type="text" readonly class="form-control b-datepicker" style="background: transparent" placeholder="Masukkan Kode Proyek" name="tanggal"
                      value={{old('tanggal') ? $carbon::parse(old('tanggal'))->format('d-M-Y') : $carbon::parse($ambil->tanggal)->format('d-M-Y')}}>
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
                        <option value="{{ $k->id }}" @if(old('kategori') ? old('kategori') == $k->id : $ambil->kategori == $k->id) selected @endif>{{$k->nama }}</option>
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
                    <select id="role" class="form-control" name="stok" readonly style="background: transparent">
                        <option value="{{ $stok->id }}">{{ $stok->barang . ' - ' . $stok->kuantitas + $ambil->kuantitas . ' ' . $stok->satuan }}</option>
                    </select>
                    @error('stok')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Jumlah</label>
                  <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" placeholder="Masukkan Jumlah Ambil Stok" name="kuantitas" value={{$ambil->kuantitas}} step="0.1">
                    <small class="warning">Note: Jumlah tidak boleh melebihi stok dan dapat diisi angka desimal maksimal 1 digit.</small>
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
