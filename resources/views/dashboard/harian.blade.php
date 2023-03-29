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
                <h3>Tukang Harian</h3>
                <div class="align-items-center">
                  <a class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Hapus Filter"
                  href="{{ url('/dashboard/bukukas_refresh/invoice') }}"><i class="fa fa-refresh"></i></a>
                  @if (Session::get('role') !== 'operator')
                    <a class="btn btn-primary" href="{{ url('/dashboard/tukang_harian_tambah') }}"><i
                        class="fa fa-plus"></i></a>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <table class="table">
                <thead>
                  <tr>
                    <th width="5%">No <a href="{{ url('/dashboard/bukukas_sort/clear') }}"><i
                          class="fa fa-refresh"></i></a></th>
                    <th>Tanggal <a href="{{ url('/dashboard/bukukas_sort/tanggal') }}"><i class="fa @if(Session::get('sort_tanggal') === 'asc') fa-sort-asc @elseif(Session::get('sort_tanggal') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a>
                    </th>
                    <th>Nama <a href="{{ url('/dashboard/bukukas_sort/kategori') }}"><i class="fa @if(Session::get('sort_kategori') === 'asc') fa-sort-asc @elseif(Session::get('sort_kategori') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a></th>
                    <th>Hari Kerja <a href="{{ url('/dashboard/bukukas_sort/bukti') }}"><i class="fa @if(Session::get('sort_bukti') === 'asc') fa-sort-asc @elseif(Session::get('sort_bukti') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a></th>
                    <th>Total Nominal <a href="{{ url('/dashboard/bukukas_sort/keluar') }}"><i class="fa @if(Session::get('sort_keluar') === 'asc') fa-sort-asc @elseif(Session::get('sort_keluar') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a></th>
                      <th>Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @php($no = 1)
                  @foreach ($harian as $h)
                    <tr @if ($h->approved === 0) {!! "style = 'background: rgba(150, 250, 150)'" !!} @endif>
                      <td>{{ $no++ }}</td>
                      @php($bayar = DB::table('harian_bayar')->where('harian',$h->id)->orderBy('tanggal','desc'))
                      @php($tanggal = $bayar->first())
                      @php($jumlah = $bayar->count())
                      @php($nominal = $bayar->sum('harian_bayar.total'))
                      <td>{{ $carbon::parse($tanggal->tanggal)->format('d M Y') }}</td>
                      <td>{{ $h->nama }}</td>
                      <td>{{ $jumlah }}</td>
                      <td>Rp {{ number_format($nominal) }}</td>
                      <td>
                        <a class="btn btn-sm btn-success" href="{{ url('/dashboard/tukang_harian_cetak/' . $h->id) }}"><i
                          class="fa fa-eye"></i></a>
                          @if($h->approved !== 1)
                          @if (Session::get('role') === 'owner' || Session::get('role') === 'manager')
                          <a class="btn btn-sm btn-success text-white" style="cursor: pointer" data-toggle="modal"
                            data-target="#hapusitem2" data-whatever="{{ url('/dashboard/tukang_harian_approve/' . $h->id) }}"><i
                              class="fa fa-check"></i></a>
                          @endif
                          @endif
                          <a class="btn btn-sm btn-secondary" href="{{ url('/dashboard/tukang_harian_edit/' . $h->id) }}"><i
                              class="fa fa-pencil"></i></a>
                          @if($h->approved !== 1)
                          <a class="btn btn-sm btn-danger text-white" style="cursor: pointer" data-toggle="modal"
                            data-target="#hapusitem" data-whatever="{{ url('/dashboard/tukang_harian_hapus/' . $h->id) }}"><i
                              class="fa fa-trash"></i></a>
                          @endif
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              {{-- Modal Open --}}
              <div class="modal fade" id="hapusitem2" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Setujui Pembayaran Tukang Harian</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin akan menyetujui pembayaran tukang harian?
                    </div>
                    <div class="modal-footer">
                      <a class="btn btn-primary tombol-ya text-white" style="cursor: pointer">Ya</a>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    </div>
                  </div>
                </div>
              </div>
              {{-- Modal Open --}}
              <div class="modal fade" id="hapusitem" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Hapus Pembayaran Tukang Harian</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin akan menghapus pembayaran tukang harian?
                    </div>
                    <div class="modal-footer">
                      <a class="btn btn-primary tombol-ya text-white" style="cursor: pointer">Ya</a>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    </div>
                  </div>
                </div>
              </div>
              {{-- End of Modal --}}
              {{-- <div class="d-flex justify-content-center">
                {{ $invoice->appends(request()->input())->links() }}
              </div> --}}
              {{-- @else
              <h3 class="text-center">Data tidak ditemukan</h3>
              @endif --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->
@endsection
