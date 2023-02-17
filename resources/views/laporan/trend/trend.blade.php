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
                        <h4 class="card-title">Laporan Trend</h4>
                        <div class="card-action revenue-tabs mt-3 mt-sm-0">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#asmen" role="tab"
                                        aria-selected="false">
                                        Asmen
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#rpo" role="tab"
                                        aria-selected="false">
                                        RPO
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @php
                        $begin = new DateTime('2022-12-31');
                        $end = new DateTime(date('Y-m'));

                        $interval = DateInterval::createFromDateString('1 year');
                        $period = new DatePeriod($begin, $interval, $end);
                        foreach ($period as $dt) {
                            $dates[] = $dt->format("Y-m-d");
                        }
                        $dates[] = date('Y-m-d');
                        $dates   = array_reverse($dates);
                    @endphp
                    <div class="card-body">
                        <div class="tab-content">
                            <div id="asmen" class="tab-pane active">
                                <div class="row">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="" class="display min-w850 datatable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
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
                                                        $year       = date_format(date_create($dt), 'Y');
                                                        $month      = date_format(date_create($dt), 'n');
                                                        $day        = date_format(date_create($dt), 'd');

                                                        if($day == "01") continue;
                                                    @endphp
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $year }}</td>
                                                            <td><a href="{{ url('report/trend-asmen/'.$year) }}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download</a></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="rpo" class="tab-pane">
                                <div class="row">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="" class="display min-w850 datatable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
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
                                                        $year       = date_format(date_create($dt), 'Y');
                                                        $month      = date_format(date_create($dt), 'n');
                                                        $day        = date_format(date_create($dt), 'd');

                                                        if($day == "01") continue;
                                                    @endphp
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $year }}</td>
                                                            <td><a href="{{ url('report/trend-rpo/'.$year) }}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download</a></td>
                                                        </tr>
                                                    @endforeach
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
    </div>
</div>
<!--**********************************
    Content body end
***********************************-->
@include('template/footer')
<script>
    $('.datatable').DataTable();
</script>
