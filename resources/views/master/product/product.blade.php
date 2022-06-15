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
                    Tambah Produk
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
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Kode Produk</th>
                                        <th>Kategori Produk</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($product as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->NAME_PRODUCT }}</td>
                                        <td>{{ $item->CODE_PRODUCT }}</td>
                                        <td>{{ $item->NAME_PC }}</td>
                                        <td>
                                            @if ($item->deleted_at == NULL)
                                                <i class="fa-solid fa-circle mr-2" style="color:#3CC13B;"></i>
                                                Enable
                                            @else
                                                <i class="fa-solid fa-circle mr-2" style="color:#C13B3B;"></i>
                                                Disable
                                            @endif
                                        </td>
                                        <td>
                                            <button onclick="showMdlEdit('{{ $item->ID_PRODUCT }}', '{{ $item->NAME_PRODUCT }}', '{{ $item->CODE_PRODUCT }}', '{{ $item->ID_PC }}', '{{ $item->deleted_at }}')" class="btn btn-primary btn-sm">
                                                <i class="flaticon-381-edit-1"></i>
                                            </button>
                                            <button onclick="showMdlDelete('{{ $item->ID_PRODUCT }}')" class="btn btn-primary btn-sm">
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

<!-- Modal Tambah Product  -->
<div class="modal fade" id="mdlAdd">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="{{ url('master/product/store') }}">
                <div class="form-group">
                    <label for="">Nama Produk</label>
                    <input type="text" name="name_product" class="form-control" placeholder="Input Nama Produk" required>
                </div>
                <div class="form-group">
                    <label for="">Kode Produk</label>
                    <input type="text" name="code_product" maxlength="20" style="text-transform:uppercase" class="form-control" placeholder="Input Kode Produk" required>
                </div>
                <div class="form-group">
                    <label>Kategori Produk</label>
                    <select name="category_product" class="form-control default-select" id="select1" required>
                        @foreach ($dropdown_category_product as $item)
                        <option value="{{ $item->ID_PC }}">{{ $item->NAME_PC }}</option>
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
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal" onclick="this.form.reset();">Batalkan</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit  -->
<div class="modal fade" id="mdlEdit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Produk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('master/product/update') }}">
                <div class="form-group">
                    <label for="">Nama Produk</label>
                    <input type="text" name="name_product" id="mdlEdit_name" class="form-control" placeholder="Input nama produk" required>
                </div>
                <div class="form-group">
                    <label for="">Kode Produk</label>
                    <input type="text" name="code_product" id="mdlEdit_code" maxlength="20" style="text-transform:uppercase" class="form-control" placeholder="Input Kode Produk" required>
                </div>
                <div class="form-group">
                    <label>Kategori Produk</label>
                    <select name="category_product" class="form-control default-select" id="mdlEdit_category" required>
                        @foreach ($dropdown_category_product as $item)
                        <option value="{{ $item->ID_PC }}">{{ $item->NAME_PC }}</option>
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

<!-- Modal Delete  -->
<div class="modal fade" id="mdlDelete">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Produk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin untuk menghapus data produk?</p>
            </div>
            <form action="{{ url('master/product/destroy') }}">
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

    function showMdlEdit(id, name, code, category, status){
        $('#mdlEdit_id').val(id)
        $('#mdlEdit_name').val(name)
        $('#mdlEdit_code').val(code)
        console.log(category)
        $('#mdlEdit_category').val(category).change()
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