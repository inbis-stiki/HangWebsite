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

        <!-- Add Reporting Performance by Category -->
        <div class="row">
            <div class="col-12" style="margin-bottom: 5px;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Cetak Laporan Performance</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="category">Kategori Produk:</label>
                                <select id="category" name="category" class="form-control" required>
                                    <option selected disabled value=''>Pilih Kategori</option>@foreach ($category as $reg)<option value='{{ $reg->ID_PC }}'>{{ $reg->NAME_PC }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12" id="date-start">
                                <label for="start_month">Tahun:</label>
                                <input type="year" class="form-control date-picker-start" name="dateStart" required>
                            </div>
                        </div>
                        <br></br>
                        <button id="generate_report" class="btn btn-primary">Generate Report</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Reporting Recap Performance -->
        <div class="row">
            <div class="col-12" style="margin-bottom: 5px;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Cetak Laporan Rekap Performance</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col-md-12" id="date-start">
                                <label for="start_month">Tahun:</label>
                                <input type="year" class="form-control date-picker-start" name="dateStart" required>
                            </div>
                        </div>
                        <br></br>
                        <button id="generate_report_rekap" class="btn btn-primary">Generate Report</button>
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
            var category = $("#category").find('option:selected').val();

            var url = '{{ url("cronjob/gen-performance") }}?dateStart=' + dateStart + '&category=' + category;

            window.location.href = url;
        });

        $('#generate_report_rekap').on('click', function() {
            var year = $("input[name='dateStart']").val();

            var url = '{{ url("cronjob/gen-performance-rekap") }}/' + year;

            window.location.href = url;
        });
    });

    $('.date-picker-start').pickadate({
        format: 'yyyy',
        onClose: function() {
            var year = $('#date-start').find('.picker__select--year').val()
            var date = [year].join("-")
            $('.date-picker-start').val(date)
        },
        selectYears: true,
        buttonClear: false
    })
</script>
<style>
    .picker__select--month {
        font-size: 20px;
        height: 50px;
        display: none;
    }

    .picker__month {
        display: none;
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