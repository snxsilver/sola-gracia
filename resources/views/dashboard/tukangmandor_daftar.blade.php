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
                <h3>Daftar Tukang dengan Mandor</h3>
                <div class="d-flex align-items-center justify-content-end">
                  @if($mandor->approved === 1)
                  @if (Session::get('role') === 'owner' || Session::get('role') === 'manager')
                  <a data-whatever="{{ url('/dashboard/tukang_mandor_disapprove/'.$mandor->id) }}" style="cursor: pointer" data-toggle="modal"
                  data-target="#hapusitem" class="btn btn-warning text-white"><i
                      class="fa fa-unlock" data-toggle="tooltip"
                      data-placement="bottom" title="Batalkan persetujuan"></i></a>
                  @endif
                  @endif
                  <a href="{{ url('/dashboard/tukang_mandor') }}" class="btn btn-primary"><i
                      class="fa fa-arrow-left"></i></a>
                </div>
                <div class="modal fade" id="hapusitem" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Batalkan Pembayaran Tukang Harian</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin akan membatalkan pembayaran tukang harian?
                    </div>
                    <div class="modal-footer">
                      <a class="btn btn-primary tombol-ya text-white" style="cursor: pointer">Ya</a>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    </div>
                  </div>
                </div>
              </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <div class="form-group row">
                <label class="col-form-label col-md-3 col-sm-3 ">Mandor</label>
                <div class="col-md-9 col-sm-9">
                  <select class="form-control" id="" disabled style="background: transparent">
                    <option>{{ $mandor->nama }}</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-3 col-sm-3 ">Tanggal</label>
                <div class="col-md-9 col-sm-9">
                  <div class="d-flex align-items-center">
                    <input type="text" class="form-control bo-datepicker" disabled style="background: transparent"
                      value="{{ $carbon->parse($mandor->tanggal)->format('d-M-Y') }}">
                  </div>
                </div>
              </div>
              @if($mandor->approved !== 1)
              <div class="row">
                <div class="col-12">
                  <div class="d-flex align-items-center justify-content-end">
                    <a href="{{ url('/dashboard/tukang_mandor_tambah_c/'.$mandor->id.'/'.$carbon->parse($mandor->tanggal)->format('d-M-Y')) }}" class="btn btn-primary"><i
                        class="fa fa-plus"></i></a>
                  </div>
                </div>
              </div>
              @endif
              <table class="table">
                <thead>
                  <tr>
                    <th width="10%">No</th>
                    <th>Nama Tukang</th>
                    <th>Hari Kerja</th>
                    <th>Nominal</th>
                    <th>OPSI</th>
                  </tr>
                </thead>
                <tbody>
                  @php($no = 1)
                  @foreach ($tukang as $t)
                    @php($kerja = DB::table('gaji_mandor_tukang')->where('mandor_tukang', $t->id))
                    @php($hari = $kerja->count())
                    @php($nominal = $kerja->sum('total'))
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ $t->nama }}</td>
                      <td>{{ $hari }}</td>
                      <td>{{ $nominal }}</td>
                      <td>
                        <a class="btn btn-sm btn-secondary"
                          href="{{ url('/dashboard/tukang_mandor_daftar_b/' . $mandor->id . '/' . $t->id) }}"><i
                            class="fa fa-pencil"></i></a>
                      @if($mandor->approved !== 1)
                        <a class="btn btn-sm btn-danger text-white" style="cursor: pointer" data-toggle="modal"
                          data-target="#hapusitem"
                          data-whatever="{{ url('/dashboard/tukang_mandor_hapus_a/' . $t->id) }}"><i
                            class="fa fa-trash"></i></a>
                      @endif
                      </td>
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
                      <h5 class="modal-title" id="exampleModalLabel">Hapus Tukang dari Gaji dengan Mandor</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin akan menghapus tukang dari gaji dengan mandor?
                    </div>
                    <div class="modal-footer">
                      <a class="btn btn-primary tombol-ya text-white" style="cursor: pointer">Ya</a>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- /page content -->
@endsection
