@include('template/header')
@include('template/sidebar')

<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <!-- Add Order -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header pb-0 d-block d-flex flex-row justify-content-start border-0">
                        <h3 class="fs-16 text-black font-weight-bolder mb-0">Ranking</h3>
                        <div class="card-action revenue-tabs mt-3 mt-sm-0 ml-5">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" id="tab-aktivity">
                                    <a class="nav-link active fs-12" data-toggle="tab" href="#" role="tab" aria-selected="false">
                                        Aktivitas
                                    </a>
                                </li>
                                <li class="nav-item" id="tab-pencapaian">
                                    <a class="nav-link fs-12" data-toggle="tab" href="#" role="tab" aria-selected="false">
                                        Pencapaian
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="card text-black mb-3" style="background-color: #F3F2F0;">
                                    <div class="card-body">
                                        <h5 class="card-title fs-14">ASMEN</h5>
                                        <div class="table-responsive">
                                            <table id="datatables" class="table table-light rounded">
                                                <tbody class="fs-12" id="ranking_asmen"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card text-white mb-3" style="background-color: #F3F2F0;">
                                    <div class="card-body">
                                        <h5 class="card-title fs-14">RPO</h5>
                                        <div class="table-responsive">
                                            <table id="datatables" class="table table-light rounded">
                                                <tbody class="fs-12" id="ranking_rpo"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card text-white mb-3" style="background-color: #F3F2F0;">
                                    <div class="card-body">
                                        <h5 class="card-title fs-14">APO</h5>
                                        <div class="table-responsive">
                                            <table id="datatables" class="table table-light rounded">
                                                <tbody class="fs-12" id="ranking_apo"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header pb-0 d-block d-flex border-0">
                        <h3 class="fs-16 font-weight-bolder text-black mb-0">Trend</h3>
                        <div class="card-action revenue-tabs mt-3 mt-sm-0 ml-5">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active fs-12" data-toggle="tab" href="" role="tab" aria-selected="false" id="UST" onclick="SetTypeTrend(this.id)">
                                        UST
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-12" data-toggle="tab" href="" role="tab" aria-selected="false" id="NONUST" onclick="SetTypeTrend(this.id)">
                                        Non UST
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-12" data-toggle="tab" href="" role="tab" aria-selected="false" id="SELERAKU" onclick="SetTypeTrend(this.id)">
                                        Seleraku
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="d-flex ml-auto">
                            <select name="" id="role_trend" class="form-control default-select fs-12">
                                <option selected value="3">Role</option>
                                <option value="3">Asmen</option>
                                <option value="4">RPO</option>
                            </select>
                            <select name="" id="year_trend" class="form-control default-select fs-12">
                                <option selected value="<?= date("Y"); ?>">Tahun</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="LineChart" role="tabpanel">
                                {{-- <canvas id="" class="chart"></canvas> --}}
                            </div>
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
{{-- Flatpickr --}}

<script type="text/javascript">
    // Chart Trend
    var data_trend_asmen = [];

    var options = {
        // colors: ["#EC1D25", "#F26F21", "#F8C460"],
        series: [],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
        },
        xaxis: {
            categories: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'left',
        },
    };
    var chart = new ApexCharts(document.querySelector("#LineChart"), options);
    chart.render().then(() => chart.isRendered = true);

    var role_data = $('#role_trend').find('option').filter(':selected').val()
    var year_data = $('#year_trend').find('option').filter(':selected').val()

    setDataTrend()
    $('#role_trend').change(function(e) {
        role_data = $(this).find('option').filter(':selected').val()
        setDataTrend()
    })

    $('#year_trend').change(function(e) {
        year_data = $(this).find('option').filter(':selected').val()
        setDataTrend()
    })

    function callback(response) {
        data_trend_asmen = []
        data_trend_asmen.push(response)
    }

    // AJAX Trend asmen
    function setDataTrend() {
        if (role_data == 3) {
            $.ajax({
                url: "{{ url('dashboard/trend_asmen') }}",
                type: "POST",
                crossDomain: true,
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                data: {
                    date: year_data,
                    role: role_data
                },
                dataType: "json",
                success: function(response) {
                    callback(response)
                    $('#UST').trigger('click')
                }
            });
        } else {
            $.ajax({
                url: "{{ url('dashboard/trend_rpo') }}",
                type: "POST",
                crossDomain: true,
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                data: {
                    date: year_data,
                    role: role_data
                },
                dataType: "json",
                success: function(response) {
                    callback(response)
                    $('#UST').trigger('click')
                }
            });
        }
    }

    function SetTypeTrend(type) {
        chart.updateSeries([])
        if (role_data == 3) {
            for (let i = 0; i < data_trend_asmen[0].length; i++) {
                if (type == "UST") {
                    chart.appendSeries({
                        name: "ASMEN " + data_trend_asmen[0][i].NAME_AREA,
                        data: data_trend_asmen[0][i].UST
                    }, true)
                } else if (type == "NONUST") {
                    chart.appendSeries({
                        name: "ASMEN " + data_trend_asmen[0][i].NAME_AREA,
                        data: data_trend_asmen[0][i].NONUST
                    }, true)
                } else if (type == "SELERAKU") {
                    chart.appendSeries({
                        name: "ASMEN " + data_trend_asmen[0][i].NAME_AREA,
                        data: data_trend_asmen[0][i].SELERAKU
                    }, true)
                }
            }
        } else {
            for (let i = 0; i < data_trend_asmen[0].length; i++) {
                if (type == "UST") {
                    chart.appendSeries({
                        name: "RPO " + data_trend_asmen[0][i].NAME_AREA,
                        data: data_trend_asmen[0][i].UST
                    }, true)
                } else if (type == "NONUST") {
                    chart.appendSeries({
                        name: "RPO " + data_trend_asmen[0][i].NAME_AREA,
                        data: data_trend_asmen[0][i].NONUST
                    }, true)
                } else if (type == "SELERAKU") {
                    chart.appendSeries({
                        name: "RPO " + data_trend_asmen[0][i].NAME_AREA,
                        data: data_trend_asmen[0][i].SELERAKU
                    }, true)
                }
            }
        }
    }

    $(document).ready(function() {
        // AJAX Jquery ranking
        load_data_ranking()
        $('#tab-aktivity').click(function() {
            ranking_activity()
        })

        $('#tab-pencapaian').click(function() {
            ranking_pencapaian()
        })

        function load_data_ranking() {
            $('#ranking_asmen').html('');
            $('#ranking_rpo').html('');
            $('#ranking_apo').html('');
            ranking_activity()
        }

        function ranking_activity() {
            $('#ranking_asmen').html('');
            $('#ranking_rpo').html('');
            $('#ranking_apo').html('');
            $.ajax({
                url: "{{ url('dashboard/ranking_activity') }}",
                type: "GET",
                'crossDomain': true,
                dataType: "json",
                success: function(response) {
                    var trHTML_asmen = '';
                    var trHTML_rpo = '';
                    var trHTML_apo = '';
                    var no_asmen = 0
                    var no_rpo = 0
                    var no_apo = 0
                    $.each(response, function(key, value) {
                        if (value.ID_ROLE == 3) {
                            no_asmen++
                            trHTML_asmen +=
                                '<tr><td>' + no_asmen +
                                '</td><td>' + value.NAME_USER +
                                '</td><td>' + Number(value.AVERAGE.toFixed(3)) + "%"
                            '</td></tr>';
                        } else if (value.ID_ROLE == 4) {
                            no_rpo++
                            trHTML_rpo +=
                                '<tr><td>' + no_rpo +
                                '</td><td>' + value.NAME_USER +
                                '</td><td>' + Number(value.AVERAGE.toFixed(3)) + "%"
                            '</td></tr>';
                        } else if (value.ID_ROLE == 5) {
                            no_apo++
                            trHTML_apo +=
                                '<tr><td>' + no_apo +
                                '</td><td>' + value.NAME_USER +
                                '</td><td>' + Number(value.AVERAGE.toFixed(3)) + "%"
                            '</td></tr>';
                        }
                    });

                    $('#ranking_asmen').append(trHTML_asmen);
                    $('#ranking_rpo').append(trHTML_rpo);
                    $('#ranking_apo').append(trHTML_apo);
                }
            });
        }

        function ranking_pencapaian() {
            $('#ranking_asmen').html('');
            $('#ranking_rpo').html('');
            $('#ranking_apo').html('');
            $.ajax({
                url: "{{ url('dashboard/ranking_sale') }}",
                type: "GET",
                'crossDomain': true,
                dataType: "json",
                success: function(response) {
                    var trHTML_asmen = '';
                    var trHTML_rpo = '';
                    var trHTML_apo = '';
                    var no_asmen = 0
                    var no_rpo = 0
                    var no_apo = 0
                    $.each(response, function(key, value) {
                        if (value.ID_ROLE == 3) {
                            no_asmen++
                            trHTML_asmen +=
                                '<tr><td>' + no_asmen +
                                '</td><td>' + value.NAME_USER +
                                '</td><td>' + Number(value.AVERAGE.toFixed(2)) + "%"
                            '</td></tr>';
                        } else if (value.ID_ROLE == 4) {
                            no_rpo++
                            trHTML_rpo +=
                                '<tr><td>' + no_rpo +
                                '</td><td>' + value.NAME_USER +
                                '</td><td>' + Number(value.AVERAGE.toFixed(2)) + "%"
                            '</td></tr>';
                        } else if (value.ID_ROLE == 5) {
                            no_apo++
                            trHTML_apo +=
                                '<tr><td>' + no_apo +
                                '</td><td>' + value.NAME_USER +
                                '</td><td>' + Number(value.AVERAGE.toFixed(2)) + "%"
                            '</td></tr>';
                        }
                    });

                    $('#ranking_asmen').append(trHTML_asmen);
                    $('#ranking_rpo').append(trHTML_rpo);
                    $('#ranking_apo').append(trHTML_apo);
                }
            });
        }
    });

    $.fn.dataTable.ext.errMode = 'throw';
</script>