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
                                <li class="nav-item">
                                    <a class="nav-link active fs-12" data-toggle="tab" href="#" role="tab" aria-selected="true" id="tab-aktivity">
                                        Aktivitas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-12" data-toggle="tab" href="#" role="tab" aria-selected="false" id="tab-pencapaian">
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
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ asset('images/loader.gif') }}" id="loader_1" style="max-width: 150px;">
                                        </div>
                                        <div class="table-responsive" id="table-data">
                                            <table class="table table-light rounded">
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
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ asset('images/loader.gif') }}" id="loader_2" style="max-width: 150px;">
                                        </div>
                                        <div class="table-responsive" id="table-data">
                                            <table class="table table-light rounded">
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
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ asset('images/loader.gif') }}" id="loader_3" style="max-width: 150px;">
                                        </div>
                                        <div class="table-responsive" id="table-data">
                                            <table class="table table-light rounded">
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
    // RANKING
    ranking_activity()
    $('#tab-aktivity').click(function() {
        ranking_activity()
    })

    $('#tab-pencapaian').click(function() {
        ranking_pencapaian()
    })

    function ranking_activity() {
        reset()
        $.ajax({
            url: "{{ url('dashboard/ranking_activity') }}",
            type: "GET",
            'crossDomain': true,
            dataType: "json",
            success: function(response) {
                $.each(response.asmen, function(key, value) {
                    trHTML_asmen +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>ASMEN ' + value.NAME_LOCATION +
                        '</td><td>' + value.NEW_AVERAGE + "%"
                    '</td></tr>';
                });

                var first_array_rpo = response.rpo.slice(0, 5)
                var last_array_rpo = response.rpo.slice(Math.max(response.rpo.length - 5, 1))
                $.each(first_array_rpo, function(key, value) {
                    trHTML_rpo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>RPO ' + value.NAME_REGIONAL +
                        '</td><td>' + value.NEW_AVERAGE + "%"
                    '</td></tr>';
                });
                $.each(last_array_rpo, function(key, value) {
                    trHTML_rpo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>RPO ' + value.NAME_REGIONAL +
                        '</td><td>' + value.NEW_AVERAGE + "%"
                    '</td></tr>';
                });

                var first_array_apo = response.apo.slice(0, 5)
                var last_array_apo = response.apo.slice(Math.max(response.apo.length - 5, 1))
                $.each(first_array_apo, function(key, value) {
                    trHTML_apo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>APO ' + value.NAME_AREA +
                        '</td><td>' + value.NEW_AVERAGE + "%"
                    '</td></tr>';
                });
                $.each(last_array_apo, function(key, value) {
                    trHTML_apo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>APO ' + value.NAME_AREA +
                        '</td><td>' + value.NEW_AVERAGE + "%"
                    '</td></tr>';
                });

                $('#ranking_asmen').append(trHTML_asmen);
                $('#ranking_rpo').append(trHTML_rpo);
                $('#ranking_apo').append(trHTML_apo);

                $('#loader_1, #loader_2, #loader_3').hide()
                $('#table-data').show()
            }
        });
    }

    function ranking_pencapaian() {
        reset()
        $.ajax({
            url: "{{ url('dashboard/ranking_sale') }}",
            type: "GET",
            'crossDomain': true,
            dataType: "json",
            success: function(response) {
                $.each(response.asmen, function(key, value) {
                    trHTML_asmen +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>ASMEN ' + value.NAME_LOCATION +
                        '</td><td>' + value.NEW_AVERAGE + "%"
                    '</td></tr>';
                });

                var first_array_rpo = response.rpo.slice(0, 5)
                var last_array_rpo = response.rpo.slice(Math.max(response.rpo.length - 5, 1))
                $.each(first_array_rpo, function(key, value) {
                    trHTML_rpo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>RPO ' + value.NAME_REGIONAL +
                        '</td><td>' + value.NEW_AVERAGE + "%"
                    '</td></tr>';
                });
                $.each(last_array_rpo, function(key, value) {
                    trHTML_rpo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>RPO ' + value.NAME_REGIONAL +
                        '</td><td>' + value.NEW_AVERAGE + "%"
                    '</td></tr>';
                });

                var first_array_apo = response.apo.slice(0, 5)
                var last_array_apo = response.apo.slice(Math.max(response.apo.length - 5, 1))
                $.each(first_array_apo, function(key, value) {
                    trHTML_apo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>APO ' + value.NAME_AREA +
                        '</td><td>' + value.NEW_AVERAGE + "%"
                    '</td></tr>';
                });
                $.each(last_array_apo, function(key, value) {
                    trHTML_apo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>APO ' + value.NAME_AREA +
                        '</td><td>' + value.NEW_AVERAGE + "%"
                    '</td></tr>';
                });

                $('#ranking_asmen').append(trHTML_asmen);
                $('#ranking_rpo').append(trHTML_rpo);
                $('#ranking_apo').append(trHTML_apo);

                $('#loader_1, #loader_2, #loader_3').hide()
                $('#table-data').show()
            }
        });
    }

    function reset() {
        $('#loader_1, #loader_2, #loader_3').show()
        $('#table-data').hide()
        trHTML_asmen = ''
        trHTML_rpo = ''
        trHTML_apo = ''
        $('#ranking_asmen').html('');
        $('#ranking_rpo').html('');
        $('#ranking_apo').html('');
    }

    // TREND
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
            formatter: (seriesName, opts) => {
                if (opts.seriesIndex == 0) return '' // hides first label
                return seriesName;
            },
            markers: {
                width: [0, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12], // hides first marker
            },
            onItemClick: {
                toggleDataSeries: false
            }
        },
        noData: {
            text: undefined,
            align: 'center',
            verticalAlign: 'middle',
            offsetX: 0,
            offsetY: 0,
            style: {
                color: undefined,
                fontSize: '14px',
                fontFamily: undefined
            }
        }
    };
    var chart = new ApexCharts(document.querySelector("#LineChart"), options);
    chart.render().then(() => chart.isRendered = true);

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
        chart.updateSeries()
        if (role_data == 3) {
            for (let i = 0; i < data_trend_asmen[0].length; i++) {
                chart.update
                if (type == "UST") {
                    chart.appendSeries({
                        name: "ASMEN " + data_trend_asmen[0][i].NAME_AREA,
                        data: data_trend_asmen[0][i].UST
                    }, false)
                } else if (type == "NONUST") {
                    chart.appendSeries({
                        name: "ASMEN " + data_trend_asmen[0][i].NAME_AREA,
                        data: data_trend_asmen[0][i].NONUST
                    }, false)
                } else if (type == "SELERAKU") {
                    chart.appendSeries({
                        name: "ASMEN " + data_trend_asmen[0][i].NAME_AREA,
                        data: data_trend_asmen[0][i].SELERAKU
                    }, false)
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
</script>