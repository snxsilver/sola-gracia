@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon')

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      {{-- <div class="col-12">
        <div class="x_panel">
          <div class="x_content">
            <div class="row">
              <div class="col-12">
                <form action="{{ url('/dashboard/invoice_search') }}">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="search" value="">
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
          <div class="x_content filter_toggle @if(Session::get('mulai') || Session::get('selesai') || Session::get('bulan') || $errors->has('selesai')) @else d-none @endif" style="color: black">
            <form action="{{ url('/dashboard/filter') }}" method="post">
              @csrf
              <div class="row mb-2">
                <div class="ml-2" style="background: #c0c0c0; border-radius: 50px">
                  <button type="button"
                    class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 trigger_date @if(!Session::get('bulan') && !Session::get('tahun')) bg-primary @endif"
                    style="border-radius: 50px">Tanggal</button>
                  <button type="button" class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 trigger_month @if(Session::get('bulan')) bg-primary @endif"
                    style="border-radius: 50px">Bulan</button>
                  <button type="button" class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 trigger_year @if(Session::get('tahun')) bg-primary @endif"
                    style="border-radius: 50px">Tahun</button>
                </div>
              </div>
              <div class="row mt-1 target_date @if(Session::get('bulan') || Session::get('tahun')) d-none @endif">
                <div class="col-md-6 col-12">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Mulai</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="text" readonly class="form-control b-datepicker" name="mulai" @if(Session::get('bulan') || Session::get('tahun')) disabled @endif
                        value={{ old('mulai') ?? Session::get('mulai') ?? '-' }}>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12 mt-md-0 mt-1">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Selesai</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="text" readonly class="form-control b-datepicker" name="selesai" @if(Session::get('bulan') || Session::get('tahun')) disabled @endif
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
              <div class="row mt-1 target_year @if(!Session::get('tahun')) d-none @endif">
                <div class="col-md-6 col-12">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Tahun</label>
                    <div class="col-md-9 col-sm-9 ">
                      <input type="text" readonly class="form-control b-yearpicker" name="tahun" @if(!Session::get('tahun')) disabled @endif
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
      </div> --}}
      <div class="col-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3>Tukang Borongan</h3>
                <div class="align-items-center">
                  <a class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Hapus Filter"
                  href="{{ url('/dashboard/bukukas_refresh/invoice') }}"><i class="fa fa-refresh"></i></a>
                  @if (Session::get('role') !== 'operator')
                    <a class="btn btn-primary" href="{{ url('/dashboard/tukang_borongan_tambah') }}"><i
                        class="fa fa-plus"></i></a>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              {{-- @if (count($invoice) > 0) --}}
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="5%">No <a href="{{ url('/dashboard/bukukas_sort/clear') }}"><i
                          class="fa fa-refresh"></i></a></th>
                    <th>Tanggal <a href="{{ url('/dashboard/bukukas_sort/tanggal') }}"><i class="fa @if(Session::get('sort_tanggal') === 'asc') fa-sort-asc @elseif(Session::get('sort_tanggal') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a>
                    </th>
                    <th>Proyek <a href="{{ url('/dashboard/bukukas_sort/proyek') }}"><i class="fa @if(Session::get('sort_proyek') === 'asc') fa-sort-asc @elseif(Session::get('sort_proyek') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a></th>
                    <th>Nama <a href="{{ url('/dashboard/bukukas_sort/kategori') }}"><i class="fa @if(Session::get('sort_kategori') === 'asc') fa-sort-asc @elseif(Session::get('sort_kategori') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a></th>
                    <th>Total Nominal <a href="{{ url('/dashboard/bukukas_sort/keluar') }}"><i class="fa @if(Session::get('sort_keluar') === 'asc') fa-sort-asc @elseif(Session::get('sort_keluar') === 'desc') fa-sort-desc @else fa-sort @endif"></i></a></th>
                    @if (Session::get('role') === 'owner')
                      <th>Opsi</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @php($no = 1)
                  @foreach ($borongan as $b)
                    <tr>
                      <td>{{ $no++ }}</td>
                      @php($bayar = DB::table('borongan_bayar')->where('borongan',$b->id)->orderBy('tanggal','desc'))
                      @php($tanggal = $bayar->first())
                      @php($nominal = $bayar->sum('borongan_bayar.nominal'))
                      <td>{{ $carbon::parse($tanggal->tanggal)->format('d M Y') }}</td>
                      <td>{{ $b->namaproyek }}</td>
                      <td>{{ $b->nama }}</td>
                      <td>Rp {{ number_format($nominal) }}</td>
                      @if (Session::get('role') === 'owner')
                        <td>
                          <a class="btn btn-sm btn-success" href="{{ url('/dashboard/tukang_borongan_cetak/' . $b->id) }}"><i
                              class="fa fa-eye"></i></a>
                          <a class="btn btn-sm btn-secondary" href="{{ url('/dashboard/tukang_borongan_edit/' . $b->id) }}"><i
                              class="fa fa-pencil"></i></a>
                          <a class="btn btn-sm btn-danger text-white" style="cursor: pointer" data-toggle="modal"
                            data-target="#hapusitem" data-whatever="{{ url('/dashboard/tukang_borongan_hapus/' . $b->id) }}"><i
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
                      <h5 class="modal-title" id="exampleModalLabel">Hapus Pembayaran Tukang Borongan</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin akan menghapus pembayaran tukang borongan?
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
