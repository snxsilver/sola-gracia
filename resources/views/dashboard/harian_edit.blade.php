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
                <h3>Edit Gaji Tukang Harian @if($harian->approved === 1) <span data-toggle="tooltip"
                  data-placement="bottom" title="Sudah disetujui"><i class="fa fa-check"></i></span> @endif</h3>
                <div class="d-flex align-items-center justify-content-end">
                  @if($harian->approved === 1)
                  @if (Session::get('role') === 'owner' || Session::get('role') === 'manager')
                  <a data-whatever="{{ url('/dashboard/tukang_harian_disapprove/'.$harian->id) }}" style="cursor: pointer" data-toggle="modal"
                  data-target="#hapusitem" class="btn btn-warning text-white"><i
                      class="fa fa-unlock" data-toggle="tooltip"
                      data-placement="bottom" title="Batalkan persetujuan"></i></a>
                  @endif
                  @endif
                  <a href="{{ url('/dashboard/tukang_harian') }}" class="btn btn-primary"><i
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
              <form class="form-label-left input_mask" action="{{ url('/dashboard/tukang_harian_update') }}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-form-label col-md-3 col-sm-3 ">Tukang</label>
                  <div class="col-md-9 col-sm-9">
                    <div style="background: #c0c0c0; border-radius: 50px; width: fit-content; margin-bottom: 20px">
                      <button type="button"
                        class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 @if($harian->approved !== 1) trigger_harian_a @endif @if(old('nama')) bg-primary @endif"
                        style="border-radius: 50px">Input Baru</button>
                      @php($cektukang = DB::table('tukang')->where('id',$harian->tukang)->where('mandor','0')->first())
                      <button type="button"
                        class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 @if($harian->approved !== 1)trigger_harian_b @endif @if(old('nama') || old('tukang_mandor')) @elseif(old('tukang_harian') || $cektukang)) bg-primary @endif"
                        style="border-radius: 50px">Tukang Harian</button>
                      <button type="button"
                        class="btn btn-sm px-3 py-2 text-white font-weight-bold mb-0 mr-0 @if($harian->approved !== 1) trigger_harian_c @endif @if(old('nama') || old('tukang_harian')) @elseif(old('tukang_mandor') || !$cektukang)) bg-primary @endif"
                        style="border-radius: 50px">Tukang Mandor</button>
                    </div>
                    <div class="row target_harian_a @if(old('nama')) @else d-none @endif">
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
                    <div class="row target_harian_b @if(old('nama') || old('tukang_mandor')) d-none @elseif(old('tukang_harian')) @elseif(!$cektukang) d-none @endif">
                      <div class="col-12">
                        <div class="form-group row">
                          <label class="col-form-label col-md-3 col-sm-3 ">Nama Tukang</label>
                          <div class="col-md-9 col-sm-9 ">
                            <select name="tukang_harian" class="form-control" id="" @if(old('nama') || old('tukang_mandor') || $harian->approved === 1) disabled @elseif(old('tukang_harian')) @elseif(!$cektukang) d-none @endif>
                              @foreach ($tukang_harian as $t)
                                <option value={{ $t->id }} @if (old('tukang_harian') ? old('tukang_harian') == $t->id : $harian->tukang == $t->id) selected @endif>
                                  {{ $t->nama }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row target_harian_c @if(old('nama') || old('tukang_harian')) d-none @elseif(old('tukang_mandor')) @elseif($cektukang) d-none @endif">
                      <div class="col-12">
                        <div class="form-group row">
                          <label class="col-form-label col-md-3 col-sm-3 ">Nama Tukang</label>
                          <div class="col-md-9 col-sm-9 ">
                            <select name="tukang_mandor" class="form-control" id="" @if(old('nama') || old('tukang_harian')) disabled @elseif(old('tukang_mandor')) @elseif($cektukang) disabled @endif>
                              @foreach ($tukang_mandor as $t)
                                <option value={{ $t->id }} @if (old('tukang_mandor') ? old('tukang_mandor') == $t->id : $harian->tukang == $t->id) selected @endif>
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
                        <input type="hidden" name="id" value="{{$harian->id}}">
                        <input type="number" class="form-control" placeholder="Masukkan Pokok Gaji" name="pokok"
                          value="{{ old('pokok') ?? $harian->pokok }}" @if($harian->approved === 1) disabled @endif>
                        @error('pokok')
                          <small>*{{ $message }}</small>
                        @enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-md-3 col-sm-3 ">Insentif</label>
                      <div class="col-md-9 col-sm-9 ">
                        <input type="number" class="form-control" placeholder="Masukkan Insentif" name="insentif"
                          value="{{ old('insentif') ?? $harian->insentif }}" @if($harian->approved === 1) disabled @endif>
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
                          name="lembur" value="{{ old('lembur') ?? $harian->lembur }}" @if($harian->approved === 1) disabled @endif>
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
                        name="setdate" value="{{old('setdate')}}" @if($harian->approved === 1) disabled @endif>
                      <button type="button" class="btn btn-primary ml-2 atur-harian"
                        style="margin: 0; white-space: nowrap" @if($harian->approved === 1) disabled @endif>Atur Tanggal</button>
                    </div>
                    <small class="warning">*Masukkan tanggal pertama tukang bekerja</small>
                  </div>
                </div>
                <div class="target_jadwal">
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
                  @php($query = DB::table('harian_bayar')->where('harian',$harian->id)->orderBy('tanggal','desc')->first())
                  @php($bayar = DB::table('harian_bayar')->where('harian',$harian->id)->orderBy('tanggal','asc')->get())
                  @php($tanggalacuan = $query->tanggal)
                  @php($subnum = $carbon->parse($tanggalacuan)->getDaysFromStartOfWeek())
                  @php($tanggal = $carbon->parse($tanggalacuan)->subDays($subnum))
                  @php($i = 0)
                  @for ($x = 0; $x < 7; $x++)
                    <div class="form-group row">
                      <div class="col-8">
                        <div class="row">
                          @php($cektanggal = date('Y-m-d', strtotime($carbon->parse($tanggal)->addDays($x))))
                          <input type="hidden" name="bayarid[]" value={{$cektanggal == $bayar[$i]->tanggal ? $bayar[$i]->id : ''}}>
                          <div class="col-3">
                            <input type="hidden" class="form-control" name="tanggal[]" value="{{old('tanggal.'.$x) ?? $carbon->parse($tanggal)->addDays($x)}}">
                            <input type="text" class="form-control" name="tampil[]" readonly value="{{old('tampil.'.$x) ?? $helper->formatHari($carbon->parse($tanggal)->addDays($x))}}" @if($harian->approved === 1) disabled @endif>
                          </div>
                          <div class="col-3">
                            <select name="proyek[]" class="form-control" id="" @if($harian->approved === 1) disabled @endif>
                              <option value="">Tidak ada pekerjaan</option>
                              @foreach ($proyek as $p)
                                <option value={{ $p->id }}
                                  @if (old('proyek.' . $x) ? old('proyek.' . $x) == $p->id : ($cektanggal == $bayar[$i]->tanggal ? $bayar[$i]->proyek == $p->id : false )) selected @endif
                                  >
                                  {{ $p->nama }}</option>
                              @endforeach
                            </select>
                            @error('proyek.'.$x)
                            <small>*{{ $message }}</small>
                          @enderror
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="keterangan[]" @if($harian->approved === 1) disabled @endif
                              value="{{ old('keterangan.' . $x) ?? $cektanggal == $bayar[$i]->tanggal ? $bayar[$i]->keterangan : '' }}">
                              @error('keterangan.'.$x)
                              <small>*{{ $message }}</small>
                            @enderror
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="row">
                          <div class="col-4">
                            <input type="number" class="form-control" name="jam_lembur[]" @if($harian->approved === 1) disabled @endif
                              value="{{ old('jam_lembur.' . $x) ?? $cektanggal == $bayar[$i]->tanggal ? $bayar[$i]->jam : '' }}">
                              @error('jam_lembur.'.$x)
                              <small>*{{ $message }}</small>
                            @enderror
                          </div>
                          <div class="col-4">
                            <select name="uang_makan[]" class="form-control" id="" disabled>
                              @foreach ($makan as $m)
                                <option value={{ $m->id }}
                                  @if (old('uang_makan.' . $x) ? old('uang_makan.' . $x) == $m->id : ($cektanggal == $bayar[$i]->tanggal ? $bayar[$i]->makan == $m->id : false )) selected @endif
                                  >
                                  {{ $m->level . ' - ' . $m->nominal }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-4">
                            <select name="transport[]" class="form-control" id="" @if($harian->approved === 1) disabled @endif>
                              @foreach ($transport as $t)
                                <option value={{ $t->id }}
                                  @if (old('transport.' . $x) ? old('transport.' . $x) == $t->id : ($cektanggal == $bayar[$i]->tanggal ? $bayar[$i]->transport == $t->id : false )) selected @endif
                                  >
                                  {{ $t->level . ' - ' . $t->nominal }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    @if($cektanggal == $bayar[$i]->tanggal && $i < (count($bayar) - 1))
                    @php($i++)
                    @endif
                  @endfor
                  <div class="ln_solid"></div>
                  <div class="form-group row">
                    @error('nowork')
                    <small>*{{ $message }}</small>
                    @enderror
                    <div class="col d-flex justify-content-end">
                      <button type="submit" class="btn btn-success" @if($harian->approved === 1) disabled @endif>Simpan</button>
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
