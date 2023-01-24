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
                  <a href="{{ url('/dashboard/tukang_mandor') }}" class="btn btn-primary"><i
                      class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{ url('/dashboard/tukang_mandor_aksi') }}" method="post">
                @csrf
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
                    <select class="form-control" id="" name="gaji_mandor">
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
                        <label class="col-form-label">Lembur</label>
                      </div>
                      <div class="col-4">
                        <label class="col-form-label">Uang Makan</label>
                      </div>
                      <div class="col-4">
                        <label class="col-form-label">Transport</label>
                      </div>
                    </div>
                  </div>
                </div>
                @php($bayar = DB::table('gaji_mandor_tukang')->where('mandor_tukang', $tukang->id)->orderBy('tanggal', 'asc')->get())
                @php($tanggalacuan = $mandor->tanggal)
                @php($subnum = $carbon->parse($tanggalacuan)->getDaysFromStartOfWeek())
                @php($tanggal = $carbon->parse($tanggalacuan)->subDays($subnum))
                @php($i = 0)
                @if (count($bayar) > 0)
                  @for ($x = 0; $x < 7; $x++)
                    <div class="form-group row">
                      <div class="col-8">
                        <div class="row">
                          @php($cektanggal = date('Y-m-d', strtotime($carbon->parse($tanggal)->addDays($x))))
                          <input type="hidden" name="bayarid[]"
                            value={{ $cektanggal == $bayar[$i]->tanggal ? $bayar[$i]->id : '' }}>
                          <div class="col-4">
                            <input type="hidden" class="form-control" name="tanggal[]"
                              value="{{ $carbon->parse($tanggal)->addDays($x) }}">
                            <input type="text" class="form-control" name="tampil[]" readonly
                              value="{{ $helper->formatHari($carbon->parse($tanggal)->addDays($x)) }}">
                          </div>
                          <div class="col-8">
                            <select name="proyek[]" class="form-control" id="">
                              <option value="">Tidak ada pekerjaan</option>
                              @foreach ($proyek as $p)
                                <option value={{ $p->id }} @if (old('proyek.' . $x)
                                        ? old('proyek.' . $x) == $p->id
                                        : ($cektanggal == $bayar[$i]->tanggal
                                            ? $bayar[$i]->proyek == $p->id
                                            : false)) selected @endif>
                                  {{ $p->nama }}</option>
                              @endforeach
                            </select>
                            @error('proyek.' . $x)
                              <small>*{{ $message }}</small>
                            @enderror
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="row">
                          <div class="col-4">
                            <input type="number" class="form-control" name="jam_lembur[]"
                              value="{{ old('jam_lembur.' . $x) ?? $cektanggal == $bayar[$i]->tanggal ? $bayar[$i]->jam_lembur : '' }}">
                            @error('jam_lembur.' . $x)
                              <small>*{{ $message }}</small>
                            @enderror
                          </div>
                          <div class="col-4">
                            <select name="uang_makan[]" class="form-control" id="">
                              @foreach ($makan as $m)
                                <option value={{ $m->id }} @if (old('uang_makan.' . $x)
                                        ? old('uang_makan.' . $x) == $m->id
                                        : ($cektanggal == $bayar[$i]->tanggal
                                            ? $bayar[$i]->makan == $m->id
                                            : false)) selected @endif>
                                  {{ $m->level . ' - ' . $m->nominal }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-4">
                            <select name="transport[]" class="form-control" id="">
                              @foreach ($transport as $t)
                                <option value={{ $t->id }} @if (old('transport.' . $x)
                                        ? old('transport.' . $x) == $t->id
                                        : ($cektanggal == $bayar[$i]->tanggal
                                            ? $bayar[$i]->transport == $t->id
                                            : false)) selected @endif>
                                  {{ $t->level . ' - ' . $t->nominal }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    @if ($cektanggal == $bayar[$i]->tanggal && $i < count($bayar) - 1)
                      @php($i++)
                    @endif
                  @endfor
                @else
                  @for ($x = 0; $x < 7; $x++)
                    <div class="form-group row">
                      <div class="col-8">
                        <div class="row">
                          @php($cektanggal = date('Y-m-d', strtotime($carbon->parse($tanggal)->addDays($x))))
                          <div class="col-4">
                            <input type="hidden" name="bayarid[]"
                            value="">
                            <input type="hidden" class="form-control" name="tanggal[]"
                              value="{{ $carbon->parse($tanggal)->addDays($x) }}">
                            <input type="text" class="form-control" name="tampil[]" readonly
                              value="{{ $helper->formatHari($carbon->parse($tanggal)->addDays($x)) }}">
                          </div>
                          <div class="col-8">
                            <select name="proyek[]" class="form-control" id="">
                              <option value="">Tidak ada pekerjaan</option>
                              @foreach ($proyek as $p)
                                <option value={{ $p->id }} @if (old('proyek.' . $x) == $p->id) selected @endif>
                                  {{ $p->nama }}</option>
                              @endforeach
                            </select>
                            @error('proyek.' . $x)
                              <small>*{{ $message }}</small>
                            @enderror
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="row">
                          <div class="col-4">
                            <input type="number" class="form-control" name="jam_lembur[]"
                              value="{{ old('jam_lembur.' . $x) }}">
                            @error('jam_lembur.' . $x)
                              <small>*{{ $message }}</small>
                            @enderror
                          </div>
                          <div class="col-4">
                            <select name="uang_makan[]" class="form-control" id="">
                              @foreach ($makan as $m)
                                <option value={{ $m->id }} @if (old('uang_makan.' . $x) == $m->id) selected @endif>
                                  {{ $m->level . ' - ' . $m->nominal }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-4">
                            <select name="transport[]" class="form-control" id="">
                              @foreach ($transport as $t)
                                <option value={{ $t->id }} @if (old('transport.' . $x) == $t->id) selected @endif>
                                  {{ $t->level . ' - ' . $t->nominal }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endfor
                @endif
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
                    <button type="submit" class="btn btn-success">Simpan</button>
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
