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
                    Tambah Target Penjualan
                </button>
                <a href="{{ url('master/target-sale/download_template') }}" style="float: right;" class="btn btn-sm btn-outline-primary mr-3">
                    <i class="flaticon-381-download"></i>
                    Download Template
                </a>
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
                                        <th>Kategori Produk</th></th>
                                        <th>Jumlah</th>
                                        <th>Region</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 1;
                                    @endphp
                                    @if (count($target_sales) > 0)
                                    @foreach ($target_sales as $target_sale)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $target_sale->procat->NAME_PC }}</td>
                                        <td>{{ $target_sale->QUANTITY }}</td>
                                        <td>{{ $target_sale->regional->NAME_REGIONAL }}</td>
                                        <td>
                                            @if ($target_sale->DELETED_AT == NULL)
                                            <i class="fa-solid fa-circle mr-2" style="color:#3CC13B;"></i>
                                            Enable
                                            @else
                                            <i class="fa-solid fa-circle mr-2" style="color:#C13B3B;"></i>
                                            Disable
                                            @endif
                                        </td>
                                        <td>
                                            <button onclick="showMdlEdit('{{ $target_sale->ID_TS}}', '{{ $target_sale->procat->ID_PC}}', '{{ $target_sale->regional->ID_REGIONAL }}', '{{ $target_sale->QUANTITY }}', '{{ $target_sale->DELETED_AT }}')" class="btn btn-primary btn-sm">
                                                <i class="flaticon-381-edit-1"></i>
                                            </button>
                                            <button onclick="showMdlDelete('{{ $target_sale->ID_TS}}')" class="btn btn-primary btn-sm">
                                                <i class="flaticon-381-trash-1"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Target Penjualan  -->
<div class="modal fade" id="mdlAdd">
    <div class="modal-dialog" style="max-width:600px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah File Target Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('master/target-sale/store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="rounded border border-primary w-100 h-75 d-inline-block p-10 d-flex justify-content-center p-5 flex-column">
                        <div class="mx-auto">
                            Upload Target Penjualan dari Template yang sudah tersedia
                        </div>
                        <input type="file" name="file_excel_template" class="dropzone">
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
    <div class="modal-dialog modal-lg" style="max-width:600px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Target Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('master/target-sale/update') }}">
                    @csrf
                    <div class="row form-group">
                        <div class="col-md-6 ">
                            <label for="">Kategori Produk</label>
                            <select name="procat_edit" class="select2" required>
                                <option disabled value="">Pilih Kategori Produk</option>
                                @foreach ($procats as $procat)
                                <option value="{{ $procat->ID_PC }}">{{ $procat->NAME_PC }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 ">
                            <label for="">Regional</label>
                            <select name="regional_edit" class="select2" required>
                                <option disabled value="">Pilih Regional</option>
                                @foreach ($regionals as $regional)
                                <option value="{{ $regional->ID_REGIONAL }}">{{ $regional->NAME_REGIONAL }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12 ">
                            <label for="quantity">Quantity</label>
                            <input type="text" id="quantity_edit" name="quantity" class="form-control" placeholder="Quantity" required>
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
                <h5 class="modal-title">Hapus Target Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin untuk menghapus target penjualan ini?</p>
            </div>
            <form action="{{ url('master/target-sale/destroy') }}">
                @csrf
                <div class="modal-footer">
                    <input type="hidden" name="id" id="mdlDelete_id">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-primary">Hapus</button>
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

    function showMdlEdit(id, procat_id, regional_id, quantity, status) {
        $('#mdlEdit_id').val(id)
        $('#procat_edit').val(procat_id)
        // $("#product_edit option[value=" + product_id + "]").attr('selected', 'selected');
        $('#regional_edit').val(regional_id)
        $('#quantity_edit').val(quantity)
        // $('#name_edit').val(name)
        // $('#email_edit').val(email)
        // $('#telepon_edit').val(phone)
        // $('#role_edit').val(idrole).change()
        // $('#area_edit').val(area).change()
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
</script>