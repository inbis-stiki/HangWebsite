@include('template/header')
@include('template/sidebar')
<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                {{-- <button style="float: right;" data-toggle="modal" data-target="#mdlAdd"  class="btn btn-sm btn-primary">
                    <i class="flaticon-381-add-2"></i>
                    Tambah Toko
                </button> --}}
            </div>
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

        <div class="row">
            <div class="col-12" style="margin-bottom: 5px;">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="card-title">Tanggal Transaksi</h4>
                                <input name="datepicker" class="datepicker-default form-control">
                            </div>
                            <div class="col-6">
                                <h4 class="card-title">Jenis Transaksi</h4>
                                <select name="transaksi" id="SelectTrans" class="form-control default-select">
                                    <option selected value="0">All Transaksi</option>
                                    <option value="1">Spreading</option>
                                    <option value="2">UB</option>
                                    <option value="3">UBLP</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trans -->
        <div class="row" id="spreading">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Daftar Transaksi</h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatables" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Regional</th>
                                        <th>Area</th>
                                        <th>Waktu</th>
                                        <th>Aktifitas</th>
                                        <th>Jumlah Transaksi</th>
                                        <th>Aksi</th>
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

<script>
    var tgl_trans = "";
    filterData("");

    $(".datepicker-default").pickadate({
        format: 'd\ mmmm yyyy',
        onSet: function() {
            const Date = this.get('select', 'd\ mmmm yyyy')
            $('#datatables').DataTable().destroy()
            $('#datatables').DataTable().columns(4).search(Date).draw();
        }
    });

    function filterData(Date) {
        $('#datatables').DataTable({
            "processing": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                "ZeroRecords": " ",
                EmptyTable: ''
            },
            'serverMethod': 'POST',
            'ajax': {
                'url': "{{ url('master/transaction/Alltransaction') }}",
                'beforeSend': function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                'data': function(data) {
                    data.searchTrans = $('#SelectTrans').val();
                    data.tgl_trans = tgl_trans;
                }
            },
            'columns': [{
                    data: 'NO'
                },
                {
                    data: 'NAME_USER'
                },
                {
                    data: 'REGIONAL_TRANS'
                },
                {
                    data: 'AREA_TRANS'
                },
                {
                    data: 'DATE_TRANS'
                },
                {
                    data: 'NAME_TYPE'
                },
                {
                    data: 'JML_TRANS'
                },
                {
                    data: 'ACTION_BUTTON'
                }
            ],
        }).columns(4).search(Date).draw()
    }

    $('#SelectTrans').change(function() {
        $('#datatables').DataTable().destroy();
        const Date = $(".datepicker-default").val();
        filterData(Date);
    });

    // const showLocation = (long, lat) => {
    //     $('#mdlLocation_src').attr('src', `https://maps.google.com/maps?q=${lat},${long}&hl=es&z=14&amp;output=embed`);
    //     $('#mdlLocation').modal('show')
    // }
</script>