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
                <h3>Daftar Stok</h3>
                @if (Session::get('role') !== 'operator')
                <a class="btn btn-primary" href="{{ url('/dashboard/stok_tambah') }}"><i class="fa fa-plus"></i></a>
                @endif
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Kuantitas</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>No Bukti</th>
                    @if (Session::get('role') === 'owner')
                    <th>Opsi</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @php($no = 1)
                  @foreach ($stok as $s)
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ $carbon::parse($s->tanggal)->format('d M Y') }}</td>
                      <td>{{ $s->barang }}</td>
                      <td>{{ $s->kuantitas }}</td>
                      <td>{{ $s->satuan }}</td>
                      <td>{{ 'Rp '.number_format($s->harga) }}</td>
                      <td>{{ $s->no_bukti }}</td>
                      @if (Session::get('role') === 'owner')
                      <td>
                        <a class="btn btn-sm btn-secondary" href="{{ url('/dashboard/stok_edit/' . $s->id) }}"><i
                            class="fa fa-pencil"></i></a>
                        <a class="btn btn-sm btn-danger" href="{{ url('/dashboard/stok_hapus/' . $s->id) }}"><i
                            class="fa fa-trash"></i></a>
                      </td>
                      @endif
                    </tr>
                  @endforeach
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
