@include('template/header')
@include('template/sidebar')
<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        @if (Session::get('role') == 1 || Session::get('role') == 2)
        <div class="row mb-4">
            <div class="col">
                <button style="float: right;" data-toggle="modal" data-target="#mdlAdd" class="btn btn-sm btn-primary">
                    <i class="flaticon-381-add-2"></i>
                    Tambah Toko
                </button>
            </div>
        </div>
        @endif

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
                                        <th>Kecamatan</th>
                                        <th>Nama</th>
                                        <th>Pemilik</th>
                                        <th>Tipe</th>
                                        <th>Status</th>
                                        @if (Session::get('role') == 1 || Session::get('role') == 2)
                                        <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
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
<div class="modal fade" id="mdlEdit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Toko</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('master/shop/update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Shop</label>
                        <input type="text" name="shop" id="mdlEdit_name" class="form-control" placeholder="Input nama Toko" required>
                    </div>
                    <div class="form-group">
                        <label for="">Pemilik Toko</label>
                        <input type="text" name="owner" id="mdlEdit_owner" class="form-control" placeholder="Input nama Pemilik" required>
                    </div>
                    <div class="form-group">
                        <label for="">Detail Lokasi</label>
                        <input type="text" name="detlok" id="mdlEdit_detlok" class="form-control" placeholder="Input detail lokasi" required>
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
<!--**********************************
    Content body end
***********************************-->
@include('template/footer')
<script>
    // $('#datatable').DataTable()
    filterData();
    <?php 
        if (Session::get('role') == 1 || Session::get('role') == 2) {        
    ?>
    $("#datatable").DataTable({
        "processing": true,
        "serverSide": true,
        "language": {
            "processing": "<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>",
            "loadingRecords": "Loading...",
            "emptyTable": "  ",
            "infoEmpty": "No Data to Show",
        },
        "serverMethod": 'POST',
        "ajax": {
            'url': "{{ url('master/shop/AllShop') }}",
            'beforeSend': function(request) {
                request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            }
        },
        "columns": [{
                data: 'NO'
            },
            {
                data: 'NAME_DISTRICT'
            },
            {
                data: 'NAME_SHOP'
            },
            {
                data: 'OWNER_SHOP'
            },
            {
                data: 'TYPE_SHOP'
            },
            {
                data: 'ISACTIVE'
            },
            {
                data: 'ACTION_BUTTON'
            }
        ],
    })
    <?php  
        }else{
    ?>
    $("#datatable").DataTable({
        "processing": true,
        "serverSide": true,
        "language": {
            "processing": "<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>",
            "loadingRecords": "Loading...",
            "emptyTable": "  ",
            "infoEmpty": "No Data to Show",
        },
        "serverMethod": 'POST',
        "ajax": {
            'url': "{{ url('master/shop/AllShop') }}",
            'beforeSend': function(request) {
                request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            }
        },
        "columns": [{
                data: 'NO'
            },
            {
                data: 'NAME_DISTRICT'
            },
            {
                data: 'NAME_SHOP'
            },
            {
                data: 'OWNER_SHOP'
            },
            {
                data: 'TYPE_SHOP'
            },
            {
                data: 'ISACTIVE'
            }
        ],
    })
    <?php
    }
    ?>

    function showMdlEdit(e) {
        $('#mdlEdit_id').val($(e).data('id'))
        $('#mdlEdit_name').val($(e).data('name'))
        $('#mdlEdit_owner').val($(e).data('own'))
        $('#mdlEdit_detlok').val($(e).data('lok'))
        if ($(e).data('del') == null || $(e).data('del') == '') {
            $('#status_enable').prop('checked', true)
        } else {
            $('#status_disable').prop('checked', true)
        }
        $('#mdlEdit').modal('show');
    }

    function filterData() {

    }
</script>