@include('template/header')
@include('template/sidebar')
<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="event-tabs mb-3 ml-3">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link default-tab" data-toggle="tab" href="Javascript:void(0)" role="tab" aria-selected="false" onclick="show_tb_trans()">
                                    Transaksi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="Javascript:void(0)" role="tab" aria-selected="false" onclick="show_tb_presence()">
                                    Presence
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- <div class="col-md-6 flex-column">
                    <div class="card border mb-0 py-0">
                        <input class="datepicker-monitoring form-control" value="<?= (date_format(date_create(date("Y-m-d")), 'j F Y')); ?>" style="height: 88px;" name="datepicker">
                    </div>
                </div> -->
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        {{-- <div class="card-header">
                        <h4 class="card-title">Daftar Produk</h4>
                    </div> --}}
                        <div class="card-body" id="table-presence">
                            <div class="table-responsive">
                                <table id="datatable-presence" class="display min-w850">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Regional</th>
                                            <th>Aktivitas</th>
                                            <th>
                                                < 07:00 </th>
                                            <th>07:00 - 08:00</th>
                                            <th>> 08:00</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="card-body" id="table-trans">
                            <div class="table-responsive">
                                <table id="datatable-trans" class="display min-w850">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Regional</th>
                                            <th>Aktivitas</th>
                                            <th>
                                                < 10 </th>
                                            <th>11 - 20</th>
                                            <th>11 - 30</th>
                                            <th>> 30</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Order -->

    </div>
</div>
<!--**********************************
    Content body end
***********************************-->
@include('template/footer')
<script>
    var tgl_trans = "<?= date("Y-m-d"); ?>";
    var RegionalSearch = "0"

    $('.default-tab').trigger('click')

    function show_tb_trans() {
        $('#table-trans').show()
        $('#table-presence').hide()
        fetch_data(1)
    }

    function show_tb_presence() {
        $('#table-presence').show()
        $('#table-trans').hide()
        fetch_data(2)
    }

    function fetch_data(type) {
        if (type == 1) {
            $('#datatable-trans').DataTable().destroy();
            $("#datatable-trans").DataTable({
                "processing": true,
                "language": {
                    "processing": "<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>",
                    "loadingRecords": "",
                    "emptyTable": "  ",
                    "infoEmpty": "No Data to Show",
                },
                "serverMethod": 'POST',
                "ajax": {
                    'url': "{{ url('monitoring/monitoring-data') }}",
                    'beforeSend': function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                    },
                    'data': function(data) {
                        data.tgl_trans = tgl_trans;
                        data.area = RegionalSearch;
                        data.type = type;
                    }
                },
                "columns": [{
                        data: 'NO'
                    },
                    {
                        data: 'NAME_REGIONAL'
                    },
                    {
                        data: 'AKTIVITAS'
                    },
                    {
                        data: 'TRANS_1'
                    },
                    {
                        data: 'TRANS_2'
                    },
                    {
                        data: 'TRANS_3'
                    },
                    {
                        data: 'TRANS_4'
                    }
                ],
            }).draw()
        }else{
            $('#datatable-presence').DataTable().destroy();
            $("#datatable-presence").DataTable({
                "processing": true,
                "language": {
                    "processing": "<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>",
                    "loadingRecords": "",
                    "emptyTable": "  ",
                    "infoEmpty": "No Data to Show",
                },
                "serverMethod": 'POST',
                "ajax": {
                    'url': "{{ url('monitoring/monitoring-data') }}",
                    'beforeSend': function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                    },
                    'data': function(data) {
                        data.tgl_trans = tgl_trans;
                        data.area = RegionalSearch;
                        data.type = type;
                    }
                },
                "columns": [{
                        data: 'NO'
                    },
                    {
                        data: 'NAME_REGIONAL'
                    },
                    {
                        data: 'AKTIVITAS'
                    },
                    {
                        data: 'PRESENCE_1'
                    },
                    {
                        data: 'PRESENCE_2'
                    },
                    {
                        data: 'PRESENCE_3'
                    }
                ],
            }).draw()
        }
    }
</script>