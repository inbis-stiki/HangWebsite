@include('template/header')
@include('template/sidebar')
<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <button style="float: right;" data-toggle="modal" data-target="#mdlAdd" class="btn btn-sm btn-primary">
                    <i class="flaticon-381-add-2"></i>
                    Tambah User
                </button>
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger" style="margin-top: 1rem;">{{ $errors->first() }}</div>
        @endif
        @if (session('succ_msg'))
        <div class="alert alert-success">{{ session('succ_msg') }}</div>
        @endif
        @if (session('err_msg'))
        <div class="alert alert-danger">{{ session('err_msg') }}</div>
        @endif

        <!-- Add Order -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Daftar User</h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama User</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 1;
                                    @endphp
                                    @foreach ($users as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->NAME_USER }}</td>
                                        <td>{{ $item->USERNAME_USER }}</td>
                                        <td>{{ $item->NAME_ROLE }}</td>
                                        <td>
                                            @if (empty($item->deleted_at))
                                            <i class="fa-solid fa-circle mr-2" style="color:#3CC13B;"></i>
                                            Enable
                                            @else
                                            <i class="fa-solid fa-circle mr-2" style="color:#C13B3B;"></i>
                                            Disable
                                            @endif
                                        </td>
                                        <td>
                                            <button onclick="showMdlChangePass('{{ $item->ID_USER }}', '{{ $item->USERNAME_USER }}', '{{ $item->ID_ROLE }}', '{{ $item->ID_AREA }}', '{{ $item->KTP_USER }}', '{{ $item->NAME_USER }}', '{{ $item->EMAIL_USER }}', '{{ $item->TELP_USER }}', '{{ $item->deleted_at }}')" class="btn btn-primary btn-sm">
                                                <i class="flaticon-381-key"></i>
                                            </button>
                                            <button onclick="showMdlEdit('{{ $item->ID_USER }}', '{{ $item->USERNAME_USER }}', '{{ $item->ID_ROLE }}', '{{ $item->ID_AREA }}', '{{ $item->KTP_USER }}', '{{ $item->NAME_USER }}', '{{ $item->EMAIL_USER }}', '{{ $item->TELP_USER }}', '{{ $item->deleted_at }}')" class="btn btn-primary btn-sm">
                                                <i class="flaticon-381-edit-1"></i>
                                            </button>
                                            <button onclick="showMdlDelete('{{ $item->ID_USER }}')" class="btn btn-primary btn-sm">
                                                <i class="flaticon-381-trash-1"></i>
                                            </button>
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
</div>

<!-- Modal Tambah User  -->
<div class="modal fade" id="mdlAdd">
    <div class="modal-dialog" style="max-width:600px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('master/user/store') }}" method="POST">
                    @csrf
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="">Username</label>
                            <input type="text" name="username" class="form-control" onkeypress="return alphaNumSpace(event)" placeholder="Username" required>
                        </div>
                        <div class="col-md-6">
                            <label for="">Nama User</label>
                            <input type="text" name="name" class="form-control" onkeypress="return alpha(event)" placeholder="Nama User" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6 ">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="">Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="Telepon" onkeypress="return num(event)" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6 ">
                            <label for="">No. KTP</label>
                            <input type="text" name="ktp" class="form-control" onkeypress="return num(event)" placeholder="No.KTP" required>
                        </div>
                        <div class="col-md-6">
                            <label for="">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6 ">
                            <label for="">Role</label>
                            <select name="role" id="role_add" class="select2" required>
                                <option selected disabled value="">Pilih Role</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role->ID_ROLE }}">{{ $role->NAME_ROLE }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 ">
                            <label for="">Area</label>
                            <select name="area" id="area_add" class="select2" required>
                                <option selected disabled value="">Pilih Area</option>
                                @foreach ($areas as $area)
                                <option value="{{ $area->ID_AREA }}">{{ $area->NAME_AREA }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="">Status</label>
                            <div class="form-group mb-0">
                                <label class="radio-inline mr-3"><input type="radio" name="status" value="1" required> Enable</label>
                                <label class="radio-inline mr-3"><input type="radio" name="status" value="0" required> Disable</label>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal" onclick="this.form.reset();">Batalkan</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit  -->
<div class="modal fade" id="mdlEdit">
    <div class="modal-dialog modal-lg" style="max-width:600px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('master/user/update') }}">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="">Username</label>
                            <input type="text" id="username_edit" name="username" class="form-control" onkeypress="return alphaNumSpace(event)" placeholder="Username" required>
                        </div>
                        <div class="col-md-6">
                            <label for="">Nama User</label>
                            <input type="text" id="name_edit" name="name" class="form-control" onkeypress="return alpha(event)" placeholder="Nama User" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6 ">
                            <label for="">Email</label>
                            <input type="email" id="email_edit" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="">Telepon</label>
                            <input type="text" id="telepon_edit" name="phone" class="form-control" onkeypress="return num(event)" placeholder="Telepon" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6 ">
                            <label for="">No. KTP</label>
                            <input type="text" id="ktp_edit" name="ktp" class="form-control" onkeypress="return num(event)" placeholder="No.KTP" required>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6 ">
                            <label for="">Role</label>
                            <select name="role" id="role_edit" class="select2" required>
                                <option selected disabled value="">Pilih Role</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role->ID_ROLE }}">{{ $role->NAME_ROLE }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 ">
                            <label for="">Area</label>
                            <select name="area" id="area_edit" class="select2" required>
                                <option selected disabled value="">Pilih Area</option>
                                @foreach ($areas as $area)
                                <option value="{{ $area->ID_AREA }}">{{ $area->NAME_AREA }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="">Status</label>
                            <div class="form-group mb-0">
                                <label class="radio-inline mr-3"><input type="radio" id="status_enable" name="status" value="1" required> Enable</label>
                                <label class="radio-inline mr-3"><input type="radio" id="status_disable" name="status" value="0" required> Disable</label>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="id" id="mdlEdit_id">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Batalkan</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete  -->
<div class="modal fade" id="mdlDelete">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin untuk menghapus user?</p>
            </div>
            <form action="{{ url('master/user/destroy') }}">
                <div class="modal-footer">
                    <input type="hidden" name="id" id="mdlDelete_id">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-primary">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Change Password  -->
<div class="modal fade" id="mdlChangePass">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Password</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('master/user/changePass') }}" method="POST">
                @csrf
                    <div class="form-group">
                    <label for="">Password Baru</label>
                    <input type="text" class="form-control" name="pass">
                </div>
            </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="mdlChangePass_id">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--**********************************
    Content body end
***********************************-->
@include('template/footer')
<script>
    $('#datatable').DataTable()

    $(document).ready(function() {
        $("#role_add").change(function() {
            var val = $(this).val();
            $("#area_add").html()
            if  (val == "1"){
                $("#area_add").html("<option selected disabled value=''>Pilih Area</option>@foreach ($areas as $area)<option value='{{ $area->ID_AREA }}'>{{ $area->NAME_AREA }}</option>@endforeach");
            }else if (val == "2") {
                $("#area_add").html("<option selected disabled value=''>Pilih Area</option>@foreach ($areas as $area)<option value='{{ $area->ID_AREA }}'>{{ $area->NAME_AREA }}</option>@endforeach");
            }else if (val == "3") {
                $("#area_add").html("<option selected disabled value=''>Pilih Area</option>@foreach ($location as $loc)<option value='{{ $loc->ID_LOCATION }}'>{{ $loc->NAME_LOCATION }}</option>@endforeach");
            } else if (val == "4") {
                $("#area_add").html("<option selected disabled value=''>Pilih Area</option>@foreach ($regional as $reg)<option value='{{ $reg->ID_REGIONAL }}'>{{ $reg->NAME_REGIONAL }}</option>@endforeach");
            } else if (val == "5") {
                $("#area_add").html("<option selected disabled value=''>Pilih Area</option>@foreach ($areas as $area)<option value='{{ $area->ID_AREA }}'>{{ $area->NAME_AREA }}</option>@endforeach");
            } else if (val == "6") {
                $("#area_add").html("<option selected disabled value=''>Pilih Area</option>@foreach ($areas as $area)<option value='{{ $area->ID_AREA }}'>{{ $area->NAME_AREA }}</option>@endforeach");
            }
        });
    });

    function showMdlEdit(id, username, idrole, area, ktp, name, email, phone, status) {
        $('#mdlEdit_id').val(id)
        $('#username_edit').val(username)
        $('#ktp_edit').val(ktp)
        $('#name_edit').val(name)
        $('#email_edit').val(email)
        $('#telepon_edit').val(phone)
        $('#role_edit').val(idrole).change()
        $('#area_edit').val(area).change()
        if (status == null || status == '') {
            $('#status_enable').prop('checked', true)
        } else {
            $('#status_disable').prop('checked', true)
        }

        $('#mdlEdit').modal('show');
    }

    function showMdlDelete(id) {
        $('#mdlDelete_id').val(id)
        $('#mdlDelete').modal('show');
    }
    function showMdlChangePass(id) {
        $('#mdlChangePass_id').val(id)
        $('#mdlChangePass').modal('show');
    }
</script>