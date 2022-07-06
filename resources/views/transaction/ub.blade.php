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
                                            <button class="btn light btn-success" onclick="showPresence('{{ $ub_data->ID_TRANS }}')"><i class="fa fa-circle-info"></i></button>
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
<div class="modal fade" id="mdlPresence">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Setail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Foto</label>
                    <br>
                    <div style="text-align: center;" id="mdlTrans_Image"></div>
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
<!-- Modal  -->
<div class="modal fade" id="mdlLocation">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lokasi</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: center;">
                <iframe width="600" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" id="mdlLocation_src" src="">
                </iframe>
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
    $('#datatable').DataTable()
    const showPresence = (id_trans) => {
        $.ajax({
            url: "{{ url('transactionDetail') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id_trans": id_trans
            },
            dataType: 'json',
            success: function(response) {
                $('#mdlDetailTrans_NameShop').html('');
                $('#mdlDetailTrans_NameProduct').html('');
                $('#mdlDetailTrans_QTYProduct').html('');
                $('#mdlTrans_Image').html('')
                
                for(let j = 0; j < response.image_trans[0].length; j++){
                    $('#mdlTrans_Image').append('<img src="'+response.image_trans[0][j]+'" style="max-width: 400px; margin-bottom: 10px" alt="">');
                }
                for (let i = 0; i < response.data.length; i++) {
                    $('#mdlDetailTrans_NameShop').append('<p>' + response.data[i].NAME_SHOP + '</p>');
                    $('#mdlDetailTrans_NameProduct').append('<p>' + response.data[i].NAME_PRODUCT + '</p>');
                    $('#mdlDetailTrans_QTYProduct').append('<p>' + response.data[i].QTY_TD + '</p>');
                    $('#mdlPresence').modal('show');
                }
            }
        })
    }

    const showLocation = (long, lat) => {
        $('#mdlLocation_src').attr('src', `https://maps.google.com/maps?q=${lat},${long}&hl=es&z=14&amp;output=embed`);
        $('#mdlLocation').modal('show')
    }
</script>