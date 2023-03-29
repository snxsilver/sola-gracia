@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon')

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12">
        <div class="x_panel">
          <div class="x_content">
            <div class="row">
              <div class="col-12">
                <form action="{{ url('/dashboard/proyek_search') }}">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="search"
                      value="{{ $search ?? '' }}">
                    <div class="input-group-append">
                      <button type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3>Daftar Proyek</h3>
                <div class="align-items-center">
                  <a class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Hapus Filter"
                    href="{{ url('/dashboard/bukukas_refresh/proyek') }}"><i class="fa fa-refresh"></i></a>
                  @if (Session::get('role') !== 'operator' && Session::get('tahun') == $carbon->parse(now())->year)
                    <a class="btn btn-primary" href="{{ url('/dashboard/proyek_tambah') }}"><i class="fa fa-plus"></i></a>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              @if (count($proyek) > 0)
                <table class="table">
                  <thead>
                    <tr>
                      <th width="5%">No <a href="{{ url('/dashboard/bukukas_sort/clear') }}"><i
                            class="fa fa-refresh"></i></a></th>
                      <th>Tanggal <a href="{{ url('/dashboard/bukukas_sort/tanggal') }}"><i
                            class="fa @if (Session::get('sort_tanggal') === 'asc') fa-sort-asc @elseif(Session::get('sort_tanggal') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a>
                      </th>
                      <th>Kode <a href="{{ url('/dashboard/bukukas_sort/kategori') }}"><i
                            class="fa @if (Session::get('sort_kategori') === 'asc') fa-sort-asc @elseif(Session::get('sort_kategori') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a>
                      </th>
                      <th>Nama Proyek <a href="{{ url('/dashboard/bukukas_sort/proyek') }}"><i
                            class="fa @if (Session::get('sort_proyek') === 'asc') fa-sort-asc @elseif(Session::get('sort_proyek') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a>
                      </th>
                      <th>Nilai <a href="{{ url('/dashboard/bukukas_sort/keluar') }}"><i
                            class="fa @if (Session::get('sort_keluar') === 'asc') fa-sort-asc @elseif(Session::get('sort_keluar') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a>
                      </th>
                      @if (Session::get('role') === 'owner' && Session::get('tahun') == $carbon->parse(now())->year)
                        <th>Opsi</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody>
                    @php($no = 1)
                    @foreach ($proyek as $p)
                      <tr @if ($p->pajak === null || $p->pajak === 0) {!! "style = 'background: rgba(150, 250, 150)'" !!} @endif>
                        <td>{{ $no++ }}</td>
                        <td>{{ $p->created_at ? $carbon::parse($p->created_at)->format('d M Y') : '' }}</td>
                        <td>{{ $p->kode }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ 'Rp ' . number_format($p->nilai) }}</td>
                        @if (Session::get('role') === 'owner' && Session::get('tahun') == $carbon->parse(now())->year)
                          <td>
                            <a class="btn btn-sm btn-secondary" href="{{ url('/dashboard/proyek_edit/' . $p->id) }}"><i
                                class="fa fa-pencil"></i></a>
                            <a class="btn btn-sm btn-danger text-white" style="cursor: pointer" data-toggle="modal"
                              data-target="#hapusitem" data-whatever="{{ url('/dashboard/proyek_hapus/' . $p->id) }}"><i
                                class="fa fa-trash"></i></a>
                          </td>
                        @endif
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                {{-- Modal Open --}}
                <div class="modal fade" id="hapusitem" tabindex="-1" aria-labelledby="exampleModalLabel"
                  aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Proyek</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        Apakah Anda yakin akan menghapus proyek?
                      </div>
                      <div class="modal-footer">
                        <a class="btn btn-primary tombol-ya text-white" style="cursor: pointer">Ya</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                      </div>
                    </div>
                  </div>
                </div>
                {{-- End of Modal --}}
                <div class="d-flex justify-content-center">
                  {{ $proyek->appends(request()->input())->links() }}
                </div>
              @else
                <h3 class="text-center">Data tidak ditemukan</h3>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->
@endsection
