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
                                    <a class="nav-link active" data-toggle="tab" href="#bulanan" role="tab"
                                        aria-selected="false">
                                        Bulanan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#toko" role="tab"
                                        aria-selected="false">
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

                    while($current <= $end) {
                        $dates[] = $current->format('Y-m-d');
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
                                                            $year     = date_format(date_create($dt), 'Y');
                                                            $month    = date_format(date_create($dt), 'm');
                                                            $monthLat = date_format(date_create($dt), 'F');
                                                            $day      = date_format(date_create($dt), 'd');
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
                                                            $year     = date_format(date_create($dt), 'Y');
                                                            $month    = date_format(date_create($dt), 'm');
                                                            $monthLat = date_format(date_create($dt), 'F');
                                                            $day      = date_format(date_create($dt), 'd');
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12" style="margin-bottom: 5px;">
                <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Cetak Report Toko</h4>
                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input id="start_date" name="start_date" class="datepicker-default form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input id="end_date" name="end_date" class="datepicker-default form-control" type="text">
                            </div>
                            <button id="generate_report" class="btn btn-primary">Generate Report</button>
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
<script>
    var startDatePicker = $('#start_date').pickadate({
        format: 'yyyy-mm-dd'
    });

    var endDatePicker = $('#end_date').pickadate({
        format: 'yyyy-mm-dd'
    });

    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const generateReportButton = document.getElementById('generate_report');

    // Add a click event listener to the button
    generateReportButton.addEventListener('click', function() {
        // Get the values of the datepicker inputs
        const startDateValue = startDateInput.value;
        const endDateValue = endDateInput.value;

        // Build the URL with the date range parameters
        const url = `{{ url('cronjob/gen-ro-shop-range') }}?start=${startDateValue}&end=${endDateValue}`;

        // Redirect to the URL
        window.location.href = url;
    });

    $('.datatable').DataTable();
</script>