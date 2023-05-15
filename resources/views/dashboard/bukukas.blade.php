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
                <form action="{{ url('/dashboard/bukukas_search') }}">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="search" value="{{$search ?? ''}}">
                    <div class="input-group-append">
                      <button type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="x_title filter_toggler d-flex justify-content-between" style="cursor: pointer">
            <span style="color: black; font-weight: bold">Filter</span>
            <span style="color: black"><i class="fa fa-chevron-down"></i></span>
          </div>
          <div class="x_content filter_toggle @if(Session::get('proyek') || Session::get('kategori') || Session::get('mulai') || Session::get('selesai') || Session::get('bulan') || $errors->has('selesai')) @else d-none @endif" style="color: black">
            <form action="{{ url('/dashboard/filter') }}" method="post">
              @csrf
              <div class="row">
                <div class="col-md-6 col-12">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Kategori</label>
                    <div class="col-md-9 col-sm-9 ">
                      <select name="kategori" class="form-control" id="">
                        <option value="" @if (!Session::get('kategori')) selected @endif>Semua Kategori</option>
                        @foreach ($kategori as $k)
                          <option value={{ $k->id }} @if (old('kategori') ? old('kategori') == $k->id : Session::get('kategori') == $k->id) selected @endif>
                            {{ $k->nama }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12 mt-md-0 mt-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Proyek</label>
                    <div class="col-md-9 col-sm-9 ">
                      <select name="proyek" class="form-control" id="">
                        <option value="" @if (!Session::get('proyek')) selected @endif>Semua Proyek</option>
                        @foreach ($proyek as $p)
                          <option value={{ $p->id }} @if (old('proyek') ? old('proyek') == $p->id : Session::get('proyek') == $p->id) selected @endif>
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
                    class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 trigger_date @if(Session::get('mulai') || Session::get('selesai')) bg-primary @endif"
                    style="border-radius: 50px">Tanggal</button>
                  <button type="button" class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 trigger_month @if(Session::get('bulan')) bg-primary @endif"
                    style="border-radius: 50px">Bulan</button>
                  <button type="button" class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 trigger_year @if(!Session::get('mulai') && !Session::get('selesai') && !Session::get('bulan')) bg-primary @endif"
                    style="border-radius: 50px">Tahun</button>
                </div>
              </div>
              <div class="row mt-1 target_date @if(!Session::get('mulai') && !Session::get('selesai')) d-none @endif">
                <div class="col-md-6 col-12">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Mulai</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="text" readonly class="form-control b-datepicker" name="mulai" @if(!Session::get('mulai') && !Session::get('selesai')) disabled @endif
                        value={{ old('mulai') ?? Session::get('mulai') ?? '-' }}>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12 mt-md-0 mt-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Selesai</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="text" readonly class="form-control b-datepicker" name="selesai" @if(!Session::get('mulai') && !Session::get('selesai')) disabled @endif
                        value={{ old('selesai') ?? Session::get('selesai') ?? '-' }}>
                        @error('selesai')<small>*{{$message}}</small>@enderror
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-1 target_month @if(!Session::get('bulan')) d-none @endif">
                <div class="col-md-6 col-12">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Bulan</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="text" readonly class="form-control b-monthpicker" name="bulan" @if(!Session::get('bulan')) disabled @endif
                        value={{ old('bulan') ?? Session::get('bulan') ?? '-' }}>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-1 target_year @if(Session::get('mulai') || Session::get('selesai') || Session::get('bulan')) d-none @endif">
                <div class="col-md-6 col-12">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Tahun</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="text" readonly class="form-control b-yearpicker" name="tahun" @if(Session::get('mulai') || Session::get('selesai') || Session::get('bulan')) disabled @endif
                        value={{ old('tahun') ?? Session::get('tahun') ?? '-' }}>
                    </div>
                  </div>
                </div>
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
      <div class="col-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3>Buku Kas</h3>
                <div class="align-items-center">
                  @if (Session::get('role') === 'owner')
                    @if (!Session::get('approved'))
                    <a class="btn btn-primary" data-toggle="tooltip" data-placement="top"
                    title="Tutup Buku" href="{{ url('/dashboard/tutup_buku') }}"><i
                      class="fa fa-check"></i></a>
                    @endif
                    <a class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Cetak Excel"
                      href="{{ url('/dashboard/export') }}"><i class="fa fa-file-excel-o"></i></a>
                  @endif
                  <a class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Hapus Filter"
                      href="{{ url('/dashboard/bukukas_refresh/bukukas') }}"><i class="fa fa-refresh"></i></a>
                  @if (Session::get('role') !== 'operator' && !Session::get('approved'))
                    <a class="btn btn-success text-sm btn-sm fw-semibold" data-toggle="tooltip" data-placement="top"
                      title="Tambah Transaksi dari Stok" href="{{ url('/dashboard/ambil_stok') }}"><span
                        class="text-white mr-1"><i class="fa fa-plus"></i></span>Ambil Stok</a>
                    <a class="btn btn-primary" data-toggle="tooltip" data-placement="top"
                      title="Tambah Transaksi Langsung" href="{{ url('/dashboard/bukukas_tambah') }}"><i
                        class="fa fa-plus"></i></a>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              @if(count($bukukas) > 0)
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
                    <th>Proyek <a href="{{ url('/dashboard/bukukas_sort/proyek') }}"><i class="fa @if(Session::get('sort_proyek') === 'asc') fa-sort-asc @elseif(Session::get('sort_proyek') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a>
                    </th>
                    <th>Tanggal <a href="{{ url('/dashboard/bukukas_sort/tanggal') }}"><i class="fa @if(Session::get('sort_tanggal') === 'asc') fa-sort-asc @elseif(Session::get('sort_tanggal') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a>
                    </th>
                    <th>Uraian</th>
                    <th>Keterangan</th>
                    <th>Kategori <a href="{{ url('/dashboard/bukukas_sort/kategori') }}"><i class="fa @if(Session::get('sort_kategori') === 'asc') fa-sort-asc @elseif(Session::get('sort_kategori') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a>
                    </th>
                    <th>No Nota <a href="{{ url('/dashboard/bukukas_sort/bukti') }}"><i class="fa @if(Session::get('sort_bukti') === 'asc') fa-sort-asc @elseif(Session::get('sort_bukti') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a>
                    </th>
                    <th>Masuk <a href="{{ url('/dashboard/bukukas_sort/masuk') }}"><i class="fa @if(Session::get('sort_masuk') === 'asc') fa-sort-asc @elseif(Session::get('sort_masuk') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a></th>
                    <th>Keluar <a href="{{ url('/dashboard/bukukas_sort/keluar') }}"><i class="fa @if(Session::get('sort_keluar') === 'asc') fa-sort-asc @elseif(Session::get('sort_keluar') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a>
                    </th>
                    @if (Session::get('role') === 'owner' && !Session::get('approved'))
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
                      <td style="white-space: pre-line" width="20%">{{ $b->uraian }}</td>
                      <td width="5%"> {{ $b->keterangan }}</td>
                      <td>{{ $b->namakategori }}</td>
                      <td>{{ $b->no_nota ? $b->no_nota : '-' }}</td>
                      <td>{{ $b->masuk ? 'Rp ' . number_format($b->masuk) : '-' }}</td>
                      <td>{{ $b->keluar ? 'Rp ' . number_format($b->keluar) : '-' }}</td>
                      @if (Session::get('role') === 'owner' && !Session::get('approved'))
                        <td width="10%">
                          @if ($b->ambil_stok == 0 || $b->ambil_stok == null)
                            @if ($b->nota)
                              <span data-toggle="tooltip" data-placement="bottom" title="Lihat Nota"><a class="btn btn-sm btn-success text-white" style="cursor: pointer" data-toggle="modal"
                                data-target="#shownota" data-name="{{ $b->no_bukti ?? 'Nota' }}"
                                data-url="{{ url('/images/nota/' . $b->nota) }}"
                                data-whatever="{{ asset('/images/nota/' . $b->nota) }}"><i class="fa fa-eye"></i></a></span>
                            @endif
                            <a class="btn btn-sm btn-secondary"
                              href="{{ url('/dashboard/bukukas_edit/' . $b->id) }}"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-sm btn-danger text-white" style="cursor: pointer" data-toggle="modal"
                              data-target="#hapusitem"
                              data-whatever="{{ url('/dashboard/bukukas_hapus/' . $b->id) }}"><i
                                class="fa fa-trash"></i></a>
                          @elseif($b->ambil_stok == 1)
                            <a class="btn btn-sm btn-secondary"
                              href="{{ url('/dashboard/ambil_stok_edit/' . $b->id) }}"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-sm btn-danger text-white" style="cursor: pointer" data-toggle="modal"
                              data-target="#hapusitem"
                              data-whatever="{{ url('/dashboard/ambil_stok_hapus/' . $b->id) }}"><i
                                class="fa fa-trash"></i></a>
                          @elseif($b->ambil_stok == 2)
                            @php($invoice = DB::table('invoice')->where('bukukas',$b->id)->first())
                            @if($invoice)
                            <span data-toggle="tooltip" data-placement="bottom" title="Lihat Invoice"><a class="btn btn-sm btn-success text-white" style="cursor: pointer" href="{{ url('/dashboard/invoice_cetak/' . $invoice->id) }}"><i class="fa fa-eye"></i></a></span>
                            @endif
                          @endif
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
                      <h5 class="modal-title" id="exampleModalLabel">Hapus Buku Kas</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin akan menghapus buku kas?
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
                      <a type="button" class="btn btn-primary tombol-ya" data-toggle="tooltip"
                        data-placement="bottom" title="Download Nota"><i class="fa fa-download"></i></a>
                      <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                    </div>
                  </div>
                </div>
              </div>
              {{-- End of Modal --}}
              <div class="d-flex justify-content-center">
                {{ $bukukas->appends(request()->input())->links() }}
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
