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
                    Tambah Area
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
                        <h4 class="card-title">Daftar Produk</h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>Area</th>
                                        <th>Regional</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($areas as $area)
                                        <tr>
                                            <td>{{ $area->NAME_AREA }}</td>
                                            <td>{{ $area->NAME_REGIONAL }}</td>
                                            <td>
                                                @if ($area->deleted_at == NULL)
                                                    <i class="fa-solid fa-circle mr-2" style="color:#3CC13B;"></i>
                                                    Enable
                                                @else
                                                    <i class="fa-solid fa-circle mr-2" style="color:#C13B3B;"></i>
                                                    Disable
                                                @endif
                                            </td>
                                            <td>
                                                <button onclick="showMdlEdit('{{ $area->ID_AREA }}', '{{ $area->NAME_AREA }}', '{{ $area->ID_REGIONAL }}', '{{ $area->deleted_at }}')" class="btn btn-primary btn-sm">
                                                    <i class="flaticon-381-edit-1"></i>
                                                </button>
                                                <button onclick="showMdlDelete('{{ $area->ID_AREA }}')" class="btn btn-primary btn-sm">
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
<!-- Modal  -->
<div class="modal fade" id="mdlAdd">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Area</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('master/location/area/store') }}">
                <div class="form-group">
                    <label for="">Area</label>
                    <input type="text" name="area" class="form-control" placeholder="Input nama Area" required>
                </div>
                <div class="form-group">
                    <label for="">Regional</label>
                    <select name="regional" class="select2" required>
                        <option selected disabled value="">Pilih Regional</option>
                        @foreach ($regionals as $regional)
                            <option value="{{ $regional->ID_REGIONAL }}">{{ $regional->NAME_REGIONAL }}</option>                            
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Status</label>
                    <div class="form-group mb-0">
                        <label class="radio-inline mr-3"><input type="radio" name="status" value="1" required> Enable</label>
                        <label class="radio-inline mr-3"><input type="radio" name="status" value="0" required> Disable</label>
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
<!-- Modal  -->
<div class="modal fade" id="mdlEdit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Area</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('master/location/area/update') }}">
                <div class="form-group">
                    <label for="">Area</label>
                    <input type="text" name="area" id="mdlEdit_name" class="form-control" placeholder="Input nama Regional" required>
                </div>
                <div class="form-group">
                    <label for="">Regional</label>
                    <select name="regional" class="select2" id="mdlEdit_regional" required>
                        <option selected disabled value="">Pilih Regional</option>
                        @foreach ($regionals as $regional)
                            <option value="{{ $regional->ID_REGIONAL }}">{{ $regional->NAME_REGIONAL }}</option>                            
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Status</label>
                    <div class="form-group mb-0">
                        <label class="radio-inline mr-3"><input type="radio" id="status_enable" name="status" value="1" required> Enable</label>
                        <label class="radio-inline mr-3"><input type="radio" id="status_disable" name="status" value="0" required> Disable</label>
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
<!-- Modal  -->
<div class="modal fade" id="mdlDelete">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Area</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin untuk menghapus data area?</p>
            </div>
            <form action="{{ url('master/location/area/destroy') }}">
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
    function showMdlEdit(id, name, location, status){
        $('#mdlEdit_id').val(id)
        $('#mdlEdit_name').val(name)
        $('#mdlEdit_regional').val(location).change()
        if (status == null || status == '') {
            $('#status_enable').prop('checked', true)
        } else {
            $('#status_disable').prop('checked', true)
        }
        $('#mdlEdit').modal('show');
    }
    function showMdlDelete(id){
        $('#mdlDelete_id').val(id)
        $('#mdlDelete').modal('show');
    }
</script>