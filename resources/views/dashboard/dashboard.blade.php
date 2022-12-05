@extends('dashboard.header');

@section('halaman_admin')
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="row">
      <div class="col-md-12 col-sm-12 ">
        <div class="dashboard_graph">
          <div class="row x_title">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3>Daftar User</h3>
                <button class="btn btn-primary"><i class="fa fa-plus"></i></button>
              </div>
            </div>
          </div>
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
                <tr>
                  <td>1</td>
                  <td>Syaiful</td>
                  <td>Owner</td>
                  <td>
                    <button class="btn btn-secondary"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- /page content -->
@endsection
