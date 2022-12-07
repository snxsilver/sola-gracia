@extends('dashboard.header');

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12">
        <div class="x_panel">
          <div class="x_title filter_toggler d-flex justify-content-between" style="cursor: pointer">
            <span>Filter</span>
            <span><i class="fa fa-chevron-down"></i></span>
          </div>
          <div class="x_content filter_toggle d-none">
            <form action="{{ url('/dashboard/filter') }}" method="post">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Kategori</label>
                    <div class="col-md-9 col-sm-9 ">
                      <select name="kategori" class="form-control" id="">
                        <option value="" @if(!Session::get('kategori')) selected @endif>Semua Kategori</option>
                        @foreach ($kategori as $k)
                          <option value={{ $k->id }} @if(Session::get('kategori')) selected @endif>{{ $k->nama }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mt-md-0 mt-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Proyek</label>
                    <div class="col-md-9 col-sm-9 ">
                      <select name="proyek" class="form-control" id="">
                        <option value="" @if(!Session::get('kategori')) selected @endif>Semua Proyek</option>
                        @foreach ($proyek as $p)
                          <option value={{ $p->id }} @if(Session::get('proyek')) selected @endif>{{ $p->nama }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-1">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Start</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="date" class="form-control" name="start" value={{ now() }}>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mt-md-0 mt-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Selesai</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="date" class="form-control" name="selesai" value={{ now() }}>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex mt-1 justify-content-between">
                <div class=""></div>
                <button type="submit" class="btn btn-success btn-sm text-sm">Filter</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
          <div class="x_title">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3>Buku Kas</h3>
                <div class="align-items-center">
                  <a class="btn btn-success text-sm btn-sm fw-semibold" href="{{ url('/dashboard/ambil_stok') }}"><span
                      class="text-white mr-1"><i class="fa fa-plus"></i></span>Ambil Stok</a>
                  <a class="btn btn-primary" href="{{ url('/dashboard/bukukas_tambah') }}"><i class="fa fa-plus"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th>Proyek</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Kategori</th>
                    <th>No Bukti</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @php($no = 1)
                  @foreach ($bukukas as $b)
                    <tr>
                      <td width="5%">{{ $no++ }}</td>
                      <td>{{ $b->namaproyek }}</td>
                      <td>{{ $b->tanggal }}</td>
                      <td style="white-space: pre-line" width="20%">{{ $b->keterangan }}</td>
                      <td>{{ $b->namakategori }}</td>
                      <td>{{ $b->no_bukti ?? '-' }}</td>
                      <td>{{ $b->masuk ?? '-' }}</td>
                      <td>{{ $b->keluar ?? '-' }}</td>
                      <td width="10%">
                        @if ($b->ambil_stok == 0)
                          <a class="btn btn-sm btn-secondary" href="{{ url('/dashboard/bukukas_edit/' . $b->id) }}"><i
                              class="fa fa-pencil"></i></a>
                          <a class="btn btn-sm btn-danger" href="{{ url('/dashboard/bukukas_hapus/' . $b->id) }}"><i
                              class="fa fa-trash"></i></a>
                        @else
                          <a class="btn btn-sm btn-secondary" href="{{ url('/dashboard/ambil_stok_edit/' . $b->id) }}"><i
                              class="fa fa-pencil"></i></a>
                          <a class="btn btn-sm btn-danger" href="{{ url('/dashboard/ambil_stok_hapus/' . $b->id) }}"><i
                              class="fa fa-trash"></i></a>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                  <tr style='background: #c0c0c0'>
                    <td colspan="6" class="sum text-center">JUMLAH</td>
                    <td class="sum">MASUK</td>
                    <td class="sum">KELUAR</td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->
@endsection
