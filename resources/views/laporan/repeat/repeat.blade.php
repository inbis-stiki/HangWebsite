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
                            <div id="asmen" class="tab-pane active">
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