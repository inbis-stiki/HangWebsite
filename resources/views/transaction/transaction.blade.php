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
            <div class="col-12" style="margin-bottom: 20px;">
                <select name="transaksi" class="select2" onchange="getval(this);" required>
                    <option selected value="Spreading">Spreading</option>
                    <option value="UB">UB</option>
                    <option value="UBLP">UBLP</option>
                </select>
            </div>
        </div>

        <!-- Spreading Trans -->
        <div class="row" id="spreading">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Daftar Produk</h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable-spread" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Kecamatan</th>
                                        <th>Waktu</th>
                                        <th>Target Aktifitas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 1;
                                    @endphp
                                    @foreach ($spreadings as $spread_data)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $spread_data->NAME_USER }}</td>
                                        <td>{{ $spread_data->NAME_DISTRICT }}</td>
                                        <td>{{ date_format(date_create($spread_data->DATE_TRANS), 'j F Y H:i') }}</td>
                                        <td>{{ $spread_data->NAME_TYPE }}</td>
                                        <td>
                                            <button class="btn light btn-success" onclick="showDetailSpread('{{ $spread_data->ID_TRANS }}')"><i class="fa fa-circle-info"></i></button>
                                            <a class="btn light btn-info" href="https://maps.google.com/maps?q={{ $spread_data->LAT_SHOP }},{{ $spread_data->LONG_SHOP }}&hl=es&z=14&amp;" target="_blank"><i class="fa fa-map-location-dot"></i></a>
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

        <!-- UB Trans -->
        <div class="row" id="ub">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Daftar Produk</h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable-ub" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Kecamatan</th>
                                        <th>Waktu</th>
                                        <th>Target Aktifitas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 1;
                                    @endphp
                                    @foreach ($ub as $ub_data)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $ub_data->NAME_USER }}</td>
                                        <td>{{ $ub_data->DISTRICT }}</td>
                                        <td>{{ date_format(date_create($ub_data->DATE_TRANS), 'j F Y H:i') }}</td>
                                        <td>{{ $ub_data->NAME_TYPE }}</td>
                                        <td>
                                            <button class="btn light btn-success" onclick="showDetailUB('{{ $ub_data->ID_TRANS }}')"><i class="fa fa-circle-info"></i></button>
                                            <a class="btn light btn-info" href="https://maps.google.com/maps?q={{ $ub_data->LAT_TRANS }},{{ $ub_data->LONG_TRANS }}&hl=es&z=14&amp;" target="_blank"><i class="fa fa-map-location-dot"></i></a>
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

        <!-- UBLP Trans -->
        <div class="row" id="ublps">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Daftar Produk</h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable-ublps" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Area</th>
                                        <th>Lokasi</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Target Aktifitas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 1;
                                    @endphp
                                    @foreach ($ublps as $ublp)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $ublp->NAME_USER }}</td>
                                        <td>{{ $ublp->AREA_TRANS }}</td>
                                        <td>{{ $ublp->LOCATION_TRANS }}</td>
                                        <td>{{ date_format(date_create($ublp->DATE_TRANS), 'j F Y H:i') }}</td>
                                        <td>{{ $ublp->NAME_TYPE }}</td>
                                        <td>
                                            <button class="btn light btn-success" onclick="showDetailUBLP('{{ $ublp->ID_TRANS }}')"><i class="fa fa-circle-info"></i></button>
                                            <a class="btn light btn-info" href="https://maps.google.com/maps?q={{ $ublp->LAT_TRANS }},{{ $ublp->LONG_TRANS }}&hl=es&z=14&amp;" target="_blank"><i class="fa fa-map-location-dot"></i></a>
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
    $('#datatable-spread').DataTable()
    $('#datatable-ub').DataTable()
    $('#datatable-ublps').DataTable()

    $('#spreading').show()
    $('#ub').hide()
    $('#ublps').hide()

    function getval(sel) {
        if (sel.value == 'Spreading') {
            $('#spreading').show()
            $('#ub').hide()
            $('#ublps').hide()
        } else if (sel.value == 'UB') {
            $('#spreading').hide()
            $('#ub').show()
            $('#ublps').hide()
        } else {
            $('#spreading').hide()
            $('#ub').hide()
            $('#ublps').show()
        }
    }

    const showDetailSpread = (id_trans) => {
        $.ajax({
            url: "{{ url('transactionSpreadDetail') }}",
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
                console.log(response.image_trans[0]);
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
            url: "{{ url('transactionUBDetail') }}",
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
            url: "{{ url('transactionUBLPDetail') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id_trans": id_trans
            },
            dataType: 'json',
            success: function(response) {
                console.log(response)
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

                console.log(response);
                for (let i = 0; i < response.data.length; i++) {
                    $('#mdlDetUblpTrans_AreaTrans').append('<p>' + response.data[i].AREA_TRANS + '</p>');
                    $('#mdlDetUblpTrans_NameProduct').append('<p>' + response.data[i].NAME_PRODUCT + '</p>');
                    $('#mdlDetUblpTrans_QTYProduct').append('<p>' + response.data[i].QTY_TD + '</p>');
                }
                $('#mdlUblpDetail').modal('show');
            }
        })
    }

    const showLocation = (long, lat) => {
        $('#mdlLocation_src').attr('src', `https://maps.google.com/maps?q=${lat},${long}&hl=es&z=14&amp;output=embed`);
        $('#mdlLocation').modal('show')
    }
</script>