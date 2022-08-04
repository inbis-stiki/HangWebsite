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
                {{-- <button style="float: right;" data-toggle="modal" data-target="#mdlAdd"  class="btn btn-sm btn-primary">
                    <i class="flaticon-381-add-2"></i>
                    Tambah Toko
                </button> --}}
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

        <div class="row">
            <div class="col-12" style="margin-bottom: 5px;">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="card-title">Tanggal Transaksi</h4>
                                <input name="datepicker" class="datepicker-default form-control">
                            </div>
                            <div class="col-6">
                                <h4 class="card-title">Jenis Transaksi</h4>
                                <select name="transaksi" id="SelectTrans" class="form-control default-select">
                                    <option selected value="0">All Transaksi</option>
                                    <option value="1">Spreading</option>
                                    <option value="2">UB</option>
                                    <option value="3">UBLP</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trans -->
        <div class="row" id="spreading">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Daftar Transaksi</h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatables" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Regional</th>
                                        <th>Area</th>
                                        <th>Waktu</th>
                                        <th>Target Aktifitas</th>
                                        <th>Jumlah Transaksi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Spreading Modal -->
<!-- Modal  -->
<div class="modal fade" id="mdlSpreadDetail">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 d-flex">
                    <div class="form-group col-md-6">
                        <label for="">Foto 1</label>
                        <br>
                        <div style="text-align: center;" id="mdlSpreadTrans_Image_1"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Foto 2</label>
                        <br>
                        <div style="text-align: center;" id="mdlSpreadTrans_Image_2"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Nama Toko</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Nama Produk</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Quantity Produk</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group" id="mdlDetSpreadTrans_NameShop">

                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group" id="mdlDetSpreadTrans_NameProduct">

                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group" id="mdlDetSpreadTrans_QTYProduct">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- UB Modal -->
<!-- Modal  -->
<div class="modal fade" id="mdlUbDetail">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="col-md-12 d-flex">
                    <div class="form-group col-md-6">
                        <label for="">Foto Booth</label>
                        <br>
                        <div id="mdlTrans_ImageBooth"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Foto Masak</label>
                        <br>
                        <div id="mdlTrans_ImageMasak"></div>
                    </div>
                </div>
                <div class="col-md-12 d-flex">
                    <div class="form-group col-md-6">
                        <label for="">Foto Icip</label>
                        <br>
                        <div id="mdlTrans_ImageIcip"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Foto Selling</label>
                        <br>
                        <div id="mdlTrans_ImageSell"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Nama Toko</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Nama Produk</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Quantity Produk</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group" id="mdlDetailTrans_NameShop">

                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group" id="mdlDetailTrans_NameProduct">

                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group" id="mdlDetailTrans_QTYProduct">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- UBLP Modal -->
<!-- Modal  -->
<div class="modal fade" id="mdlUblpDetail">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 d-flex">
                    <div class="form-group col-md-6">
                        <label for="">Foto Booth</label>
                        <br>
                        <div id="mdlTransUBLP_ImageBooth"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Foto Masak</label>
                        <br>
                        <div id="mdlTransUBLP_ImageMasak"></div>
                    </div>
                </div>
                <div class="col-md-12 d-flex">
                    <div class="form-group col-md-6">
                        <label for="">Foto Icip</label>
                        <br>
                        <div id="mdlTransUBLP_ImageIcip"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Foto Selling</label>
                        <br>
                        <div id="mdlTransUBLP_ImageSell"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Nama Area</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Nama Produk</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Quantity Produk</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group" id="mdlDetUblpTrans_AreaTrans">

                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group" id="mdlDetUblpTrans_NameProduct">

                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group" id="mdlDetUblpTrans_QTYProduct">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!--**********************************
    Content body end
***********************************-->
@include('template/footer')

<script>
    var tgl_trans = "";
    filterData("");

    $(".datepicker-default").pickadate({
        format: 'd\ mmmm yyyy',
        onSet: function() {
            const Date = this.get('select', 'd\ mmmm yyyy')
            $('#datatables').DataTable().destroy()
            $('#datatables').DataTable().columns(4).search(Date).draw();
        }
    });

    function filterData(Date) {
        $('#datatables').DataTable({
            "processing": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                "ZeroRecords": " ",
                EmptyTable: ''
            },
            'serverMethod': 'POST',
            'ajax': {
                'url': "{{ url('master/transaction/Alltransaction') }}",
                'beforeSend': function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                'data': function(data) {
                    data.searchTrans = $('#SelectTrans').val();
                    data.tgl_trans = tgl_trans;
                }
            },
            'columns': [{
                    data: 'NO'
                },
                {
                    data: 'NAME_USER'
                },
                {
                    data: 'REGIONAL_TRANS'
                },
                {
                    data: 'AREA_TRANS'
                },
                {
                    data: 'DATE_TRANS'
                },
                {
                    data: 'NAME_TYPE'
                },
                {
                    data: 'JML_TRANS'
                },
                {
                    data: 'ACTION_BUTTON'
                }
            ],
        }).columns(4).search(Date).draw()
    }

    $('#SelectTrans').change(function() {
        $('#datatables').DataTable().destroy();
        const Date = $(".datepicker-default").val();
        filterData(Date);
    });

    const showDetailSpread = (id_trans) => {
        $.ajax({
            url: "{{ url('master/transaction/transactionSpreadDetail') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id_trans": id_trans
            },
            dataType: 'json',
            success: function(response) {
                $('#mdlDetSpreadTrans_NameShop').html('');
                $('#mdlDetSpreadTrans_NameProduct').html('');
                $('#mdlDetSpreadTrans_QTYProduct').html('');
                $('#mdlSpreadTrans_Image').html('')
                if (response.image_trans[0] != undefined) {
                    $('#mdlSpreadTrans_Image_1').append('<img src="' + response.image_trans[0][0] + '" style="max-width: 300px; margin-bottom: 10px" alt="">');
                    $('#mdlSpreadTrans_Image_2').append('<img src="' + response.image_trans[0][1] + '" style="max-width: 300px; margin-bottom: 10px" alt="">');
                }
                for (let i = 0; i < response.data.length; i++) {
                    $('#mdlDetSpreadTrans_NameShop').append('<p>' + response.data[i].NAME_SHOP + '</p>');
                    $('#mdlDetSpreadTrans_NameProduct').append('<p>' + response.data[i].NAME_PRODUCT + '</p>');
                    $('#mdlDetSpreadTrans_QTYProduct').append('<p>' + response.data[i].QTY_TD + '</p>');
                }
                $('#mdlSpreadDetail').modal('show');
            }
        })
    }

    const showDetailUB = (id_trans) => {
        $.ajax({
            url: "{{ url('master/transaction/transactionUBDetail') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id_trans": id_trans
            },
            dataType: 'json',
            success: function(response) {
                $('#mdlDetailTrans_NameShop').html('')
                $('#mdlDetailTrans_NameProduct').html('')
                $('#mdlDetailTrans_QTYProduct').html('')
                $('#mdlTrans_ImageBooth').html('')
                $('#mdlTrans_ImageMasak').html('')
                $('#mdlTrans_ImageIcip').html('')
                $('#mdlTrans_ImageSell').html('')

                $('#mdlTrans_ImageBooth').append('<img src="' + response.image_trans[0][0] + '" style="max-width: 300px; margin-bottom: 10px" alt="">');
                $('#mdlTrans_ImageMasak').append('<img src="' + response.image_trans[0][1] + '" style="max-width: 300px; margin-bottom: 10px" alt="">');
                $('#mdlTrans_ImageIcip').append('<img src="' + response.image_trans[0][2] + '" style="max-width: 300px; margin-bottom: 10px" alt="">');
                $('#mdlTrans_ImageSell').append('<img src="' + response.image_trans[0][3] + '" style="max-width: 300px; margin-bottom: 10px" alt="">');

                for (let i = 0; i < response.data.length; i++) {
                    $('#mdlDetailTrans_NameShop').append('<p>' + response.data[i].NAME_SHOP + '</p>');
                    $('#mdlDetailTrans_NameProduct').append('<p>' + response.data[i].NAME_PRODUCT + '</p>');
                    $('#mdlDetailTrans_QTYProduct').append('<p>' + response.data[i].QTY_TD + '</p>');
                }
                $('#mdlUbDetail').modal('show');
            }
        })
    }

    const showDetailUBLP = (id_trans) => {
        $.ajax({
            url: "{{ url('master/transaction/transactionUBLPDetail') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id_trans": id_trans
            },
            dataType: 'json',
            success: function(response) {
                $('#mdlDetUblpTrans_NameShop').html('');
                $('#mdlDetUblpTrans_NameProduct').html('');
                $('#mdlDetUblpTrans_QTYProduct').html('');
                $('#mdlDetUblpTrans_AreaTrans').html('')
                $('#mdlTransUBLP_ImageBooth').html('')
                $('#mdlTransUBLP_ImageMasak').html('')
                $('#mdlTransUBLP_ImageIcip').html('')
                $('#mdlTransUBLP_ImageSell').html('')

                $('#mdlTransUBLP_ImageBooth').append('<img src="' + response.image_trans[0][0] + '" style="max-width: 300px; margin-bottom: 10px" alt="">');
                $('#mdlTransUBLP_ImageMasak').append('<img src="' + response.image_trans[0][1] + '" style="max-width: 300px; margin-bottom: 10px" alt="">');
                $('#mdlTransUBLP_ImageIcip').append('<img src="' + response.image_trans[0][2] + '" style="max-width: 300px; margin-bottom: 10px" alt="">');
                $('#mdlTransUBLP_ImageSell').append('<img src="' + response.image_trans[0][3] + '" style="max-width: 300px; margin-bottom: 10px" alt="">');

                for (let i = 0; i < response.data.length; i++) {
                    $('#mdlDetUblpTrans_AreaTrans').append('<p>' + response.data[i].AREA_TRANS + '</p>');
                    $('#mdlDetUblpTrans_NameProduct').append('<p>' + response.data[i].NAME_PRODUCT + '</p>');
                    $('#mdlDetUblpTrans_QTYProduct').append('<p>' + response.data[i].QTY_TD + '</p>');
                }
                $('#mdlUblpDetail').modal('show');
            }
        })
    }

    // const showLocation = (long, lat) => {
    //     $('#mdlLocation_src').attr('src', `https://maps.google.com/maps?q=${lat},${long}&hl=es&z=14&amp;output=embed`);
    //     $('#mdlLocation').modal('show')
    // }
</script>