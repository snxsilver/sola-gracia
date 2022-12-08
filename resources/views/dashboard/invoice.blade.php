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
                <h3>Daftar Invoice</h3>
                <a class="btn btn-primary" href="{{ url('/dashboard/invoice_tambah') }}"><i class="fa fa-plus"></i></a>
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
                    <th>No Invoice</th>
                    <th>Total</th>
                    <th>Keterangan</th>
                    <th>Perusahaan</th>
                    <th>Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @php($no = 1)
                  @foreach ($invoice as $i)
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ $carbon::parse($i->tanggal)->format('d M Y') }}</td>
                      <td>{{ $i->no_invoice }}</td>
                      <td>{{ 'Rp '.number_format($i->total) }}</td>
                      <td style="white-space: pre-line" width="20%">{{ $i->keterangan }}</td>
                      <td>{{ $i->nama_perusahaan }}</td>
                      <td>
                        <a class="btn btn-sm btn-secondary" href="{{ url('/dashboard/invoice_edit/' . $i->id) }}"><i
                            class="fa fa-pencil"></i></a>
                        <a class="btn btn-sm btn-danger" href="{{ url('/dashboard/invoice_hapus/' . $i->id) }}"><i
                            class="fa fa-trash"></i></a>
                      </td>
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
