@extends('dashboard.header');

@section('halaman_admin')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3>Buku Kas</h3>
                                <div class="align-items-center">
                                    <a class="btn btn-success text-sm btn-sm fw-semibold" href="{{url('/dashboard/ambil_stok')}}"><span class="text-white mr-1"><i class="fa fa-plus"></i></span>Ambil Stok</a>
                                    <a class="btn btn-primary" href="{{url('/dashboard/bukukas_tambah')}}"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="x_content">
                        <div class="col-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Proyek</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Kategori</th>
                                        <th>No Bukti</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php ($no = 1)
                                    @foreach ($bukukas as $b)
                                    <tr>
                                        <td width="5%">{{$no++}}</td>
                                        <td>{{$b->namaproyek}}</td>
                                        <td>{{$b->tanggal}}</td>
                                        <td style="white-space: pre-line" width="25%">{{$b->keterangan}}</td>
                                        <td>{{$b->namakategori}}</td>
                                        <td>{{$b->no_bukti ?? '-'}}</td>
                                        <td>{{$b->masuk ?? '-'}}</td>
                                        <td>{{$b->keluar ?? '-'}}</td>
                                        <td>
                                            @if($b->ambil_stok == 0)
                                            <a class="btn btn-sm btn-secondary" href="{{url('/dashboard/bukukas_edit/'.$b->id)}}"><i class="fa fa-pencil"></i></a>
                                            <a class="btn btn-sm btn-danger" href="{{url('/dashboard/bukukas_hapus/'.$b->id)}}"><i class="fa fa-trash"></i></a>
                                            @else
                                            <a class="btn btn-sm btn-secondary" href="{{url('/dashboard/ambil_stok_edit/'.$b->id)}}"><i class="fa fa-pencil"></i></a>
                                            <a class="btn btn-sm btn-danger" href="{{url('/dashboard/ambil_stok_hapus/'.$b->id)}}"><i class="fa fa-trash"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection
