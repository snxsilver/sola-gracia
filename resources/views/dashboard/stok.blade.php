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
                    <th>No Nota</th>
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
                      <td>{{ 'Rp ' . number_format($s->harga) }}</td>
                      <td>{{ $s->no_bukti }}</td>
                      @if (Session::get('role') === 'owner')
                        <td>
                          @if ($s->nota)
                            <span data-toggle="tooltip" data-placement="bottom" title="Lihat Nota"><a
                                class="btn btn-sm btn-success text-white" style="cursor: pointer" data-toggle="modal"
                                data-target="#shownota" data-name="{{ $s->no_bukti ?? 'Nota' }}"
                                data-url="{{ url('/images/nota/' . $s->nota) }}"
                                data-whatever="{{ asset('/images/nota/' . $s->nota) }}"><i
                                  class="fa fa-eye"></i></a></span>
                          @endif
                          <a class="btn btn-sm btn-secondary" href="{{ url('/dashboard/stok_edit/' . $s->id) }}"><i
                              class="fa fa-pencil"></i></a>
                          <a class="btn btn-sm btn-danger text-white" style="cursor: pointer" data-toggle="modal"
                            data-target="#hapusitem" data-whatever="{{ url('/dashboard/stok_hapus/' . $s->id) }}"><i
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
                      <h5 class="modal-title" id="exampleModalLabel">Hapus Stok</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin akan menghapus stok?
                    </div>
                    <div class="modal-footer">
                      <a class="btn btn-primary tombol-ya text-white" style="cursor: pointer">Ya</a>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    </div>
                  </div>
                </div>
              </div>
              {{-- End of Modal --}}
              {{-- Modal Open --}}
              <div class="modal fade" id="shownota" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Nota</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <img src="" class="showimage" style="width: 100%" alt="">
                    </div>
                    <div class="modal-footer">
                      <a type="button" class="btn btn-primary tombol-ya" data-toggle="tooltip" data-placement="bottom"
                        title="Download Nota"><i class="fa fa-download"></i></a>
                      <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                    </div>
                  </div>
                </div>
              </div>
              {{-- End of Modal --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->
@endsection
