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
                    Tambah Pasar
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
                                        <th>Pasar</th>
                                        <th>Area</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($markets as $market)
                                        <tr>
                                            <td>{{ $market->NAME_DISTRICT }}</td>
                                            <td>{{ $market->NAME_AREA }}</td>
                                            <td>
                                                @if ($market->ISFOCUS_DISTRICT == '1')
                                                    <i class="fa-solid fa-circle mr-2" style="color:#0000FF;"></i>
                                                    Pasar Fokus
                                                @endif
                                                @if ($market->deleted_at == NULL)
                                                    <i class="fa-solid fa-circle mr-2" style="color:#3CC13B;"></i>
                                                    Enable
                                                @else
                                                    <i class="fa-solid fa-circle mr-2" style="color:#C13B3B;"></i>
                                                    Disable
                                                @endif
                                            </td>
                                            <td>
                                                <button onclick="showMdlEdit('{{ $market->ID_DISTRICT }}', '{{ $market->NAME_DISTRICT }}', '{{ $market->ID_AREA }}', '{{ $market->ISFOCUS_DISTRICT }}', '{{ $market->deleted_at }}')" class="btn btn-primary btn-sm">
                                                    <i class="flaticon-381-edit-1"></i>
                                                </button>
                                                <button onclick="showMdlDelete('{{ $market->ID_DISTRICT }}')" class="btn btn-primary btn-sm">
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
                <h5 class="modal-title">Tambah Pasar</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAdd" action="{{ url('master/location/market/store') }}" method="POST">
                    @csrf
                <div class="form-group">
                    <label for="">Pasar</label>
                    <input type="text" name="district" class="form-control" placeholder="Input nama Pasar" required>
                </div>
                <div class="form-group">
                    <label for="">Area</label>
                    <select id="mdlAdd_select" name="area" class="select2" required>
                        <option selected disabled value="">Pilih Area</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->ID_AREA }}">{{ $area->NAME_AREA }}</option>                            
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Kecamatan</label>
                    <select id="mdlAdd_selectD" name="districtK" class="select2" required>
                            <option selected disabled value="">Pilih Kecamatan</option>
                            @foreach ($district as $districts)
                                <option value="{{ $districts->ID_DISTRICT }}">{{ $districts->NAME_DISTRICT }}</option>                            
                            @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Status Pasar</label>
                    <div class="form-group mb-0">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" name="statusMarket" class="custom-control-input" id="customCheckBox1">
                            <label class="custom-control-label" for="customCheckBox1">Pasar Fokus</label>
                        </div>
                    </div>
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
                <h5 class="modal-title">Ubah Pasar</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('master/location/market/update') }}" method="POST">
                    @csrf
                <div class="form-group">
                    <label for="">Pasar</label>
                    <input type="text" name="district" id="mdlEdit_name" class="form-control" placeholder="Input nama Pasar" required>
                </div>
                <div class="form-group">
                    <label for="">Area</label>
                    <select name="area" class="select2" id="mdlEdit_area" required>
                        <option selected disabled value="">Pilih Area</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->ID_AREA }}">{{ $area->NAME_AREA }}</option>                            
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Kecamatan</label>
                    <select name="districtK" class="select2" id="mdlEdit_district" required>
                        <option selected disabled value="">Pilih Kecamatan</option>
                        @foreach ($district as $districts)
                            <option value="{{ $districts->ID_DISTRICT }}">{{ $districts->NAME_DISTRICT }}</option>                            
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Status Pasar</label>
                    <div class="form-group mb-0">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" id="status_market" name="statusMarket" class="custom-control-input">
                            <label class="custom-control-label" for="status_market">Pasar Fokus</label>
                        </div>
                    </div>
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
                <h5 class="modal-title">Hapus Pasar</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin untuk menghapus data pasar?</p>
            </div>
            <form action="{{ url('master/location/market/destroy') }}" method="POST">
                @csrf
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
    $('#mdlAdd').on('hidden.bs.modal', function () {
        $('#formAdd').trigger('reset')
        $('#mdlAdd_select').val("").change()
        $('#mdlAdd_selectD').val("").change()
    })
    function showMdlEdit(id, name, location, district, statusMarket, status){
        $('#mdlEdit_id').val(id)
        $('#mdlEdit_name').val(name)
        $('#mdlEdit_area').val(location).change()
        $('#mdlEdit_district').val(district).change()
        if (status == null || status == '') {
            $('#status_enable').prop('checked', true)
        } else {
            $('#status_disable').prop('checked', true)
        }

        if (statusMarket == '1') {
            $('#status_market').prop('checked', true)
        } else {
            $('#status_market').prop('checked', false)
        }

        $('#mdlEdit').modal('show');
    }
    function showMdlDelete(id){
        $('#mdlDelete_id').val(id)
        $('#mdlDelete').modal('show');
    }
</script>