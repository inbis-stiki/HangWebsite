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
                            <select name="" id="" class="form-control default-select fs-12">
                                <option selected value="0">Tahun</option>
                                <option value="">2021</option>
                                <option value="">2022</option>
                                <option value="">2023</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="LineChart_asmen" role="tabpanel">
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
                    <div class="card-header pb-0 d-block d-flex border-0">
                        <h3 class="fs-16 font-weight-bolder text-black mb-0">Trend</h3>
                        <div class="card-action revenue-tabs mt-3 mt-sm-0 ml-5">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active fs-12" data-toggle="tab" href="#" role="tab" aria-selected="false">
                                        UST
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-12" data-toggle="tab" href="#" role="tab" aria-selected="false">
                                        Non UST
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-12" data-toggle="tab" href="#" role="tab" aria-selected="false">
                                        Seleraku
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="d-flex ml-auto">
                            <select name="" id="" class="form-control default-select fs-12">
                                <option selected value="0">Tahun</option>
                                <option value="">2021</option>
                                <option value="">2022</option>
                                <option value="">2023</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="LineChart_rpo" role="tabpanel">
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
                <div class="row">
                    <div class="col-sm">
                        <h3 class="fs-16 text-black font-weight-bolder mb-3">Ranking</h3>
                    </div>
                    <div class="col-sm-4 mb-3 mt-n2">
                        <div class="basic-form ml-auto pr-2">
                            <form>
                                <div class="form-row">
                                    <div class="col mt-2 mt-sm-0">
                                        <select name="" id="SelectAREAAktivitas" class="form-control default-select">
                                            <option selected value="0">Area</option>
                                            @foreach($location as $item)
                                            <option value="{{ $item->ID_LOCATION }}"> {{ $item->NAME_LOCATION }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col mt-2 mt-sm-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-light" style="background-color: white" type="button"><i class="flaticon-381-calendar-1 pl-2"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control input-default" id="dateAktivitas" placeholder="Bulan">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card text-black mb-3">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="RankAktivitas" role="tabpanel">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="card text-black">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="card text-black mb-3">
                                            <div class="card-body mb-4">
                                                <h5 class="card-title fs-14 mb-4">Aktivitas UB</h5>
                                                <div class="basic-form mt-4">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                                            <div class="col text-black text-center tgt" id="tgt_ub"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                                            <div class="col text-black text-center real" id="real_ub"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                                            <div class="col text-primary text-center vstgt" id="vstgt_ub"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card text-white mb-3">
                                            <div class="card-body mb-4">
                                                <h5 class="card-title fs-14 mb-4">Pedagang Sayur</h5>
                                                <div class="basic-form mt-4">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                                            <div class="col text-black text-center tgt" id="tgt_pdgSayur"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                                            <div class="col text-black text-center real" id="real_pdgSayur"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                                            <div class="col text-primary text-center vstgt" id="vstgt_pdgSayur"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card text-white mb-3">
                                            <div class="card-body mb-4">
                                                <h5 class="card-title fs-14 mb-4">Retail</h5>
                                                <div class="basic-form mt-4">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                                            <div class="col text-black text-center tgt" id="tgt_retail"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                                            <div class="col text-black text-center real" id="real_retail"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                                            <div class="col text-primary text-center vstgt" id="vstgt_retail"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                <div class="row">
                    <div class="col-sm">
                        <h3 class="fs-16 text-black font-weight-bolder mb-3">Pencapaian</h3>
                    </div>
                    <div class="col-sm-4 mb-3 mt-n2">
                        <div class="basic-form ml-auto pr-2">
                            <form>
                                <div class="form-row">
                                    <div class="col mt-2 mt-sm-0">
                                        <select name="" id="SelectAREAPencapaian" class="form-control default-select">
                                            <option selected value="0">Area</option>
                                            @foreach($location as $item)
                                            <option value="{{ $item->ID_LOCATION }}"> {{ $item->NAME_LOCATION }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col mt-2 mt-sm-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-light" style="background-color: white" type="button"><i class="flaticon-381-calendar-1 pl-2"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control input-default" id="datePencapaian" placeholder="Bulan">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card text-black mb-3">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="RankPencapaian" role="tabpanel">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="card text-black">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="card text-black mb-3">
                                            <div class="card-body mb-4">
                                                <h5 class="card-title fs-14 mb-4">NON UST</h5>
                                                <div class="basic-form mt-4">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                                            <div class="col text-black text-center tgt" id="tgt_nonUST"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                                            <div class="col text-black text-center real" id="real_nonUST"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                                            <div class="col text-primary text-center vstgt" id="vstgt_nonUST"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card text-white mb-3">
                                            <div class="card-body mb-4">
                                                <h5 class="card-title fs-14 mb-4">UST</h5>
                                                <div class="basic-form mt-4">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                                            <div class="col text-black text-center tgt" id="tgt_UST"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                                            <div class="col text-black text-center real" id="real_UST"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                                            <div class="col text-primary text-center vstgt" id="vstgt_UST"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card text-white mb-3">
                                            <div class="card-body mb-4">
                                                <h5 class="card-title fs-14 mb-4">Seleraku</h5>
                                                <div class="basic-form mt-4">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                                            <div class="col text-black text-center tgt" id="tgt_SELERAKU"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                                            <div class="col text-black text-center real" id="real_SELERAKU"></div>
                                                            <div class="w-100"></div>
                                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                                            <div class="col text-primary text-center vstgt" id="vstgt_SELERAKU"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                    <div class="card-header pb-0 d-block d-flex flex-row justify-content-start border-0">
                        <h3 class="fs-16 text-black font-weight-bolder mb-0">Presensi</h3>
                        <div class="basic-form ml-auto pr-2">
                            <form>
                                <div class="form-row">
                                    <div class="col mt-2 mt-sm-0">
                                        <select id="SelectAREAPresensi">
                                            @Foreach($area as $item)
                                            <option value="{{$item->ID_AREA}}">{{$item->NAME_AREA}}</option>
                                            @endForeach
                                        </select>
                                    </div>
                                    <div class="col mt-2 mt-sm-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-light" type="button"><i class="flaticon-381-calendar-1 pl-2"></i></button>
                                            </div>
                                            <input type="text" class="form-control input-default" id="datePresensi" placeholder="Bulan">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="datatable_presensi" class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bolder fs-14 text-black">No</th>
                                        <th class="font-weight-bolder fs-14 text-black">Nama</th>
                                        <th class="font-weight-bolder fs-14 text-black">Area</th>
                                        <th class="font-weight-bolder fs-14 text-black">Jumlah</th>
                                        <th class="font-weight-bolder fs-14 text-black">Bulan</th>
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
{{-- Flatpickr --}}

<script type="text/javascript">
    // Chart Trend
    var options_asmen = {
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

    var chart_asmen = new ApexCharts(document.querySelector("#LineChart_asmen"), options_asmen);
    chart_asmen.render();

    // AJAX Trend asmen
    var data_trend_asmen = [];
    trend_asmen('<?= date("Y"); ?>')

    function callback(response) {
        data_trend_asmen.push(response)
    }

    function trend_asmen(date) {
        $.ajax({
            url: "{{ url('dashboard/trend_asmen') }}",
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            },
            dataType: "json",
            success: function(response) {
                callback(response)
                SetTypeTrend("UST")
            }
        });
    }

    function SetTypeTrend(type) {
        chart_asmen.updateSeries([])
        for (let i = 0; i < data_trend_asmen[0].length; i++) {
            if (type == "UST") {
                chart_asmen.appendSeries({
                    name: "ASMEN " + data_trend_asmen[0][i].NAME_AREA,
                    data: data_trend_asmen[0][i].UST
                }, true)
            } else if (type == "NONUST") {
                chart_asmen.appendSeries({
                    name: "ASMEN " + data_trend_asmen[0][i].NAME_AREA,
                    data: data_trend_asmen[0][i].NONUST
                }, true)
            } else if (type == "SELERAKU") {
                chart_asmen.appendSeries({
                    name: "ASMEN " + data_trend_asmen[0][i].NAME_AREA,
                    data: data_trend_asmen[0][i].SELERAKU
                }, true)
            }
        }
    }

    var options_rpo = {
        // colors: ["#EC1D25", "#F26F21", "#F8C460"],
        series: [{
                name: "RPO 1",
                data: [12, 31, 38, 58, 48, 68, 69, 91, 148, 12, 76, 40]
            },
            {
                name: "RPO 2",
                data: [15, 21, 39, 55, 45, 76, 73, 57, 12, 36, 19, 87]
            },
            {
                name: "RPO 3",
                data: [9, 41, 32, 52, 42, 91, 41, 64, 47, 16, 29, 38]
            }
        ],
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

    var chart_rpo = new ApexCharts(document.querySelector("#LineChart_rpo"), options_rpo);
    chart_rpo.render();

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

        // Ranking Aktivitas
        var RankAktivitas = {
            series: [{
                name: 'Target',
                data: [0, 0, 0]
            }, {
                name: 'Real',
                data: [0, 0, 0]
            }],
            colors: ['#3f3d56', '#f26f21'],
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                toolbar: {
                    show: true
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 10
                },
            },
            xaxis: {
                categories: ['Aktivitas UB', 'Pedagang Sayur', 'Retail'],
            },
            legend: {
                position: 'right',
                offsetY: 40
            },
            fill: {
                opacity: 1
            }
        };

        var RankAktivitas = new ApexCharts(document.querySelector('#RankAktivitas'), RankAktivitas);
        RankAktivitas.render();

        //AKTIVITAS FILTER
        flatpickr(dateAktivitas, {
            inline: false,
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "M Y",
                    altFormat: "m-Y"
                })
            ],
            onChange: date => {
                activity_ranking(convert(date))
            }
        });

        function convert(str) {
            var date = new Date(str),
                mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth].join("-");
        }

        $('#SelectAREAAktivitas').change(function() {
            activity_ranking($('#dateAktivitas').val());
        });

        // AJAX SET DATA AKTIVITAS
        activity_ranking('<?= date("Y-m"); ?>')

        function activity_ranking(date) {
            $.ajax({
                url: "{{ url('dashboard/ranking_aktivitas') }}",
                type: "POST",
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                data: {
                    filter_date: date,
                    filter_area: $('#SelectAREAAktivitas').val()
                },
                dataType: "json",
                success: function(response) {
                    $('#tgt_ub').html(response.TGT_UB);
                    $('#real_ub').html(response.REAL_UB);
                    $('#vstgt_ub').html(response.VSTARGET_UB);
                    $('#tgt_pdgSayur').html(response.TGT_PDGSAYUR);
                    $('#real_pdgSayur').html(response.REAL_PDGSAYUR);
                    $('#vstgt_pdgSayur').html(response.VSTARGET_PDGSAYUR);
                    $('#tgt_retail').html(response.TGT_RETAIL);
                    $('#real_retail').html(response.REAL_RETAIL);
                    $('#vstgt_retail').html(response.VSTARGET_RETAIL);

                    RankAktivitas.updateOptions({
                        series: [{
                            name: 'Target',
                            data: [response.TGT_UB, response.TGT_PDGSAYUR, response.TGT_RETAIL]
                        }, {
                            name: 'Real',
                            data: [response.REAL_UB, response.REAL_PDGSAYUR, response.REAL_RETAIL]
                        }],
                        colors: ['#3f3d56', '#f26f21']
                    })
                }
            });
        }

        // Ranking Pencapaian
        var RankPencapaian = {
            series: [{
                name: 'Target',
                data: [0, 0, 0]
            }, {
                name: 'Real',
                data: [0, 0, 0]
            }],
            colors: ['#3f3d56', '#f26f21'],
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                toolbar: {
                    show: true
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 10
                },
            },
            xaxis: {
                categories: ['Non UST', 'UST', 'Seleraku'],
            },
            legend: {
                position: 'right',
                offsetY: 40
            },
            fill: {
                opacity: 1
            }
        }

        var RankPencapaian = new ApexCharts(document.querySelector('#RankPencapaian'), RankPencapaian);
        RankPencapaian.render();

        //PENCAPAIAN FILTER
        flatpickr(datePencapaian, {
            inline: false,
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "M Y",
                    altFormat: "m-Y"
                })
            ],
            onChange: date => {
                pencapaian_ranking(convert(date))
            }
        });

        function convert(str) {
            var date = new Date(str),
                mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth].join("-");
        }

        $('#SelectAREAPencapaian').change(function() {
            pencapaian_ranking($('#datePencapaian').val());
        });

        // AJAX SET DATA PENCAPAIAN
        pencapaian_ranking('<?= date("Y-m"); ?>')

        function pencapaian_ranking(date) {
            $.ajax({
                url: "{{ url('dashboard/ranking_pencapaian') }}",
                type: "POST",
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                data: {
                    filter_date: date,
                    filter_area: $('#SelectAREAPencapaian').val()
                },
                dataType: "json",
                success: function(response) {
                    $('#tgt_nonUST').html(response.TGT_NONUST);
                    $('#real_nonUST').html(response.REAL_NONUST);
                    $('#vstgt_nonUST').html(response.VSTARGET_NONUST);
                    $('#tgt_UST').html(response.TGT_UST);
                    $('#real_UST').html(response.REAL_UST);
                    $('#vstgt_UST').html(response.VSTARGET_UST);
                    $('#tgt_SELERAKU').html(response.TGT_SELERAKU);
                    $('#real_SELERAKU').html(response.REAL_SELERAKU);
                    $('#vstgt_SELERAKU').html(response.VSTARGET_SELERAKU);

                    RankPencapaian.updateOptions({
                        series: [{
                            name: 'Target',
                            data: [response.TGT_NONUST, response.TGT_UST, response.TGT_SELERAKU]
                        }, {
                            name: 'Real',
                            data: [response.REAL_NONUST, response.REAL_UST, response.REAL_SELERAKU]
                        }],
                        colors: ['#3f3d56', '#f26f21']
                    })
                }
            });
        }

        // PRESENSI FILTER
        flatpickr(datePresensi, {
            inline: false,
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "M Y",
                    altFormat: "m-Y"
                })
            ],
            onChange: date => {
                $('#datatable_presensi').DataTable().destroy();
                presensi(convert(date))
            }
        });

        function convert(str) {
            var date = new Date(str),
                mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth].join("-");
        }

        $('#SelectAREAPresensi').select2();
        $('#SelectAREAPresensi').change(function() {
            $('#datatable_presensi').DataTable().destroy();
            presensi($('#datePresensi').val());
        });

        // TABEL PRESENSI
        presensi('<?= date("Y-m"); ?>')

        function presensi(date) {
            $('#datatable_presensi').DataTable({
                "pageLength": 5,
                "lengthMenu": [
                    [5, 10, 20],
                    [5, 10, 20]
                ],
                "processing": true,
                "language": {
                    "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                    "loadingRecords": "Loading...",
                    "emptyTable": "  ",
                    "infoEmpty": "No Data to Show",
                },
                "serverMethod": 'POST',
                "ajax": {
                    'url': "{{ url('dashboard/presensi') }}",
                    'beforeSend': function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                    },
                    'data': function(data) {
                        data.filter_date = date
                        data.filter_area = $('#SelectAREAPresensi').val()
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
                        data: 'JML_PRESENCE'
                    },
                    {
                        data: 'DATE_PRESENCE'
                    }
                ],
            }).draw()

            $('#ranking_presensi').html('');
            $.ajax({
                url: "{{ url('dashboard/presensi') }}",
                type: "POST",
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                data: {
                    filter_date: date
                },
                dataType: "json",
                success: function(response) {
                    var trHTML_presence = '';
                    $.each(response, function(key, value) {
                        trHTML_presence +=
                            '<tr><td>' + value.NAME_USER +
                            '</td><td>' + value.NAME_AREA +
                            '</td><td>' + value.JML_PRESENCE +
                            '</td><td>' + value.DATE_PRESENCE +
                            '</td></tr>';
                    });
                    $('#ranking_presensi').append(trHTML_presence);
                }
            });
        }
    });

    $.fn.dataTable.ext.errMode = 'throw';
    
</script>