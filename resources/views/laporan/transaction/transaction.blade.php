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
                        <h4 class="card-title">Laporan Transaksi Harian</h4>
                        <div class="card-action revenue-tabs mt-3 mt-sm-0">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#asmen" role="tab"
                                        aria-selected="false">
                                        APO/SPG
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('report/trans-daily') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Pilih Area</label>
                                <select name="idRegional" id="slctArea" class="form-control">
                                    @foreach ($regionals as $regional)
                                        <option value="{{ $regional->ID_REGIONAL }}" {{ !empty($idReg) && $idReg == $regional->ID_REGIONAL ? "selected" : ""}}>{{ $regional->NAME_REGIONAL }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Pilih Tanggal</label>
                                <input value="<?= (date_format(date_create(date("Y-m-d")), 'j F Y')); ?>" name="date" class="datepicker-default form-control">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 mb-3"><i class="fa fa-download"></i> Download Laporan</button>
                        </form>
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
    $('#datatable').DataTable();
    $(".datepicker-default").pickadate({
        format: 'd\ mmmm yyyy',
        clear: 'All Time',
        max: new Date(),
        onSet: function() {
            tgl_trans = this.get('select', 'yyyy-mm-dd');
        }
    });
</script>
