@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon')

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12">
        <div class="x_panel">
          <div class="x_title filter_toggler d-flex justify-content-between" style="cursor: pointer">
            <span style="color: black; font-weight: bold">Filter</span>
            <span style="color: black"><i class="fa fa-chevron-down"></i></span>
          </div>
          <div class="x_content">
            <div class="row">
              <div class="col-12">
                <form action="{{ url('/dashboard/search') }}">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="search">
                    <div class="input-group-append">
                      <button type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="x_content filter_toggle d-none" style="color: black">
            <form action="{{ url('/dashboard/filter') }}" method="post">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Kategori</label>
                    <div class="col-md-9 col-sm-9 ">
                      <select name="kategori" class="form-control" id="">
                        <option value="" @if (!Session::get('kategori')) selected @endif>Semua Kategori</option>
                        @foreach ($kategori as $k)
                          <option value={{ $k->id }} @if (Session::get('kategori') == $k->id) selected @endif>
                            {{ $k->nama }}</option>
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
                        <option value="" @if (!Session::get('proyek')) selected @endif>Semua Proyek</option>
                        @foreach ($proyek as $p)
                          <option value={{ $p->id }} @if (Session::get('proyek') == $p->id) selected @endif>
                            {{ $p->nama }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="ml-2" style="background: #c0c0c0; border-radius: 50px">
                  <button type="button"
                    class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 bg-primary trigger_date"
                    style="border-radius: 50px">Tanggal</button>
                  <button type="button" class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 trigger_month"
                    style="border-radius: 50px">Bulan</button>
                </div>
              </div>
              <div class="row mt-1 target_date">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Mulai</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="text" readonly class="form-control b-datepicker" name="mulai"
                        value={{ Session::get('mulai') ?? '-' }}>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mt-md-0 mt-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Selesai</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="text" readonly class="form-control b-datepicker" name="selesai"
                        value={{ Session::get('selesai') ?? '-' }}>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-1 target_month d-none">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Bulan</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="text" readonly class="form-control b-monthpicker" name="bulan" disabled
                        value={{ Session::get('bulan') ?? '-' }}>
                    </div>
                  </div>
                </div>
                {{-- <div class="col-md-6 mt-md-0 mt-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Selesai</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="date" class="form-control" name="selesai" value={{ now() }}>
                    </div>
                  </div>
                </div> --}}
              </div>
              <div class="d-flex mt-1 justify-content-between">
                <div class=""></div>
                <div class="">
                  <input type="submit" name="submit" value="Clear Filter" class="btn btn-secondary btn-sm text-sm">
                  <input type="submit" name="submit" value="Filter" class="btn btn-success btn-sm text-sm">
                </div>
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
                  @if (Session::get('role') === 'owner')
                    <a class="btn btn-secondary" href="{{ url('/dashboard/export') }}"><i
                        class="fa fa-file-excel-o"></i></a>
                  @endif
                  @if (Session::get('role') !== 'operator')
                    <a class="btn btn-success text-sm btn-sm fw-semibold" href="{{ url('/dashboard/ambil_stok') }}"><span
                        class="text-white mr-1"><i class="fa fa-plus"></i></span>Ambil Stok</a>
                    <a class="btn btn-primary" href="{{ url('/dashboard/bukukas_tambah') }}"><i
                        class="fa fa-plus"></i></a>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <div class="row">
                <div class="col-6">
                  <div class="d-flex justify-content-between">
                    <p style="color: black; font-weight:bold">Jumlah Uang Masuk :</p>
                    <p class="sum" style="color: black; font-weight:bold">{{ 'Rp ' . number_format($masuk) }}</p>
                  </div>
                  <div class="d-flex justify-content-between">
                    <p style="color: black; font-weight:bold">Jumlah Uang Keluar :</p>
                    <p class="sum" style="color: black; font-weight:bold">{{ 'Rp ' . number_format($keluar) }}</p>
                  </div>
                </div>
              </div>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="5%">No <a href="{{ url('/dashboard/bukukas_sort/clear') }}"><i
                          class="fa fa-refresh"></i></a></th>
                    <th>Proyek <a href="{{ url('/dashboard/bukukas_sort/proyek') }}"><i class="fa fa-sort"></i></a>
                    </th>
                    <th>Tanggal <a href="{{ url('/dashboard/bukukas_sort/tanggal') }}"><i class="fa fa-sort"></i></a>
                    </th>
                    <th>Keterangan</th>
                    <th>Kategori <a href="{{ url('/dashboard/bukukas_sort/kategori') }}"><i class="fa fa-sort"></th>
                    <th>No Bukti <a href="{{ url('/dashboard/bukukas_sort/bukti') }}"><i class="fa fa-sort"></th>
                    <th>Masuk <a href="{{ url('/dashboard/bukukas_sort/masuk') }}"><i class="fa fa-sort"></th>
                    <th>Keluar <a href="{{ url('/dashboard/bukukas_sort/keluar') }}"><i class="fa fa-sort"></th>
                    @if (Session::get('role') === 'owner')
                      <th>Opsi</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @php($no = 1)
                  @foreach ($bukukas as $b)
                    <tr>
                      <td width="5%">{{ $no++ }}</td>
                      <td>{{ $b->namaproyek }}</td>
                      <td>{{ $carbon::parse($b->tanggal)->format('d M Y') }}</td>
                      <td style="white-space: pre-line" width="20%">{{ $b->keterangan }}</td>
                      <td>{{ $b->namakategori }}</td>
                      <td>{{ $b->no_bukti ?? '-' }}</td>
                      <td>{{ $b->masuk ? 'Rp ' . number_format($b->masuk) : '-' }}</td>
                      <td>{{ $b->keluar ? 'Rp ' . number_format($b->keluar) : '-' }}</td>
                      @if (Session::get('role') === 'owner')
                        <td width="10%">
                          @if ($b->ambil_stok == 0)
                            <a class="btn btn-sm btn-secondary"
                              href="{{ url('/dashboard/bukukas_edit/' . $b->id) }}"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-sm btn-danger" href="{{ url('/dashboard/bukukas_hapus/' . $b->id) }}"><i
                                class="fa fa-trash"></i></a>
                          @else
                            <a class="btn btn-sm btn-secondary"
                              href="{{ url('/dashboard/ambil_stok_edit/' . $b->id) }}"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-sm btn-danger"
                              href="{{ url('/dashboard/ambil_stok_hapus/' . $b->id) }}"><i class="fa fa-trash"></i></a>
                          @endif
                        </td>
                      @endif
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="d-flex justify-content-center">
                {{ $bukukas->links() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->
@endsection
