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
                <h3>Pengaturan Gaji Mandor</h3>
                <div class="align-items-center">
                  @if (Session::get('role') !== 'operator')
                    <a class="btn btn-primary" href="{{ url('/dashboard/gaji_mandor_tambah') }}"><i
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
                    <th>Mandor</th>
                    <th>Level</th>
                    <th>Gaji Pokok</th>
                    <th>Uang Lembur per Jam</th>
                    <th>Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @php($no = 1)
                  @foreach ($gaji as $g)
                    <tr @if (($g->pokok_baru !== null || $g->lembur_baru !== null) && $g->approved === 0) {!! "style = 'background: rgba(150, 250, 150)'" !!} @endif>
                      <td>{{ $no++ }}</td>
                      <td>{{ $g->tanggal ? $carbon::parse($g->tanggal)->format('d M Y') : '-' }}</td>
                      @php($mandor = DB::table('mandor')->where('id', $g->mandor)->first())
                      <td>{{ $mandor->nama }}</td>
                      <td>{{ $g->level }}</td>
                      <td>{{ $g->pokok }}</td>
                      <td>{{ $g->lembur }}</td>
                      @if (Session::get('role') === 'owner')
                        <td>
                          @if (($g->pokok_baru !== null || $g->lembur_baru !== null) && $g->approved === 0)
                            <a class="btn btn-sm btn-success text-white" data-toggle="modal" style="cursor: pointer"
                              data-target="#gajiapproval"
                              data-yes="{{ url('/dashboard/gaji_mandor_approve/' . $g->id . '/yes') }}"
                              data-no="{{ url('/dashboard/gaji_mandor_approve/' . $g->id . '/no') }}"
                              data-polama="{{ $g->pokok }}" data-pobaru="{{ $g->pokok_baru }}"
                              data-lelama="{{ $g->lembur }}" data-lebaru="{{ $g->lembur_baru }}"
                              data-tanggal="{{ $carbon::parse($g->tanggal)->format('d M Y') }}"><i
                                class="fa fa-eye"></i></a>
                          @endif
                          <a class="btn btn-sm btn-secondary"
                            href="{{ url('/dashboard/gaji_mandor_edit/' . $g->id) }}"><i class="fa fa-pencil"></i></a>
                          <a class="btn btn-sm btn-danger text-white" style="cursor: pointer" data-toggle="modal"
                            data-target="#hapusitem"
                            data-whatever="{{ url('/dashboard/gaji_mandor_hapus/' . $g->id) }}"><i
                              class="fa fa-trash"></i></a>
                        </td>
                      @endif
                    </tr>
                  @endforeach
                </tbody>
              </table>
              {{-- Modal Open --}}
              <div class="modal fade" id="gajiapproval" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Setujui Pengaturan Gaji Mandor</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-6">
                            <div class="row">
                              <div class="col-6">
                                <p>Gaji Pokok Lama</p>
                              </div>
                              <div class="col-6 d-flex">
                                <p>: </p>
                                <p class="pokok_lama"></p>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-6">
                                <p>Gaji Pokok Baru</p>
                              </div>
                              <div class="col-6 d-flex">
                                <p>: </p>
                                <p class="pokok_baru"></p>
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
                          </div>
                          <div class="col-6">
                            <div class="row">
                              <div class="col-6">
                                <p>Uang Lembur Lama</p>
                              </div>
                              <div class="col-6 d-flex">
                                <p>: </p>
                                <p class="lembur_lama"></p>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-6">
                                <p>Uang Lembur Baru</p>
                              </div>
                              <div class="col-6 d-flex">
                                <p>: </p>
                                <p class="lembur_baru"></p>
                              </div>
                            </div>
                          </div>
                        </div>
                        Apakah Anda yakin akan menyetujui pengaturan gaji mandor?
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
                      <h5 class="modal-title" id="exampleModalLabel">Hapus Pengaturan Gaji Mandor</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin akan menghapus pengaturan gaji mandor?
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
