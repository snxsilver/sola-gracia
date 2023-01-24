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
                <h3>Tambah Tukang dari Mandor Lain</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{ url('/dashboard/tukang_mandor') }}" class="btn btn-primary"><i
                      class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{ url('/dashboard/tukang_mandor_aksi_b') }}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Mandor</label>
                  <div class="col-md-9 col-sm-9">
                    <input type="hidden" name="mandor" value="{{$mandor}}">
                    <select class="form-control" id="" disabled style="background: transparent">
                      @php($nama = DB::table('mandor')->where('id',$mandor)->first())
                        <option value={{ $mandor }}>{{ $nama->nama }}</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tanggal</label>
                  <div class="col-md-9 col-sm-9">
                    <div class="d-flex align-items-center">
                      <input type="hidden" name="tanggal" value="{{$carbon->parse($tanggal)->format('d-M-Y')}}">
                      <input type="text" class="form-control bo-datepicker" disabled style="background: transparent"
                        value="{{$carbon->parse($tanggal)->format('d-M-Y')}}">
                    </div>
                  </div>
                </div>
                <table class="table">
                  <thead>
                    <tr>
                      <th width="10%"><input type="checkbox" name="cekall"></th>
                      <th>Nama Mandor</th>
                      <th>Nama Tukang</th>
                      <th>Alamat</th>
                      <th>No HP</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php($no = 1)
                    @foreach ($tukang as $t)
                      <tr>
                        <td><input type="checkbox" name="tukang[]" value="{{$t->id}}"></td>
                        @if($t->mandor !== 0)
                        @php($mandor_a = DB::table('mandor')->where('id',$t->mandor)->first())
                        <td>{{ $mandor_a->nama }}</td>
                        @else
                        <td>Tukang Harian</td>
                        @endif
                        <td>{{ $t->nama }}</td>
                        <td>{{ $t->alamat }}</td>
                        <td>{{ $t->hp }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="form-group row">
                  <div class="col d-flex justify-content-end">
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
  </div>
  <!-- /page content -->
@endsection
