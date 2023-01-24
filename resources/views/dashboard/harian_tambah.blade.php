@extends('dashboard.header');

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3>Tambah Gaji Tukang Harian</h3>
                <div class="d-flex align-items-center justify-content-end">
                  <a href="{{ url('/dashboard/tukang_harian') }}" class="btn btn-primary"><i
                      class="fa fa-arrow-left"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="x_content">
            <div class="col-12">
              <form class="form-label-left input_mask" action="{{ url('/dashboard/tukang_harian_aksi') }}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tukang</label>
                  <div class="col-md-9 col-sm-9">
                    <div style="background: #c0c0c0; border-radius: 50px; width: fit-content; margin-bottom: 20px">
                      <button type="button"
                        class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 trigger_harian_a @if(old('tukang_harian') || old('tukang_mandor')) @else bg-primary @endif"
                        style="border-radius: 50px">Input Baru</button>
                      <button type="button"
                        class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 trigger_harian_b @if(old('tukang_harian')) bg-primary @endif"
                        style="border-radius: 50px">Tukang Harian</button>
                      <button type="button"
                        class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 trigger_harian_c @if(old('tukang_mandor')) bg-primary @endif"
                        style="border-radius: 50px">Tukang Mandor</button>
                    </div>
                    <div class="row target_harian_a @if(old('tukang_harian') || old('tukang_mandor')) d-none @endif">
                      <div class="col-12">
                        <div class="form-group row">
                          <label class="col-form-label col-md-3 col-sm-3 ">Nama</label>
                          <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control" placeholder="Masukkan Nama Tukang" name="nama"
                              value="{{ old('nama') }}" @if(old('tukang_harian') || old('tukang_mandor')) disabled @endif>
                            <small class="warning">(wajib diisi)</small>
                            @error('nama')
                              <small>*{{ $message }}</small>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-md-3 col-sm-3 ">Alamat</label>
                          <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control" placeholder="Masukkan Alamat Tukang" name="alamat"
                              value="{{ old('alamat') }}" @if(old('tukang_harian') || old('tukang_mandor')) disabled @endif>
                            <small class="warning">(opsional)</small>
                            @error('alamat')
                              <small>*{{ $message }}</small>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-md-3 col-sm-3 ">HP</label>
                          <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control" placeholder="Masukkan Nomor HP Tukang"
                              name="telp" value="{{ old('telp') }}" @if(old('tukang_harian') || old('tukang_mandor')) disabled @endif>
                            <small class="warning">(opsional)</small>
                            @error('telp')
                              <small>*{{ $message }}</small>
                            @enderror
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row target_harian_b @if(old('tukang_harian')) @elseif(!old('nama') || !old('tukang_mandor')) d-none @else d-none @endif">
                      <div class="col-12">
                        <div class="form-group row">
                          <label class="col-form-label col-md-3 col-sm-3 ">Nama Tukang</label>
                          <div class="col-md-9 col-sm-9 ">
                            <select name="tukang_harian" class="form-control" id="" @if(old('tukang_harian')) @elseif(!old('nama') || !old('tukang_mandor')) disabled @else disabled @endif>
                              @foreach ($tukang_harian as $t)
                                <option value={{ $t->id }} @if (old('tukang_harian') == $t->id) selected @endif>
                                  {{ $t->nama }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row target_harian_c @if(old('tukang_mandor')) @elseif(!old('nama') || !old('tukang_harian')) d-none @else d-none @endif">
                      <div class="col-12">
                        <div class="form-group row">
                          <label class="col-form-label col-md-3 col-sm-3 ">Nama Tukang</label>
                          <div class="col-md-9 col-sm-9 ">
                            <select name="tukang_mandor" class="form-control" id="" @if(old('tukang_mandor')) @elseif(!old('nama') || !old('tukang_harian')) disabled @else disabled @endif>
                              @foreach ($tukang_mandor as $t)
                                <option value={{ $t->id }} @if (old('tukang_mandor') == $t->id) selected @endif>
                                  {{ $t->namamandor.' - '.$t->nama }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Pembayaran</label>
                  <div class="col-md-9 col-sm-9">
                    <div class="form-group row">
                      <label class="col-form-label col-md-3 col-sm-3 ">Pokok</label>
                      <div class="col-md-9 col-sm-9 ">
                        <input type="number" class="form-control" placeholder="Masukkan Pokok Gaji" name="pokok"
                          value="{{ old('pokok') }}">
                        @error('pokok')
                          <small>*{{ $message }}</small>
                        @enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-md-3 col-sm-3 ">Insentif</label>
                      <div class="col-md-9 col-sm-9 ">
                        <input type="number" class="form-control" placeholder="Masukkan Insentif" name="insentif"
                          value="{{ old('insentif') }}">
                        <small class="warning">(opsional)</small>
                        @error('insentif')
                          <small>*{{ $message }}</small>
                        @enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-md-3 col-sm-3 ">Lembur</label>
                      <div class="col-md-9 col-sm-9 ">
                        <input type="number" class="form-control" placeholder="Masukkan Gaji Lembur per Jam"
                          name="lembur" value="{{ old('lembur') }}">
                        @error('lembur')
                          <small>*{{ $message }}</small>
                        @enderror
                      </div>
                    </div>

                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 "></label>
                  <div class="col-md-9 col-sm-9">
                    <div class="d-flex align-items-center">
                      <input type="text" class="form-control bo-datepicker" readonly style="background: transparent"
                        name="setdate" value="{{old('setdate')}}">
                      <button type="button" class="btn btn-primary ml-2 atur-harian"
                        style="margin: 0; white-space: nowrap">Atur Tanggal</button>
                    </div>
                    <small class="warning">*Masukkan tanggal pertama tukang bekerja</small>
                  </div>
                </div>
                <div class="target_jadwal @if(old('tukang_harian') || old('tukang_mandor') || old('nama')) @else d-none @endif">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3 ">Jadwal Harian</label>
                  </div>
                  <div class="form-group row">
                    <div class="col-8">
                      <div class="row">
                        <div class="col-3">
                          <label class="col-form-label">Tanggal</label>
                        </div>
                        <div class="col-3">
                          <label class="col-form-label">Proyek</label>
                        </div>
                        <div class="col-6">
                          <label class="col-form-label">Keterangan</label>
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
                  @for ($x = 0; $x < 7; $x++)
                    <div class="form-group row">
                      <div class="col-8">
                        <div class="row">
                          <div class="col-3">
                            <input type="hidden" class="form-control" name="tanggal[]" value="{{old('tanggal.'.$x)}}">
                            <input type="text" class="form-control" name="tampil[]" readonly value="{{old('tampil.'.$x)}}">
                          </div>
                          <div class="col-3">
                            <select name="proyek[]" class="form-control" id="">
                              <option value="">Tidak ada pekerjaan</option>
                              @foreach ($proyek as $p)
                                <option value={{ $p->id }} @if (old('proyek.' . $x) == $p->id) selected @endif>
                                  {{ $p->nama }}</option>
                              @endforeach
                            </select>
                            @error('proyek.'.$x)
                            <small>*{{ $message }}</small>
                          @enderror
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="keterangan[]"
                              value="{{ old('keterangan.' . $x) }}">
                              @error('keterangan.'.$x)
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
                              @error('jam_lembur.'.$x)
                              <small>*{{ $message }}</small>
                            @enderror
                          </div>
                          <div class="col-4">
                            <select name="uang_makan[]" class="form-control" id="" disabled>
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
                  <div class="ln_solid"></div>
                  <div class="form-group row">
                    <div class="col d-flex justify-content-end">
                      <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
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
