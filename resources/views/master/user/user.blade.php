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
                <button style="float: right;" data-toggle="modal" data-target="#mdlAdd"  class="btn btn-sm btn-primary">
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
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Nasional</th>
                                        <th>Regional</th>
                                        <th>Area</th>
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
                                        <td>{{ $item->NAME_LOCATION }}</td>                                        
                                        <td>{{ $item->NAME_REGIONAL }}</td>                                       
                                        <td>{{ $item->NAME_AREA }}</td>
                                        <td>
                                            <button onclick="showMdlEdit('{{ $item->ID_USER }}', '{{ $item->USERNAME_USER }}', '{{ $item->ID_ROLE }}', '{{ $item->ID_LOCATION }}', '{{ $item->ID_REGIONAL }}', '{{ $item->ID_AREA }}', '{{ $item->KTP_USER }}', '{{ $item->NAME_USER }}', '{{ $item->EMAIL_USER }}', '{{ $item->TELP_USER }}')" class="btn btn-primary btn-sm">
                                                <i class="flaticon-381-edit-1"></i>
                                            </button>
                                            <button onclick="showMdlDelete('{{ $item->ID_USER }}')" class="btn btn-primary btn-sm">
                                                <i class="flaticon-381-trash-1"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Nasional</th>
                                        <th>Regional</th>
                                        <th>Area</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Category Product  -->
<div class="modal fade" id="mdlAdd">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="{{ url('master/user/store') }}">
                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="col-md-6">
                        <label for="">Nama User</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama User" required>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6 ">                        
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-md-6">
                        <label for="">Telepon</label>
                        <input type="text" name="phone" class="form-control" placeholder="Telepon" required>
                    </div>
                </div>                 
                <div class="row form-group">
                    <div class="col-md-6 ">                        
                        <label for="">No. KTP</label>
                        <input type="number" name="ktp" class="form-control" placeholder="No.KTP" required>
                    </div>
                    <div class="col-md-6">    
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4">                        
                        <label for="">Location</label>
                        <select id="nasional" name="nasional" class="select2" required>
                            <option selected disabled value="">Pilih Nasional</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->ID_LOCATION }}">{{ $location->NAME_LOCATION }}</option>                            
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">  
                        <label for="">&nbsp;</label>
                        <select id="regional" name="regional" class="select2" required>
                            <option selected disabled value="">Pilih Regional</option>                        
                        </select>
                    </div>
                    <div class="col-md-4">  
                        <label for="">&nbsp;</label>
                        <select id="area" name="area" class="select2" required>
                            <option selected disabled value="">Pilih Area</option>                        
                        </select>
                    </div>
                </div> 
                <div class="row form-group">
                    <div class="col-md-4 ">                        
                        <label for="">Role</label>
                        <select name="role" class="select2" required>
                        <option selected disabled value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->ID_ROLE }}">{{ $role->NAME_ROLE }}</option>                            
                        @endforeach
                    </select>
                    </div>
                    <div class="col-md-6">                        
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Batalkan</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit  -->
<div class="modal fade" id="mdlEdit">
    <div class="modal-dialog modal-lg" role="document">
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
                        <input type="text" id="username_edit" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="col-md-6">
                        <label for="">Nama User</label>
                        <input type="text" id="name_edit" name="name" class="form-control" placeholder="Nama User" required>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6 ">                        
                        <label for="">Email</label>
                        <input type="email" id="email_edit" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-md-6">
                        <label for="">Telepon</label>
                        <input type="text" id="phone_edit" name="phone" class="form-control" placeholder="Telepon" required>
                    </div>
                </div>                 
                <div class="row form-group">
                    <div class="col-md-6 ">                        
                        <label for="">No. KTP</label>
                        <input type="number" id="ktp_edit" name="ktp" class="form-control" placeholder="No.KTP" required>
                    </div>
                    <div class="col-md-6">    
                        <label for="">&nbsp;</label>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4">                        
                        <label for="">Location</label>
                        <select id="location_edit" name="nasional" class="select2" required>
                            <option selected disabled value="">Pilih Nasional</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->ID_LOCATION }}">{{ $location->NAME_LOCATION }}</option>                            
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">  
                        <label for="">&nbsp;</label>
                        <select id="regional_edit" name="regional" class="select2" required>
                            <option selected disabled value="">Pilih Regional</option>                        
                        </select>
                    </div>
                    <div class="col-md-4">  
                        <label for="">&nbsp;</label>
                        <select id="area_edit" name="area" class="select2" required>
                            <option selected disabled value="">Pilih Area</option>                        
                        </select>
                    </div>
                </div> 
                <div class="row form-group">
                    <div class="col-md-4 ">                        
                        <label for="">Role</label>
                        <select id="role_edit" name="role" class="select2" required>
                        <option selected disabled value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->ID_ROLE }}">{{ $role->NAME_ROLE }}</option>                            
                        @endforeach
                    </select>
                    </div>
                    <div class="col-md-6">                        
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
                <button type="submit" class="btn btn-primary">Simpan</button>
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

    function showMdlEdit(id, username, idrole, idlocation, idregional, idarea, ktp, name, email, phone){
        $('#mdlEdit_id').val(id)
        $('#username_edit').val(username)
        $('#ktp_edit').val(ktp)
        $('#name_edit').val(name)
        $('#email_edit').val(email)
        $('#phone_edit').val(phone)
        $('#role_edit').val(idrole).change()
        $('#location_edit').val(idlocation).change()

        $.ajax({
            url:'/getRegional',
            type:'post',
            data:'idnasional='+idlocation+'&_token={{csrf_token()}}',
            success:function(result){
                $('#regional_edit').html(result)
                $('#regional_edit').val(idregional).change()

                $.ajax({
                    url:'/getArea',
                    type:'post',
                    data:'idregional='+idregional+'&_token={{csrf_token()}}',
                    success:function(result){
                        $('#area_edit').html(result)                        
                        $('#area_edit').val(idarea).change()  
                    }
                });
            }
        });                 
        
        $('#mdlEdit').modal('show');
    }

    function showMdlDelete(id){
        $('#mdlDelete_id').val(id)
        $('#mdlDelete').modal('show');
    }

    $(document).ready(function(){
        $('#nasional').change(function(){
            let idnasional=$(this).val();
            $('#regional').html('<option selected disabled value="">Pilih Regional</option>')
            $('#area').html('<option selected disabled value="">Pilih Area</option>')
            $.ajax({
                url:'/getRegional',
                type:'post',
                data:'idnasional='+idnasional+'&_token={{csrf_token()}}',
                success:function(result){
                    $('#regional').html(result)
                }
            });
        });
        
        $('#regional').change(function(){
            let idregional=$(this).val();
            $('#area').html('<option selected disabled value="">Pilih Area</option>')
            $.ajax({
                url:'/getArea',
                type:'post',
                data:'idregional='+idregional+'&_token={{csrf_token()}}',
                success:function(result){
                    $('#area').html(result)
                }
            });
        });

        $('#location_edit').change(function(){
            let idnasional=$(this).val();
            $('#regional_edit').html('<option selected disabled value="">Pilih Regional</option>')
            $('#area_edit').html('<option selected disabled value="">Pilih Area</option>')
            $.ajax({
                url:'/getRegional',
                type:'post',
                data:'idnasional='+idnasional+'&_token={{csrf_token()}}',
                success:function(result){
                    $('#regional_edit').html(result)
                }
            });
        });
        
        $('#regional_edit').change(function(){
            let idregional=$(this).val();
            $('#area_edit').html('<option selected disabled value="">Pilih Area</option>')
            $.ajax({
                url:'/getArea',
                type:'post',
                data:'idregional='+idregional+'&_token={{csrf_token()}}',
                success:function(result){
                    $('#area_edit').html(result)
                }
            });
        });
    });
</script>