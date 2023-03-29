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
                <h3>Tukang dengan Mandor</h3>
                <div class="align-items-center">
                  <a class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Hapus Filter"
                  href="{{ url('/dashboard/bukukas_refresh/invoice') }}"><i class="fa fa-refresh"></i></a>
                  @if (Session::get('role') !== 'operator')
                    <a class="btn btn-primary text-white" style="cursor: pointer" data-toggle="modal"
                    data-target="#tambahtukangmandor"><i class="fa fa-plus"></i></a>
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
                    <th>Jumlah Tukang <a href="{{ url('/dashboard/bukukas_sort/bukti') }}"><i class="fa @if(Session::get('sort_bukti') === 'asc') fa-sort-asc @elseif(Session::get('sort_bukti') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a></th>
                    <th>Total Nominal <a href="{{ url('/dashboard/bukukas_sort/keluar') }}"><i class="fa @if(Session::get('sort_keluar') === 'asc') fa-sort-asc @elseif(Session::get('sort_keluar') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a></th>
                      <th>Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @php($no = 1)
                  @foreach ($proyek as $p)
                    <tr @if ($p->approved === 0) {!! "style = 'background: rgba(150, 250, 150)'" !!} @endif>
                      <td>{{ $no++ }}</td>
                      @php($jumlah = DB::table('mandor_tukang')->where('mandor_proyek',$p->id)->count())
                      @php($nominal = DB::table('mandor_tukang')->where('mandor_proyek',$p->id)->join('gaji_mandor_tukang','mandor_tukang.id','=','gaji_mandor_tukang.mandor_tukang')->sum('gaji_mandor_tukang.total'))
                      <td>{{ $carbon::parse($p->tanggal)->format('d M Y') }}</td>
                      <td>{{ $p->nama }}</td>
                      <td>{{ $jumlah }}</td>
                      <td>{{ $nominal }}</td>
                      <td>
                        <a class="btn btn-sm btn-success" href="{{ url('/dashboard/tukang_mandor_cetak/' . $p->id) }}"><i
                          class="fa fa-eye"></i></a>
                          @if (Session::get('role') === 'owner' || Session::get('role') === 'manager')
                          @if($p->approved !== 1)
                          <a class="btn btn-sm btn-success text-white" style="cursor: pointer" data-toggle="modal"
                          data-target="#hapusitem2" data-whatever="{{ url('/dashboard/tukang_mandor_approve/' . $p->id) }}"><i
                          class="fa fa-check"></i></a>
                          @endif
                          @endif
                            <a class="btn btn-sm btn-secondary" href="{{ url('/dashboard/tukang_mandor_edit/' . $p->id) }}"><i
                              class="fa fa-pencil"></i></a>
                          @if($p->approved !== 1)
                          <a class="btn btn-sm btn-danger text-white" style="cursor: pointer" data-toggle="modal"
                            data-target="#hapusitem" data-whatever="{{ url('/dashboard/tukang_mandor_hapus/' . $p->id) }}"><i
                              class="fa fa-trash"></i></a>
                        </td>
                      @endif
                    </tr>
                  @endforeach
                </tbody>
              </table>
              {{-- Modal Open --}}
              <div class="modal fade" id="tambahtukangmandor" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <form action="{{ url('/dashboard/tukang_mandor_before') }}" method="post">
                      @csrf
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Gaji Tukang dengan Mandor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group row">
                          <label class="col-form-label col-md-3 col-sm-3 ">Mandor</label>
                          <div class="col-md-9 col-sm-9">
                            <select name="mandor" class="form-control" id="">
                              @foreach ($mandor as $m)
                                <option value={{ $m->id }}>
                                  {{ $m->nama }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-md-3 col-sm-3 ">Tanggal</label>
                          <div class="col-md-9 col-sm-9">
                            <div class="d-flex align-items-center">
                              <input type="text" class="form-control bo-datepicker" readonly style="background: transparent"
                                name="tanggal" value="{{date_format(now(), 'd-M-Y')}}">
                            </div>
                            <small class="warning">*Masukkan tanggal pertama tukang bekerja</small>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary text-white" style="cursor: pointer">Ya</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              {{-- Modal Open --}}
              <div class="modal fade" id="hapusitem2" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Setujui Pembayaran Tukang dengan Mandor</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin akan menyetujui pembayaran tukang dengan mandor?
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
                      <h5 class="modal-title" id="exampleModalLabel">Hapus Pembayaran Tukang Mandor</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin akan menghapus pembayaran tukang mandor?
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
