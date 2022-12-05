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
                                <a class="btn btn-primary" href="{{url('/dashboard/bukukas_tambah')}}"><i class="fa fa-plus"></i></a>
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
                                        <td>{{$no++}}</td>
                                        <td>{{$b->namaproyek}}</td>
                                        <td>{{$b->tanggal}}</td>
                                        <td>{{$b->keterangan}}</td>
                                        <td>{{$b->namakategori}}</td>
                                        <td>{{$b->no_bukti ?? '-'}}</td>
                                        <td>{{$b->masuk ?? '-'}}</td>
                                        <td>{{$b->keluar ?? '-'}}</td>
                                        <td>
                                            <a class="btn btn-secondary" href="{{url('/dashboard/bukukas_edit/'.$b->id)}}"><i class="fa fa-pencil"></i></a>
                                            <a class="btn btn-danger" href="{{url('/dashboard/bukukas_hapus/'.$b->id)}}"><i class="fa fa-trash"></i></a>
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
