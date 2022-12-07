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
                    Tambah Toko
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
                                        <th>Kecamatan</th>
                                        <th>Nama</th>
                                        <th>Pemilik</th>
                                        <th>Tipe</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
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

<!--**********************************
    Content body end
***********************************-->
@include('template/footer')
<script>
    // $('#datatable').DataTable()
    filterData();

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

    function filterData() {
        
    }
</script>
