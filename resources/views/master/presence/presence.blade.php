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
                            <div class="col">
                                <h4 class="card-title">Tanggal Presensi</h4>
                                <input placeholder="<?= (date_format(date_create(date("Y-m-d")), 'j F Y')); ?>" name="datepicker" class="datepicker-default form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                        <th>Area</th>
                                        <th>Kecamatan</th>
                                        <th>Waktu</th>
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
<!-- Modal  -->
<div class="modal fade" id="mdlPresence">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Presensi</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Foto</label>
                    <br>
                    <div style="text-align: center;">
                        <img src="" style="max-width: 400px;" id="mdlPresence_src" alt="">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input class="form-control" id="mdlPresence_name" type="text" readonly>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Kecamatan</label>
                            <input class="form-control" type="text" id="mdlPresence_district" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Waktu</label>
                            <input class="form-control" type="text" id="mdlPresence_date" readonly>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Area</label>
                            <input class="form-control" type="text" id="mdlPresence_area" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Regional</label>
                            <input class="form-control" type="text" id="mdlPresence_regional" readonly>
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
    const showPresence = (src, name, district, date, area, regional) => {
        $('#mdlPresence_src').attr('src', src);
        $('#mdlPresence_name').val(name);
        $('#mdlPresence_district').val(district);
        $('#mdlPresence_date').val(date);
        $('#mdlPresence_area').val(area);
        $('#mdlPresence_regional').val(regional);
        $('#mdlPresence').modal('show')
    }
    const showLocation = (long, lat) => {
        $('#mdlLocation_src').attr('src', `https://maps.google.com/maps?q=${lat},${long}&hl=es&z=14&amp;output=embed`);
        $('#mdlLocation').modal('show')
    }

    var tgl_presence = "<?= date("Y-m-d"); ?>";
    $(".datepicker-default").pickadate({
        format: 'd\ mmmm yyyy',
        onSet: function() {
            tgl_presence = this.get('select', 'yyyy-mm-dd');
            $('#datatable').DataTable().destroy();
            filterData();
        }
    });

    filterData();

    function filterData() {
        $("#datatable").DataTable({
            "processing": true,
            "language": {
                "processing"    : '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                "loadingRecords": "Loading...",
                "emptyTable"    : "  ",
                "infoEmpty"     : "No Data to Show",
            },
            "serverMethod": 'POST',
            "ajax": {
                'url': "{{ url('presence/AllPresence') }}",
                'beforeSend': function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                'data': function(data) {
                    data.tglSearchPresence = tgl_presence;
                }
            },
            "columns": [{
                    data: 'NO'
                },
                {
                    data: 'NAME_USER'
                },
                {
                    data: 'NAME_AREA'
                },
                {
                    data: 'NAME_DISTRICT'
                },
                {
                    data: 'DATE_PRESENCE'
                },
                {
                    data: 'ACTION_BUTTON'
                }
            ],
        }).draw()
    }
</script>