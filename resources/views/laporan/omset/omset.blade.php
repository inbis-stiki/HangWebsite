@include('template/header')
@include('template/sidebar')
<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        @if ($errors->any())
        <div class="alert alert-danger" style="margin-top: 1rem;">{{ $errors->first() }}</div>
        @endif
        @if (session('succ_msg'))
        <div class="alert alert-success">{{ session('succ_msg') }}</div>
        @endif
        @if (session('err_msg'))
        <div class="alert alert-danger">{{ session('err_msg') }}</div>
        @endif

        <div class="row">
            <div class="col-12" style="margin-bottom: 5px;">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="card-title">Regional Presensi</h4>
                                <select name="transaksi" id="SelectRegional" class="select2">
                                    <option selected value="0">All Regional</option>
                                    @foreach($data_regional as $item)
                                    <option value="{{$item->ID_AREA}}">{{$item->NAME_AREA}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Order -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Daftar Omset</h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="datatable" class="display min-w850">
                            <thead>
                                <tr>
                                    <th>APO/SPG</th>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <th colspan="2">{{ $month }}</th>
                                    @endfor
                                </tr>
                                <tr>
                                    <th></th>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <th>Total Omset</th>
                                        <th>Jumlah Outlet</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($result as $user)
                                <tr>
                                    <td>{{ $user->NAME_USER }}</td>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <td>{{ $user->{'MONTH' . $month . '_TOTAL_OMSET'} ?? 0 }}</td>
                                        <td>{{ $user->{'MONTH' . $month . '_TOTAL_OUTLET'} ?? 0 }}</td>
                                    @endfor
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
@include('template/footer')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    });
</script>