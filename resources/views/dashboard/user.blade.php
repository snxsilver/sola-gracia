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
                                <h3>Daftar User</h3>
                                <a class="btn btn-primary" href="{{url('/dashboard/user_tambah')}}"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="x_content">
                        <div class="col-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php ($no = 1)
                                    @foreach ($user as $u)
                                    <tr>
                                        <td>{{$no++}}</td>
                                        <td>{{$u->username}}</td>
                                        <td>{{$u->role}}</td>
                                        <td>
                                            <a class="btn btn-secondary" href="{{url('/dashboard/user_edit/'.$u->id)}}"><i class="fa fa-pencil"></i></a>
                                            <a class="btn btn-danger" href="{{url('/dashboard/user_hapus/'.$u->id)}}"><i class="fa fa-trash"></i></a>
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
