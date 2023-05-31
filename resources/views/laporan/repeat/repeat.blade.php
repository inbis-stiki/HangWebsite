@include('template/header')
@include('template/sidebar')
<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="row mb-4">
            {{-- <div class="col">
                <button style="float: right;" data-toggle="modal" data-target="#mdlAdd" class="btn btn-sm btn-primary">
                    <i class="flaticon-381-add-2"></i>
                    Tambah Toko
                </button>
            </div> --}}
        </div>

        @if ($errors->any())
        <div class="alert alert-danger" style="margin-top: 1rem;">{{ $errors->first() }}</div>
        @endif
        @if (session('succ_msg'))
        <div class="alert alert-success alert-dismissible fade show">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                <polyline points="9 11 12 14 22 4"></polyline>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
            <strong>Successfully Generate!</strong> {{session('succ_msg')}}.
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
            </button>
        </div>
        @endif
        @if (session('err_msg'))
        <div class="alert alert-danger alert-dismissible fade show">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <strong>Failed Generate!</strong> {{session('err_msg')}}.
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
            </button>
        </div>
        @endif

        <!-- Add Order -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Laporan Repeat Order</h4>
                        <div class="card-action revenue-tabs mt-3 mt-sm-0">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#bulanan" role="tab" aria-selected="false">
                                        Bulanan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#toko" role="tab" aria-selected="false">
                                        Toko
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @php
                    $start = new DateTime('2023-01-31');
                    $end = new DateTime(date('Y-m'));
                    $current = new DateTime($start->format('Y-m-01'));
                    $dates = array();

                    while($current <= $end) { $dates[]=$current->format('Y-m-d');
                        $current->modify('+1 month');
                        }

                        $dates = array_reverse($dates);
                        @endphp
                        <div class="card-body">
                            <div class="tab-content">
                                <div id="bulanan" class="tab-pane active">
                                    <div class="row">
                                        <div class="col">
                                            <div class="table-responsive">
                                                <table id="" class="display min-w850 datatable">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Bulan</th>
                                                            <th>Tahun</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                        $no = 1;
                                                        @endphp
                                                        @foreach ($dates as $dt)
                                                        @php
                                                        $year = date_format(date_create($dt), 'Y');
                                                        $month = date_format(date_create($dt), 'm');
                                                        $monthLat = date_format(date_create($dt), 'F');
                                                        $day = date_format(date_create($dt), 'd');
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $monthLat }}</td>
                                                            <td>{{ $year }}</td>
                                                            <td><a href="{{ url('cronjob/gen-ro-rpo/'.$year.'-'.$month) }}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download</a></td>
                                                        </tr>
                                                        @endforeach
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="toko" class="tab-pane">
                                    <div class="row">
                                        <div class="col">
                                            <div class="table-responsive">
                                                <table id="" class="display min-w850 datatable">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Bulan</th>
                                                            <th>Tahun</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                        $no = 1;
                                                        @endphp
                                                        @foreach ($dates as $dt)
                                                        @php
                                                        $year = date_format(date_create($dt), 'Y');
                                                        $month = date_format(date_create($dt), 'm');
                                                        $monthLat = date_format(date_create($dt), 'F');
                                                        $day = date_format(date_create($dt), 'd');
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $monthLat }}</td>
                                                            <td>{{ $year }}</td>
                                                            <td><a href="{{ url('cronjob/gen-ro-shop/'.$year.'-'.$month) }}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download</a></td>
                                                        </tr>
                                                        @endforeach
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
            <div class="col-12" style="margin-bottom: 5px;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Cetak Report Toko</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="regional">Regional:</label>
                                <select id="regional" name="regional" class="form-control" required>
                                    <option selected disabled value=''>Pilih Regional</option>@foreach ($regional as $reg)<option value='{{ $reg->ID_REGIONAL }}'>{{ $reg->NAME_REGIONAL }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6" id="date-start">
                                <label for="start_month">Date Start:</label>
                                <input type="month" class="form-control date-picker-start" name="dateStart" required>
                            </div>
                            <div class="col-md-6" id="date-end">
                                <label for="start_month">Date End:</label>
                                <input type="month" class="form-control date-picker-end" name="dateEnd" required>
                            </div>
                        </div>
                        <br></br>
                        <button id="generate_report" class="btn btn-primary">Generate Report</button>
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
    $(document).ready(function() {
        $('.datatable').DataTable();

        $('#generate_report').on('click', function() {
            var dateStart = $("input[name='dateStart']").val();
            var dateEnd = $("input[name='dateEnd']").val();
            var regional = $("#regional").find('option:selected').val();

            var url = '{{ url("cronjob/gen-ro-shop-range") }}?dateStart=' + dateStart + '&dateEnd=' + dateEnd + '&regional=' + regional;

            window.location.href = url;
        });
    });

    $('.date-picker-start').pickadate({
        format: 'yyyy-mm',
        onClose: function() {
            var month = (parseInt($('#date-start').find('.picker__select--month').val()) + 1).toString()
            var cnvrtMonth = (month.length < 2) ? ('0' + month) : month
            var year = $('#date-start').find('.picker__select--year').val()
            var date = [year, cnvrtMonth].join("-")
            $('.date-picker-start').val(date)
        },
        selectMonths: true,
        selectYears: true,
        buttonClear: false
    })

    $('.date-picker-end').pickadate({
        format: 'yyyy-mm',
        onClose: function() {
            var month = (parseInt($('#date-end').find('.picker__select--month').val()) + 1).toString()
            var cnvrtMonth = (month.length < 2) ? ('0' + month) : month
            var year = $('#date-end').find('.picker__select--year').val()
            var date = [year, cnvrtMonth].join("-")
            $('.date-picker-end').val(date)
        },
        selectMonths: true,
        selectYears: true,
        buttonClear: false
    })

    function checkit() {

        var fromMonth = document.getElementById('start_month');
        var toMonth = document.getElementById('end_month');

        if (fromMonth.options[fromMonth.selectedIndex].value >
            toMonth.options[toMonth.selectedIndex].value) {
            document.getElementById('end_month').value =
                fromMonth.options[fromMonth.selectedIndex].value;
        }


    }

    function checkitYear() {

        var fromYear = document.getElementById('start_year');
        var toYear = document.getElementById('end_year');

        if (fromYear.options[fromYear.selectedIndex].value >
            toYear.options[toYear.selectedIndex].value) {
            document.getElementById('end_year').value =
                fromYear.options[fromYear.selectedIndex].value;
        }

    }
</script>
<style>
    .picker__select--month {
        font-size: 20px;
        height: 50px;
    }

    .picker__select--year {
        font-size: 20px;
        height: 50px;
    }

    .picker__table {
        display: none;
    }

    .picker__button--clear {
        display: none;
    }

    .picker__button--today {
        display: none;
    }

    .picker__button--close {
        display: none;
    }

    .picker__frame {
        margin-bottom: 26%;
    }
</style>