@extends('dashboard.header');
@inject('carbon', 'Carbon\Carbon')
@inject('helper', 'App\Helpers\Helper');

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3>Detail Gaji Tukang dengan Mandor</h3>
                <div class="d-flex align-items-center justify-content-end">
                  @if($mandor->approved === 1)
                  @if (Session::get('role') === 'owner' || Session::get('role') === 'manager')
                  <a data-whatever="{{ url('/dashboard/tukang_mandor_disapprove/'.$mandor->id) }}" style="cursor: pointer" data-toggle="modal"
                  data-target="#hapusitem" class="btn btn-warning text-white"><i
                      class="fa fa-unlock" data-toggle="tooltip"
                      data-placement="bottom" title="Batalkan persetujuan"></i></a>
                  @endif
                  @endif
                  <a href="{{ url('/dashboard/tukang_mandor_edit/'.$mandor->id) }}" class="btn btn-primary"><i
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
              <form class="form-label-left input_mask" action="{{ url('/dashboard/tukang_mandor_aksi') }}" method="post">
                @csrf
                <div class="hapus-field"></div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Mandor</label>
                  <div class="col-md-9 col-sm-9">
                    <input type="hidden" name="proyekid" value="{{ $mandor->id }}">
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
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tukang</label>
                  <div class="col-md-9 col-sm-9">
                    <input type="hidden" name="mandorid" value="{{ $tukang->id }}">
                    <select class="form-control" id="" disabled style="background: transparent">
                      <option>{{ $tukang->nama }}</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Gaji Tukang</label>
                  <div class="col-md-9 col-sm-9">
                    <select class="form-control" id="" name="gaji_mandor" @if($mandor->approved === 1) disabled @endif>
                      @foreach ($gaji as $g)
                        <option value="{{ $g->id }}" @if (old('gaji_mandor') == $g->id) selected @endif>
                          {{ $g->pokok . ' - ' . $g->lembur }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Jadwal Tukang</label>
                </div>
                <div class="form-group row">
                  <div class="col-8">
                    <div class="row">
                      <div class="col-4">
                        <label class="col-form-label">Tanggal</label>
                      </div>
                      <div class="col-8">
                        <label class="col-form-label">Proyek</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="row">
                      <div class="col-4">
                        <label class="col-form-label">Uang Makan</label>
                      </div>
                      <div class="col-4">
                        <label class="col-form-label">Transport</label>
                      </div>
                      <div class="col-4">
                        <div class="d-flex align-items-center">
                        <label class="col-form-label">Lembur</label>
                        @if($mandor->approved !== 1)
                          <button type="button" class="btn btn-primary btn-sm ml-2 tambah-mandor" style="margin: 0"><i class="fa fa-plus"></i></button>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @php($bayar = DB::table('gaji_mandor_tukang')->where('mandor_tukang', $tukang->id)->orderBy('tanggal', 'asc')->get())
                @php($tanggalacuan = $mandor->tanggal)
                @php($subnum = $carbon->parse($tanggalacuan)->getDaysFromStartOfWeek())
                @php($tanggal = $carbon->parse($tanggalacuan)->subDays($subnum))
                @php($i = 0)
                <div class="target-mandor">
                  {{-- Clone Node --}}
                    <div class="form-group row d-none">
                      <div class="col-8">
                        <div class="row">
                          <div class="col-4">
                            <select type="text" class="form-control" name="tanggal[]">
                              <option value="{{ $carbon->parse($tanggal)->addDays(0) }}">{{ $helper->formatHari($carbon->parse($tanggal)->addDays(0)) }}</option>
                              <option value="{{ $carbon->parse($tanggal)->addDays(1) }}">{{ $helper->formatHari($carbon->parse($tanggal)->addDays(1)) }}</option>
                              <option value="{{ $carbon->parse($tanggal)->addDays(2) }}">{{ $helper->formatHari($carbon->parse($tanggal)->addDays(2)) }}</option>
                              <option value="{{ $carbon->parse($tanggal)->addDays(3) }}">{{ $helper->formatHari($carbon->parse($tanggal)->addDays(3)) }}</option>
                              <option value="{{ $carbon->parse($tanggal)->addDays(4) }}">{{ $helper->formatHari($carbon->parse($tanggal)->addDays(4)) }}</option>
                              <option value="{{ $carbon->parse($tanggal)->addDays(5) }}">{{ $helper->formatHari($carbon->parse($tanggal)->addDays(5)) }}</option>
                              <option value="{{ $carbon->parse($tanggal)->addDays(6) }}">{{ $helper->formatHari($carbon->parse($tanggal)->addDays(6)) }}</option>
                            </select>
                          </div>
                          <div class="col-8">
                            <select name="proyek[]" class="form-control" id="">
                              <option value="">Tidak ada pekerjaan</option>
                              @foreach ($proyek as $p)
                                <option value={{ $p->id }}>
                                  {{ $p->nama }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="row">
                          <div class="col-4">
                            <select name="uang_makan[]" class="form-control" id="">
                              @foreach ($makan as $m)
                                <option value={{ $m->id }}>
                                  {{ $m->level . ' - ' . $m->nominal }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-4">
                            <select name="transport[]" class="form-control" id="">
                              @foreach ($transport as $t)
                                <option value={{ $t->id }}>
                                  {{ $t->level . ' - ' . $t->nominal }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-4">
                            <div class="d-flex align-items-center">
                              <input type="number" class="form-control" name="jam_lembur[]"
                              value="">
                              <button type="button" class="btn btn-danger btn-sm ml-2 hapus-mandor" style="margin: 0"><i class="fa fa-minus"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  {{-- /Clone Node --}}
                  @php($x = 0)
                  @if($bayar)
                  @foreach($bayar as $b)
                    <div class="form-group row">
                      <input type="hidden" name="bayarid[]" value="{{$b->id}}" @if($mandor->approved === 1) disabled @endif>
                      <div class="col-8">
                        <div class="row">
                          <div class="col-4">
                            <select type="text" class="form-control" name="bayartanggal[]" @if($mandor->approved === 1) disabled @endif>
                              @for($i = 0; $i < 7; $i++)
                              <option value="{{ $carbon->parse($tanggal)->addDays($i) }}" @if(old('bayartanggal.'.$x) ? date('Y-m-d', strtotime(old('bayartanggal.'.$x))) === date('Y-m-d', strtotime($carbon->parse($tanggal)->addDays($i))) : date('Y-m-d', strtotime($carbon->parse($tanggal)->addDays($i))) === $b->tanggal) selected @endif>{{ $helper->formatHari($carbon->parse($tanggal)->addDays($i)) }}</option>
                              @endfor
                            </select>
                          </div>
                          <div class="col-8">
                            <select name="bayarproyek[]" class="form-control" id="" @if($mandor->approved === 1) disabled @endif>
                              <option value="">Tidak ada pekerjaan</option>
                              @foreach ($proyek as $p)
                                <option value={{ $p->id }} @if(old('bayarproyek.'.$x) ? old('bayarproyek.'.$x) == $p->id : $b->proyek == $p->id) selected @endif>
                                  {{ $p->nama }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="row">
                          <div class="col-4">
                            <select name="bayaruang_makan[]" class="form-control" id="" @if($mandor->approved === 1) disabled @endif>
                              @foreach ($makan as $m)
                                <option value={{ $m->id }} @if(old('bayaruang_makan.'.$x) ? old('bayaruang_makan.'.$x) == $m->id : $b->makan == $m->id) selected @endif>
                                  {{ $m->level . ' - ' . $m->nominal }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-4">
                            <select name="bayartransport[]" class="form-control" id="" @if($mandor->approved === 1) disabled @endif>
                              @foreach ($transport as $t)
                                <option value={{ $t->id }} @if(old('bayartransport.'.$x) ? old('bayartransport.'.$x) == $t->id : $b->transport == $t->id) selected @endif>
                                  {{ $t->level . ' - ' . $t->nominal }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-4">
                            <div class="d-flex align-items-center">
                              <input type="number" class="form-control" name="bayarjam_lembur[]" @if($mandor->approved === 1) disabled @endif
                              value="{{old('bayarjam_lembur.'.$x) ?? $b->jam_lembur}}">
                              @if($mandor->approved !== 1)
                              <button type="button" class="btn btn-danger btn-sm ml-2 hapus-mandor" style="margin: 0"><i class="fa fa-minus"></i></button>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    @php($x++)
                  @endforeach
                  @endif
                  @if(old('tanggal') && count(old('tanggal')) > 1)
                  @for($x = 1; $x < count(old('tanggal')); $x++)
                  <div class="form-group row">
                    <div class="col-8">
                      <div class="row">
                        <div class="col-4">
                          <select type="text" class="form-control" name="tanggal[]">
                            @for($i = 0; $i < 7; $i++)
                            <option value="{{ $carbon->parse($tanggal)->addDays($i) }}" @if(old('tanggal.'.$x) === date('Y-m-d', strtotime($carbon->parse($tanggal)->addDays($i)))) selected @endif>{{ $helper->formatHari($carbon->parse($tanggal)->addDays($i)) }}</option>
                            @endfor
                          </select>
                        </div>
                        <div class="col-8">
                          <select name="proyek[]" class="form-control" id="">
                            <option value="">Tidak ada pekerjaan</option>
                            @foreach ($proyek as $p)
                              <option value={{ $p->id }} @if(old('proyek.'.$x) == $p->id) selected @endif>
                                {{ $p->nama }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="row">
                        <div class="col-4">
                          <select name="uang_makan[]" class="form-control" id="">
                            @foreach ($makan as $m)
                              <option value={{ $m->id }} @if(old('uang_makan.'.$x) == $m->id) selected @endif>
                                {{ $m->level . ' - ' . $m->nominal }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-4">
                          <select name="transport[]" class="form-control" id="">
                            @foreach ($transport as $t)
                              <option value={{ $t->id }} @if(old('transport.'.$x) == $t->id) selected @endif>
                                {{ $t->level . ' - ' . $t->nominal }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-4">
                          <div class="d-flex align-items-center">
                            <input type="number" class="form-control" name="jam_lembur[]"
                            value="{{old('jam_lembur.'.$x)}}">
                            <button type="button" class="btn btn-danger btn-sm ml-2 hapus-mandor" style="margin: 0"><i class="fa fa-minus"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endfor
                  @endif
                </div>
                  {{-- @endfor --}}
                {{-- @endif --}}
                <div class="form-group row">
                  <div class="col-8">
                    @error('nowork')
                      <small>*{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group row">
                  <div class="col d-flex justify-content-end">
                    <button type="submit" class="btn btn-success" @if($mandor->approved === 1) disabled @endif>Simpan</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- /page content -->
@endsection
