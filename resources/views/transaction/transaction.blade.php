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
                                <input value="<?= (date_format(date_create(date("Y-m-d")), 'j F Y')); ?>" name="datepicker" class="datepicker-default form-control">
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
                                        <th>Aktifitas</th>
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

<!--**********************************
    Content body end
***********************************-->
@include('template/footer')

<script>
    var tgl_trans = "<?= date("Y-m-d"); ?>";
    var type = "";
    $(document).ready(function() {
        filterData();
    });

    $.fn.dataTable.pipeline = function(opts) {

        var conf = $.extend(opts);


        var cacheLower = -1;
        var cacheUpper = null;
        var cacheLastRequest = null;
        var cacheLastJson = null;

        return function(request, drawCallback, settings) {
            var ajax = false;
            var requestStart = request.start;
            var drawStart = request.start;
            var requestLength = request.length;
            var requestEnd = requestStart + requestLength;

            if (settings.clearCache) {

                ajax = true;
                settings.clearCache = false;
            } else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {

                ajax = true;
            } else if (
                JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
            ) {

                ajax = true;
            }


            cacheLastRequest = $.extend(true, {}, request);

            if (ajax) {

                if (requestStart < cacheLower) {
                    requestStart = requestStart - requestLength * (conf.pages - 1);

                    if (requestStart < 0) {
                        requestStart = 0;
                    }
                }

                cacheLower = requestStart;
                cacheUpper = requestStart + requestLength * conf.pages;

                request.start = requestStart;
                request.length = requestLength * conf.pages;


                if (typeof conf.data === 'function') {



                    var d = conf.data(request);
                    if (d) {
                        $.extend(request, d);
                    }
                } else if ($.isPlainObject(conf.data)) {

                    $.extend(request, conf.data);
                }

                return $.ajax({
                    type: conf.method,
                    url: conf.url,
                    data: request,
                    dataType: 'json',
                    cache: false,
                    success: function(json) {
                        cacheLastJson = $.extend(true, {}, json);

                        if (cacheLower != drawStart) {
                            json.data.splice(0, drawStart - cacheLower);
                        }
                        if (requestLength >= -1) {
                            json.data.splice(requestLength, json.data.length);
                        }

                        drawCallback(json);
                    },
                });
            } else {
                json = $.extend(true, {}, cacheLastJson);
                json.draw = request.draw;
                json.data.splice(0, requestStart - cacheLower);
                json.data.splice(requestLength, json.data.length);

                drawCallback(json);
            }
        };
    };

    $.fn.dataTable.Api.register('clearPipeline()', function() {
        return this.iterator('table', function(settings) {
            settings.clearCache = true;
        });
    });

    function filterData() {
        $('#datatables').DataTable({
            "processing": true,
            "serverSide": true,
            "language": {
                "processing": "<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>",
                "loadingRecords": "Loading...",
                "emptyTable": "  ",
                "infoEmpty": "No Data to Show",
            },
            "ajax": $.fn.dataTable.pipeline({
                pages: 5,
                url: "{{ url('master/transaction/Alltransaction') }}",
                crossDomain: true,
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'searchTrans': $('#SelectTrans').val(),
                    'tglSearchtrans': tgl_trans
                },
                method: 'POST',
            }),
            "columns": [{
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
        }).draw()
    }

    $(".datepicker-default").pickadate({
        format: 'd\ mmmm yyyy',
        clear: 'All Time',
        onSet: function() {
            tgl_trans = this.get('select', 'yyyy-mm-dd');
            $('#datatables').DataTable().destroy();
            filterData();
        }
    });

    $('#SelectTrans').change(function() {
        $('#datatables').DataTable().destroy();
        filterData();
    });

    // const showLocation = (long, lat) => {
    //     $('#mdlLocation_src').attr('src', `https://maps.google.com/maps?q=${lat},${long}&hl=es&z=14&amp;output=embed`);
    //     $('#mdlLocation').modal('show')
    // }
</script>