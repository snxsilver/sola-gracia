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
                <h3>Pengaturan Tunjangan</h3>
                <div class="align-items-center">
                  @if (Session::get('role') !== 'operator')
                    <a class="btn btn-primary" href="{{ url('/dashboard/pengaturan_tunjangan_tambah') }}"><i
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
                    <th width="5%">No</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Level</th>
                    <th>Nominal</th>
                    <th>Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @php($no = 1)
                  @foreach ($tunjangan as $t)
                    <tr @if ($t->nominal_baru !== null && $t->approved === 0) {!! "style = 'background: rgba(150, 250, 150)'" !!} @endif>
                      <td>{{ $no++ }}</td>
                      <td>{{ $t->tanggal ? $carbon::parse($t->tanggal)->format('d M Y') : '-' }}</td>
                      <td>
                        @if ($t->jenis == 'uang_makan')
                          Uang Makan
                        @else
                          Transport
                        @endif
                      </td>
                      <td>{{ $t->level }}</td>
                      <td>{{ $t->nominal ? 'Rp '.number_format($t->nominal) : '0' }}</td>
                        <td>
                          @if(Session::get('role') === 'owner' || Session::get('role') === 'manager')
                          @if ($t->nominal_baru !== null && $t->approved === 0)
                            <a class="btn btn-sm btn-success text-white" data-toggle="modal" style="cursor: pointer"
                              data-target="#approval"
                              data-yes="{{ url('/dashboard/pengaturan_tunjangan_approve/' . $t->id . '/yes') }}"
                              data-no="{{ url('/dashboard/pengaturan_tunjangan_approve/' . $t->id . '/no') }}"
                              data-nomlama="{{ $t->nominal ? 'Rp '.number_format($t->nominal) : '0' }}" data-nombaru="{{ $t->nominal_baru ? 'Rp '.number_format($t->nominal_baru) : '0' }}"
                              data-tanggal="{{ $t->tanggal ? $carbon::parse($t->tanggal)->format('d M Y') : '-' }}"><i class="fa fa-eye"></i></a>
                          @endif
                          @endif
                          <a class="btn btn-sm btn-secondary"
                            href="{{ url('/dashboard/pengaturan_tunjangan_edit/' . $t->id) }}"><i
                              class="fa fa-pencil"></i></a>
                          @if(Session::get('role') === 'owner' || Session::get('role') === 'manager')
                          <a class="btn btn-sm btn-danger text-white" style="cursor: pointer" data-toggle="modal"
                            data-target="#hapusitem"
                            data-whatever="{{ url('/dashboard/pengaturan_tunjangan_hapus/' . $t->id) }}"><i
                              class="fa fa-trash"></i></a>
                          @endif
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              {{-- Modal Open --}}
              <div class="modal fade" id="approval" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Setujui Pengaturan Tunjangan</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-6">
                            <p>Nominal Lama</p>
                          </div>
                          <div class="col-6 d-flex">
                            <p>: </p>
                            <p class="nominal_lama"></p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-6">
                            <p>Nominal Baru</p>
                          </div>
                          <div class="col-6 d-flex">
                            <p>: </p>
                            <p class="nominal_baru"></p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-6">
                            <p>Tanggal</p>
                          </div>
                          <div class="col-6 d-flex">
                            <p>: </p>
                            <p class="tanggal_approve"></p>
                          </div>
                        </div>
                        Apakah Anda yakin akan menyetujui pengaturan tunjangan?
                      </div>
                    </div>
                    <div class="modal-footer">
                      <a class="btn btn-primary tombol-ya text-white" style="cursor: pointer">Ya</a>
                      <a class="btn btn-warning tombol-no text-white" style="cursor: pointer">Tidak</a>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
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
                      <h5 class="modal-title" id="exampleModalLabel">Hapus Pengaturan Tunjangan</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin akan menghapus pengaturan tunjangan?
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
