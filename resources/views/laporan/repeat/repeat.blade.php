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
        <div class="alert alert-success">{{ session('succ_msg') }}</div>
        @endif
        @if (session('err_msg'))
        <div class="alert alert-danger">{{ session('err_msg') }}</div>
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
                            <div class="col-md-6">
                                <label for="start_month">Start Month:</label>
                                <select id="start_month" name="start_month" class="form-control">
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="start_year">Start Year:</label>
                                <select id="start_year" name="start_year" class="form-control">
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="end_month">End Month:</label>
                                <select id="end_month" name="end_month" class="form-control">
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="end_year">End Year:</label>
                                <select id="end_year" name="end_year" class="form-control">
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                </select>
                            </div>
                        </div>
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
        $('#generate_report').on('click', function() {
            var start_month = $('#start_month').val();
            var start_year = $('#start_year').val();
            var end_month = $('#end_month').val();
            var end_year = $('#end_year').val();

            var url = 'cronjob/gen-ro-shop-range?start_month=' + start_month + '&start_year=' + start_year + '&end_month=' + end_month + '&end_year=' + end_year;

            window.location.href = url;
        });
    });
</script>