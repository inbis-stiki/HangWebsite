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
                    <div class="card-header pb-0 d-block d-flex border-0">
                        <h3 class="fs-16 font-weight-bolder text-black mb-0">Aktivitas</h3>
                        <div class="d-flex ml-auto">
                            <select name="" id="role_act" class="form-control default-select fs-12 mr-3" {{ (Session::get('role') != 1 && Session::get('role') != 2 && Session::get('role') != 3) ? "disabled" : "" }}>
                                <option value="asmen" {{ (Session::get('role') == 1 || Session::get('role') == 2) ? "selected" : "" }}>RPC</option>
                                <option value="rpo" {{ (Session::get('role') == 3 || Session::get('role') == 4) ? "selected" : "" }}>{{ (Session::get('role') == 4) ? "AREA" : "RPO" }}</option>
                            </select>
                            <select name="" id="cat_act" class="form-control default-select fs-12 mr-3">
                                <option selected value="0">AKTIVITAS UB</option>
                                <option value="1">PEDAGANG SAYUR</option>
                                <option value="2">RETAIL</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="dataActivity" role="tabpanel">
                                {{-- <canvas id="" class="chart"></canvas> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                        <h5 class="card-title fs-14">RPC</h5>
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
                                <li class="nav-item">
                                    <a class="nav-link fs-12" data-toggle="tab" href="" role="tab" aria-selected="false" id="RENDANG" onclick="SetTypeTrend(this.id)">
                                        Rendang
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-12" data-toggle="tab" href="" role="tab" aria-selected="false" id="GEPREK" onclick="SetTypeTrend(this.id)">
                                        Geprek
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="d-flex ml-auto">
                            <select name="" id="role_trend" class="form-control default-select fs-12" {{ (Session::get('role') != 1 && Session::get('role') != 2 && Session::get('role') != 3) ? "disabled" : "" }}>
                                <option value="3" {{ (Session::get('role') == 1 || Session::get('role') == 2 || Session::get('role') == 3) ? "selected" : "" }}>RPC</option>
                                <option value="4" {{ (Session::get('role') == 4) ? "selected" : "" }}>{{ (Session::get('role') == 4) ? "AREA" : "RPO" }}</option>
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
                        '</td><td>RPC ' + value.NAME_LOCATION +
                        '</td><td>' + value.NEW_AVERAGE.toFixed(2) + "%"
                    '</td></tr>';
                });

                var first_array_rpo = response.rpo.slice(0, 5)
                var last_array_rpo = response.rpo.slice(Math.max(response.rpo.length - 5, 1))
                $.each(first_array_rpo, function(key, value) {
                    trHTML_rpo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>RPO ' + value.NAME_REGIONAL +
                        '</td><td>' + value.NEW_AVERAGE.toFixed(2) + "%"
                    '</td></tr>';
                });
                $.each(last_array_rpo, function(key, value) {
                    trHTML_rpo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>RPO ' + value.NAME_REGIONAL +
                        '</td><td>' + value.NEW_AVERAGE.toFixed(2) + "%"
                    '</td></tr>';
                });

                var first_array_apo = response.apo.slice(0, 5)
                var last_array_apo = response.apo.slice(Math.max(response.apo.length - 5, 1))
                $.each(first_array_apo, function(key, value) {
                    trHTML_apo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>APO ' + value.NAME_AREA +
                        '</td><td>' + value.NEW_AVERAGE.toFixed(2) + "%"
                    '</td></tr>';
                });
                $.each(last_array_apo, function(key, value) {
                    trHTML_apo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>APO ' + value.NAME_AREA +
                        '</td><td>' + value.NEW_AVERAGE.toFixed(2) + "%"
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
                        '</td><td>RPC ' + value.NAME_LOCATION +
                        '</td><td>' + value.NEW_AVERAGE.toFixed(2) + "%"
                    '</td></tr>';
                });

                var first_array_rpo = response.rpo.slice(0, 5)
                var last_array_rpo = response.rpo.slice(Math.max(response.rpo.length - 5, 1))
                $.each(first_array_rpo, function(key, value) {
                    trHTML_rpo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>RPO ' + value.NAME_REGIONAL +
                        '</td><td>' + value.NEW_AVERAGE.toFixed(2) + "%"
                    '</td></tr>';
                });
                $.each(last_array_rpo, function(key, value) {
                    trHTML_rpo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>RPO ' + value.NAME_REGIONAL +
                        '</td><td>' + value.NEW_AVERAGE.toFixed(2) + "%"
                    '</td></tr>';
                });

                var first_array_apo = response.apo.slice(0, 5)
                var last_array_apo = response.apo.slice(Math.max(response.apo.length - 5, 1))
                $.each(first_array_apo, function(key, value) {
                    trHTML_apo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>APO ' + value.NAME_AREA +
                        '</td><td>' + value.NEW_AVERAGE.toFixed(2) + "%"
                    '</td></tr>';
                });
                $.each(last_array_apo, function(key, value) {
                    trHTML_apo +=
                        '<tr><td>' + value.NUM_ROW +
                        '</td><td>APO ' + value.NAME_AREA +
                        '</td><td>' + value.NEW_AVERAGE.toFixed(2) + "%"
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
    var data_trend = [];

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
            categories: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC']
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
            offsetX: 0,
            offsetY: 0,
            onItemClick: {
                toggleDataSeries: false
            },
            height: 80,
            tooltipHoverFormatter: function(seriesName, opts) {
                if (opts.seriesIndex == 0) return ''
                return seriesName + ' : <strong>' + addCommas(opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex]) + '</strong>'
            }
        },
        tooltip: {
            enabled: true,
            shared: true,
            x: {
                show: false
            },
            items: {
                'display': 'none',
            },
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
        <?php if (Session::get('role') == 1 || Session::get('role') == 2 || Session::get('role') == 3) { ?>
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
        <?php } else { ?>
            $.ajax({
                url: "{{ url('dashboard/trend_apo') }}",
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
        <?php } ?>
    }

    function SetTypeTrend(type) {
        chart.updateSeries()
        <?php if (Session::get('role') == 1 || Session::get('role') == 2 || Session::get('role') == 3) { ?>
            if (role_data == 3) {
                for (let i = 0; i < data_trend[0].length; i++) {
                    chart.update
                    if (type == "UST") {
                        chart.appendSeries({
                            name: "RPC " + data_trend[0][i].PLACE,
                            data: data_trend[0][i].UST
                        }, false)
                    } else if (type == "NONUST") {
                        chart.appendSeries({
                            name: "RPC " + data_trend[0][i].PLACE,
                            data: data_trend[0][i].NONUST
                        }, false)
                    } else if (type == "SELERAKU") {
                        chart.appendSeries({
                            name: "RPC " + data_trend[0][i].PLACE,
                            data: data_trend[0][i].SELERAKU
                        }, false)
                    } else if (type == "RENDANG") {
                        chart.appendSeries({
                            name: "RPC " + data_trend[0][i].PLACE,
                            data: data_trend[0][i].RENDANG
                        }, false)
                    } else if (type == "GEPREK") {
                        chart.appendSeries({
                            name: "RPC " + data_trend[0][i].PLACE,
                            data: data_trend[0][i].GEPREK
                        }, false)
                    }
                }
            } else {
                for (let i = 0; i < data_trend[0].length; i++) {
                    if (type == "UST") {
                        chart.appendSeries({
                            name: "RPO " + data_trend[0][i].PLACE,
                            data: data_trend[0][i].UST
                        }, true)
                    } else if (type == "NONUST") {
                        chart.appendSeries({
                            name: "RPO " + data_trend[0][i].PLACE,
                            data: data_trend[0][i].NONUST
                        }, true)
                    } else if (type == "SELERAKU") {
                        chart.appendSeries({
                            name: "RPO " + data_trend[0][i].PLACE,
                            data: data_trend[0][i].SELERAKU
                        }, true)
                    } else if (type == "RENDANG") {
                        chart.appendSeries({
                            name: "RPO " + data_trend[0][i].PLACE,
                            data: data_trend[0][i].RENDANG
                        }, false)
                    } else if (type == "GEPREK") {
                        chart.appendSeries({
                            name: "RPO " + data_trend[0][i].PLACE,
                            data: data_trend[0][i].GEPREK
                        }, false)
                    }
                }
            }
        <?php } else { ?>
            for (let i = 0; i < data_trend[0].length; i++) {
                if (type == "UST") {
                    chart.appendSeries({
                        name: "APO " + data_trend[0][i].PLACE,
                        data: data_trend[0][i].UST
                    }, true)
                } else if (type == "NONUST") {
                    chart.appendSeries({
                        name: "APO " + data_trend[0][i].PLACE,
                        data: data_trend[0][i].NONUST
                    }, true)
                } else if (type == "SELERAKU") {
                    chart.appendSeries({
                        name: "APO " + data_trend[0][i].PLACE,
                        data: data_trend[0][i].SELERAKU
                    }, true)
                } else if (type == "RENDANG") {
                    chart.appendSeries({
                        name: "APO " + data_trend[0][i].PLACE,
                        data: data_trend[0][i].RENDANG
                    }, false)
                } else if (type == "GEPREK") {
                    chart.appendSeries({
                        name: "APO " + data_trend[0][i].PLACE,
                        data: data_trend[0][i].GEPREK
                    }, false)
                }
            }
        <?php } ?>
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
        data_trend = []
        data_trend.push(response)
    }

    function addCommas(nStr) {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    // AKTIVITAS
    // Chart Activity
    var role_act = $('#role_act').find('option').filter(':selected').val();
    var cat_act = $('#cat_act').find('option').filter(':selected').val();
    var data_aktivitas = [];
    var options_activty = {
        series: [],
        chart: {
            height: 350,
            type: 'bar',
            parentHeightOffset: 50
        },
        plotOptions: {
            bar: {
                columnWidth: '50%',
            }
        },
        tooltip: {
            y: {
                formatter: function(value, {
                    series,
                    seriesIndex,
                    dataPointIndex,
                    w
                }) {
                    return addCommas(value)
                },
                title: {
                    formatter: (seriesName) => seriesName,
                },
            },
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: true,
            showForSingleSeries: true,
            customLegendItems: ['REAL', 'EXCEED TARGET'],
            horizontalAlign: 'left',
            markers: {
                fillColors: ['#f26f21', '#EC1D25']
            }
        },
        annotations: {
            yaxis: []
        }
    };

    var chartActivty = new ApexCharts(document.querySelector("#dataActivity"), options_activty);
    chartActivty.render();

    setDataAktivity()

    $('#cat_act').change(function(e) {
        cat_act = $(this).find('option').filter(':selected').val()
        setDataAktivitas()
    })

    $('#role_act').change(function(e) {
        role_act = $(this).find('option').filter(':selected').val()
        setDataAktivity()
    })

    function setDataAktivity() {
        chartActivty.updateSeries([{
            name: 'Total Aktivitas',
            data: []
        }])
        $.ajax({
            url: "{{ url('dashboard/aktivitas') }}",
            type: "POST",
            crossDomain: true,
            data: {
                role: role_act,
            },
            beforeSend: function(request) {
                request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            },
            dataType: "json",
            success: function(response) {
                data_aktivitas = []
                data_aktivitas.push(response.data)
                setDataAktivitas()
            }
        });
    }

    function setDataAktivitas() {
        chartActivty.updateSeries([{
            name: 'Total Aktivitas',
            data: []
        }])
        if (cat_act == 0) {
            $.each(data_aktivitas[0], function(key, value) {
                chartActivty.appendData([{
                    data: [{
                        x: value.PLACE,
                        y: value.total_activity_ub,
                        fillColor: (value.total_activity_ub > value.TGT_UB) ? "#ec1d25" : "#f26f21"
                    }]
                }])
                chartActivty.updateOptions({
                    annotations: {
                        yaxis: [{
                            y: value.TGT_UB,
                            strokeDashArray: 5,
                            borderColor: '#EC1D25',
                            fillColor: '#EC1D25',
                            label: {
                                borderColor: "#EC1D25",
                                style: {
                                    color: "#fff",
                                    background: "#EC1D25"
                                },
                                text: "Target : " + value.TGT_UB
                            }
                        }]
                    }
                })
            });
        } else if (cat_act == 1) {
            $.each(data_aktivitas[0], function(key, value) {
                chartActivty.appendData([{
                    data: [{
                        x: value.PLACE,
                        y: value.total_activity_ps,
                        fillColor: (value.total_activity_ps > value.TGT_PS) ? "#ec1d25" : "#f26f21"
                    }]
                }])
                chartActivty.updateOptions({
                    annotations: {
                        yaxis: [{
                            y: value.TGT_PS,
                            strokeDashArray: 5,
                            borderColor: '#EC1D25',
                            fillColor: '#EC1D25',
                            label: {
                                borderColor: "#EC1D25",
                                style: {
                                    color: "#fff",
                                    background: "#EC1D25"
                                },
                                text: "Target : " + value.TGT_PS
                            }
                        }]
                    }
                })
            });
        } else {
            $.each(data_aktivitas[0], function(key, value) {
                chartActivty.appendData([{
                    data: [{
                        x: value.PLACE,
                        y: value.total_activity_retail,
                        fillColor: (value.total_activity_retail > value.TGT_RETAIL) ? "#ec1d25" : "#f26f21"
                    }]
                }])
                chartActivty.updateOptions({
                    annotations: {
                        yaxis: [{
                            y: value.TGT_RETAIL,
                            strokeDashArray: 5,
                            borderColor: '#EC1D25',
                            fillColor: '#EC1D25',
                            label: {
                                borderColor: "#EC1D25",
                                style: {
                                    color: "#fff",
                                    background: "#EC1D25"
                                },
                                text: "Target : " + value.TGT_RETAIL
                            }
                        }]
                    }
                })
            });
        }
    }
</script>