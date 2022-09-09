@include('template/header')
@include('template/sidebar')

<style>
    .tgt {
        border-style: solid;
        border-color: #3F3D56;
    }

    .real {
        border-left-style: solid;
        border-right-style: solid;
        border-color: #3F3D56;
    }

    .vstgt {
        border-style: solid;
        border-color: #3F3D56;
    }

    .bg-dark-custom {
        background-color: #3F3D56;
    }
</style>
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
                                    <a class="nav-link active fs-12" data-toggle="tab" href="#" role="tab"
                                        aria-selected="false">
                                        UST
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-12" data-toggle="tab" href="#" role="tab"
                                        aria-selected="false">
                                        Non UST
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-12" data-toggle="tab" href="#" role="tab"
                                        aria-selected="false">
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
                            <div class="tab-pane fade show active" id="LineChart" role="tabpanel">
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
                                    <a class="nav-link active fs-12" data-toggle="tab" href="#" role="tab"
                                        aria-selected="false">
                                        Aktivitas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-12" data-toggle="tab" href="#" role="tab"
                                        aria-selected="false">
                                        Pencapaian
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm">
                                <div class="card text-black mb-3" style="max-width: 18rem; background-color: #F3F2F0;">
                                    <div class="card-body">
                                        <h5 class="card-title fs-14">ASMEN</h5>
                                        <div class="table">
                                            <table id="datatables" class="table table-light rounded ml-n3">
                                                <tbody class="fs-12">
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Nama</td>
                                                        <td>Area</td>
                                                        <td>54.7%</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Nama</td>
                                                        <td>Area</td>
                                                        <td>54.7%</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Nama</td>
                                                        <td>Area</td>
                                                        <td>54.7%</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="card text-white mb-3" style="max-width: 18rem; background-color: #F3F2F0;">
                                    <div class="card-body">
                                        <h5 class="card-title fs-14">RPO</h5>
                                        <div class="table">
                                            <table id="datatables" class="table table-light rounded ml-n3">
                                                <tbody class="fs-12">
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Nama</td>
                                                        <td>Area</td>
                                                        <td>54.7%</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Nama</td>
                                                        <td>Area</td>
                                                        <td>54.7%</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Nama</td>
                                                        <td>Area</td>
                                                        <td>54.7%</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm ">
                                <div class="card text-white mb-3" style="max-width: 18rem; background-color: #F3F2F0;">
                                    <div class="card-body">
                                        <h5 class="card-title fs-14">APO</h5>
                                        <div class="table">
                                            <table id="datatables" class="table table-light rounded ml-n3">
                                                <tbody class="fs-12">
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Nama</td>
                                                        <td>Area</td>
                                                        <td>54.7%</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Nama</td>
                                                        <td>Area</td>
                                                        <td>54.7%</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Nama</td>
                                                        <td>Area</td>
                                                        <td>54.7%</td>
                                                    </tr>
                                                </tbody>
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
                <h3 class="fs-16 text-black font-weight-bolder mb-3">Ranking</h3>
                <div class="row">
                    <div class="col">
                        <div class="card text-black mb-3" style="max-width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title fs-14">Aktivitas UB</h5>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="radialBar1" role="tabpanel">
                                        {{-- <canvas id="" class="chart"></canvas> --}}
                                    </div>
                                </div>
                                <div class="basic-form mt-4">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                            <div class="col text-black text-center tgt">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                            <div class="col text-black text-center real">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                            <div class="col text-primary text-center vstgt">76.3%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card text-white mb-3" style="max-width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title fs-14">Pedagang Sayur</h5>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="radialBar2" role="tabpanel">
                                        {{-- <canvas id="" class="chart"></canvas> --}}
                                    </div>
                                </div>
                                <div class="basic-form mt-4">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                            <div class="col text-black text-center tgt">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                            <div class="col text-black text-center real">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                            <div class="col text-primary text-center vstgt">76.3%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card text-white mb-3" style="max-width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title fs-14">Retail</h5>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="radialBar3" role="tabpanel">
                                        {{-- <canvas id="" class="chart"></canvas> --}}
                                    </div>
                                </div>
                                <div class="basic-form mt-4">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                            <div class="col text-black text-center tgt">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                            <div class="col text-black text-center real">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                            <div class="col text-primary text-center vstgt">76.3%</div>
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
                <h3 class="fs-16 text-black font-weight-bolder mb-3">Pencapaian</h3>
                <div class="row">
                    <div class="col">
                        <div class="card text-black mb-3" style="max-width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title fs-14">Non UST</h5>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="donut1" role="tabpanel">
                                        {{-- <canvas id="" class="chart"></canvas> --}}
                                    </div>
                                </div>
                                <div class="basic-form mt-4">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                            <div class="col text-black text-center tgt">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                            <div class="col text-black text-center real">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                            <div class="col text-primary text-center vstgt">76.3%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card text-white mb-3" style="max-width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title fs-14">UST</h5>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="donut2" role="tabpanel">
                                        {{-- <canvas id="" class="chart"></canvas> --}}
                                    </div>
                                </div>
                                <div class="basic-form mt-4">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                            <div class="col text-black text-center tgt">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                            <div class="col text-black text-center real">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                            <div class="col text-primary text-center vstgt">76.3%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card text-white mb-3" style="max-width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title fs-14">Seleraku</h5>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="donut3" role="tabpanel">
                                        {{-- <canvas id="" class="chart"></canvas> --}}
                                    </div>
                                </div>
                                <div class="basic-form mt-4">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col text-white text-center bg-dark-custom ">TGT</div>
                                            <div class="col text-black text-center tgt">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">REAL</div>
                                            <div class="col text-black text-center real">161</div>
                                            <div class="w-100"></div>
                                            <div class="col text-white text-center bg-dark-custom">% VS TGT</div>
                                            <div class="col text-primary text-center vstgt">76.3%</div>
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
                                        <select name="" id="" class="form-control default-select">
                                            <option selected value="0">Area</option>
                                            <option value="1">Spreading</option>
                                            <option value="2">UB</option>
                                            <option value="3">UBLP</option>
                                        </select>
                                    </div>
                                    <div class="col mt-2 mt-sm-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-light" type="button"><i
                                                        class="flaticon-381-calendar-1 pl-2"></i></button>
                                            </div>
                                            <input type="text" class="form-control input-default"
                                                id="datePresensi" placeholder="Tanggal">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bolder fs-14 text-black">Nama</th>
                                        <th class="font-weight-bolder fs-14 text-black">Area</th>
                                        <th class="font-weight-bolder fs-14 text-black">Jumlah</th>
                                        <th class="font-weight-bolder fs-14 text-black">Bulan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fs-12">Nama</td>
                                        <td class="fs-12">Area</td>
                                        <td class="fs-12">Jumlah</td>
                                        <td class="fs-12">Bulan</td>
                                    </tr>
                                    <tr>
                                        <td class="fs-12">Nama</td>
                                        <td class="fs-12">Area</td>
                                        <td class="fs-12">Jumlah</td>
                                        <td class="fs-12">Bulan</td>
                                    </tr>
                                </tbody>
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
    var options = {
        colors: ["#EC1D25", "#F26F21", "#F8C460"],
        series: [{
                name: "ASMEN 1",
                data: [12, 31, 38, 58, 48, 68, 69, 91, 148, 12, 76, 40]
            },
            {
                name: "ASMEN 2",
                data: [15, 21, 39, 55, 45, 76, 73, 57, 12, 36, 19, 87]
            },
            {
                name: "ASMEN 3",
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

    var chart = new ApexCharts(document.querySelector("#LineChart"), options);
    chart.render();

    //FlatPicker
    $("#datePresensi").flatpickr({
        dateFormat: "m-Y"
    });

    //BEGIN RADIAL BAR CHART
    var optionsCircle1 = {
        chart: {
            type: 'radialBar',
            height: 300,
            zoom: {
                enabled: false
            },
            offsetY: 20
        },
        colors: ["#F26F21"],
        plotOptions: {
            radialBar: {
                dataLabels: {
                    name: {
                        show: false
                    },
                    value: {
                        offsetY: 0
                    }
                }
            }
        },
        series: [76.3],
        theme: {
            monochrome: {
                enabled: false
            }
        },
        legend: {
            show: false
        },
    }

    var chartCircle1 = new ApexCharts(document.querySelector('#radialBar1'), optionsCircle1);
    chartCircle1.render();

    var optionsCircle2 = {
        chart: {
            type: 'radialBar',
            height: 300,
            zoom: {
                enabled: false
            },
            offsetY: 20
        },
        colors: ["#F26F21"],
        plotOptions: {
            radialBar: {
                dataLabels: {
                    name: {
                        show: false
                    },
                    value: {
                        offsetY: 0
                    }
                }
            }
        },
        series: [76.3],
        theme: {
            monochrome: {
                enabled: false
            }
        },
        legend: {
            show: false
        },
    }

    var chartCircle2 = new ApexCharts(document.querySelector('#radialBar2'), optionsCircle2);
    chartCircle2.render();
    
    var optionsCircle3 = {
        chart: {
            type: 'radialBar',
            height: 300,
            zoom: {
                enabled: false
            },
            offsetY: 20
        },
        colors: ["#F26F21"],
        plotOptions: {
            radialBar: {
                dataLabels: {
                    name: {
                        show: false
                    },
                    value: {
                        offsetY: 0
                    }
                }
            }
        },
        series: [76.3],
        theme: {
            monochrome: {
                enabled: false
            }
        },
        legend: {
            show: false
        },
    }

    var chartCircle3 = new ApexCharts(document.querySelector('#radialBar3'), optionsCircle3);
    chartCircle3.render();

    //BEGIN DONUT CHART
    var donut1 = {
        colors: ["#3F3D56", "#F26F21"],
        series: [44, 55],

        chart: {
            width: 240,
            type: 'donut',
            sparkline: {
                enabled: true
            },
        },
        stroke: {
            width: 0,
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        color: "#F26F21",
                        total: {
                            showAlways: true,
                            show: true
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false,
        },
        fill: {
            type: 'donut',
            opacity: 1,
        },
        states: {
            hover: {
                filter: 'none'
            }
        },
        theme: {
            palette: 'palette2'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    show: false,
                }
            }
        }]
    };

    var donut1 = new ApexCharts(document.querySelector("#donut1"), donut1);
    donut1.render();

    var donut2 = {
        colors: ["#3F3D56", "#F26F21"],
        series: [44, 55],

        chart: {
            width: 240,
            type: 'donut',
            sparkline: {
                enabled: true
            },
        },
        stroke: {
            width: 0,
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        color: "#F26F21",
                        total: {
                            showAlways: true,
                            show: true
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false,
        },
        fill: {
            type: 'donut',
            opacity: 1,
        },
        states: {
            hover: {
                filter: 'none'
            }
        },
        theme: {
            palette: 'palette2'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    show: false,
                }
            }
        }]
    };

    var donut2 = new ApexCharts(document.querySelector("#donut2"), donut2);
    donut2.render();

    var donut3 = {
        colors: ["#3F3D56", "#F26F21"],
        series: [44, 55],

        chart: {
            width: 240,
            type: 'donut',
            sparkline: {
                enabled: true
            },
        },
        stroke: {
            width: 0,
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        color: "#F26F21",
                        total: {
                            showAlways: true,
                            show: true
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false,
        },
        fill: {
            type: 'donut',
            opacity: 1,
        },
        states: {
            hover: {
                filter: 'none'
            }
        },
        theme: {
            palette: 'palette2'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    show: false,
                }
            }
        }]
    };

    var donut3 = new ApexCharts(document.querySelector("#donut3"), donut3);
    donut3.render();
</script>
