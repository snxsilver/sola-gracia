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
                <h3>Daftar Proyek</h3>
                @if (Session::get('role') !== 'operator')
                <a class="btn btn-primary" href="{{ url('/dashboard/proyek_tambah') }}"><i class="fa fa-plus"></i></a>
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
                    <th>Kode</th>
                    <th>Nama Proyek</th>
                    <th>Nilai</th>
                    @if (Session::get('role') === 'owner')
                      <th>Opsi</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @php($no = 1)
                  @foreach ($proyek as $p)
                    <tr @if ($p->pajak === null || $p->pajak === 0) {!! "style = 'background: rgba(150, 250, 150)'" !!} @endif>
                      <td>{{ $no++ }}</td>
                      <td>{{ $p->kode }}</td>
                      <td>{{ $p->nama }}</td>
                      <td>{{ 'Rp ' . number_format($p->nilai) }}</td>
                      @if (Session::get('role') === 'owner')
                        <td>
                          <a class="btn btn-sm btn-secondary" href="{{ url('/dashboard/proyek_edit/' . $p->id) }}"><i
                              class="fa fa-pencil"></i></a>
                          <a class="btn btn-sm btn-danger" href="{{ url('/dashboard/proyek_hapus/' . $p->id) }}"><i
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
