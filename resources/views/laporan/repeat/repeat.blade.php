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
        <div class="card-body">
            <ul class="bg-light nav nav-pills mb-4">
                <li class="nav-item mt-2">
                    <a href="#navpills-1" class="nav-link custom-nav active" style="border-radius: 7px !important;" data-toggle="tab" aria-expanded="false">Repeat Order APO</a>
                </li>
                <li class="nav-item mt-2">
                    <a href="#navpills-2" class="nav-link custom-nav" style="border-radius: 7px !important;" data-toggle="tab" aria-expanded="false">Repeat Order Toko</a>
                </li>
                <li class="nav-item mt-2">
                    <a href="#navpills-3" class="nav-link custom-nav" style="border-radius: 7px !important;" data-toggle="tab" aria-expanded="true">Repeat Order VS Call</a>
                </li>
                <li class="nav-item mt-2">
                    <a href="#navpills-4" class="nav-link custom-nav" style="border-radius: 7px !important;" data-toggle="tab" aria-expanded="true">Repeat Order Transaksi Toko</a>
                </li>
                <li class="nav-item mt-2">
                    <a href="#navpills-5" class="nav-link custom-nav" style="border-radius: 7px !important;" data-toggle="tab" aria-expanded="true">Repeat Order Rutin Toko</a>
                </li>
            </ul>
            <div class="tab-content">
                <!-- Repeat Order APO -->
                <div id="navpills-1" class="tab-pane active">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Cetak Repeat Order APO</h4>
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
                                                                        <td>
                                                                            <button data-url="{{ url('cronjob/gen-ro-rpo/'.$year.'-'.$month) }}" class="generate_report_apo btn btn-sm btn-primary">
                                                                                <i class="fa fa-download"></i> Download
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
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
                                                                        <td>
                                                                            <button data-url="{{ url('cronjob/gen-ro-shop/'.$year.'-'.$month) }}" class="generate_report_apo btn btn-sm btn-primary">
                                                                                <i class="fa fa-download"></i> Download
                                                                            </button>
                                                                        </td>
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
                <!-- Repeat Order Toko -->
                <div id="navpills-2" class="tab-pane">
                    <div class="row">
                        <div class="col-12" style="margin-bottom: 5px;">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Cetak Repeat Order Toko</h4>
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
                <!-- Repeat Order VS Call -->
                <div id="navpills-3" class="tab-pane">
                    <div class="row">
                        <div class="col-12" style="margin-bottom: 5px;">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Cetak Repeat Order VS Call</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6" id="date-start2">
                                            <label for="start_month">Tahun:</label>
                                            <input type="year" class="form-control date-picker-start2" name="yearStart" required>
                                        </div>
                                        <div class="col-md-6" id="date-start2">
                                            <label for="start_month">Tipe Toko:</label>
                                            <select class="form-control default-select" id="tipe_toko">
                                                <option value="" selected>-- Pilih tipe toko --</option>
                                                @foreach($tipe_toko as $item)
                                                <option value="{{ $item->TYPE_SHOP }}">{{ $item->TYPE_SHOP }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br></br>
                                    <button id="generate_report2" class="btn btn-primary">Generate Report</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Repeat Order Trans Toko -->
                <div id="navpills-4" class="tab-pane">
                    <div class="row">
                        <div class="col-12" style="margin-bottom: 5px;">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Cetak Repeat Order Transaksi Toko</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mt-4">
                                        <div class="col-md-6" id="date-start4">
                                            <label for="start_month">Tahun:</label>
                                            <input type="year" class="form-control date-picker-start4" name="year_report4" required>
                                        </div>
                                    </div>
                                    <br></br>
                                    <button id="generate_report3" class="btn btn-primary">Generate Report</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Repeat Order Rutin Toko -->
                <div id="navpills-5" class="tab-pane">
                    <div class="row">
                        <div class="col-12" style="margin-bottom: 5px;">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Cetak Repeat Order Rutin Toko</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mt-4">
                                        <div class="col-md-6" id="date-start5">
                                            <label for="start_month">Tahun:</label>
                                            <input type="year" class="form-control date-picker-start5" name="year_report5" required>
                                        </div>
                                    </div>
                                    <br></br>
                                    <button id="generate_report4" class="btn btn-primary">Generate Report</button>
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
<style>
    .custom-nav {
        background: #ffd3cd;
        color: #FE634E;
        box-shadow: none;
        margin-right: 14px;
    }

    .toast {
        opacity: 1 !important;
    }

    #toast-container>div {
        opacity: 1 !important;
    }
</style>
@include('template/footer')
<script>
    $(document).ready(function() {
        $('.datatable').DataTable();

        $('.generate_report_apo').on('click', function() {
            DownloadFile($(this).data('url'))
        });

        $('#generate_report').on('click', function() {
            var dateStart = $("input[name='dateStart']").val();
            var dateEnd = $("input[name='dateEnd']").val();
            var regional = $("#regional").find('option:selected').val();
            var msg = ''
            if (regional.length <= 0) {
                msg = 'Tolong memilih regional terlebih dahulu'
            } else if (dateStart.length <= 0) {
                msg = 'Tolong memilih date start terlebih dahulu'
            } else if (dateEnd.length <= 0) {
                msg = 'Tolong memilih date end terlebih dahulu'
            }

            if (regional.length > 0 && dateStart.length > 0 && dateEnd.length > 0) {
                DownloadFile('{{ url("cronjob/gen-ro-shop-range") }}?dateStart=' + dateStart + '&dateEnd=' + dateEnd + '&regional=' + regional)
            } else {
                showToast(msg)
            }
        });

        $('#generate_report2').on('click', function() {
            var tipe_toko = $("#tipe_toko").find('option:selected').val();
            var yearStart = $("input[name='yearStart']").val();
            var msg = ''
            if (yearStart.length <= 0) {
                msg = 'Tolong memilih tahun terlebih dahulu'
            } else if (tipe_toko.length <= 0) {
                msg = 'Tolong memilih tipe toko terlebih dahulu'
            }

            if (yearStart.length > 0) {
                DownloadFile('{{ url("cronjob/gen-ro-vs-test") }}?yearStart=' + yearStart + '&tipe_toko=' + tipe_toko)
            } else {
                showToast(msg)
            }
        });

        $('#generate_report3').on('click', function() {
            var yearStart = $("input[name='year_report4']").val()
            if (yearStart.length <= 0) {
                msg = 'Tolong memilih tahun terlebih dahulu'
                showToast(msg)
            } else {
                DownloadFile('{{ url("cronjob/gen-ro-trans-shop/") }}/' + yearStart)
            }
        });

        $('#generate_report4').on('click', function() {
            var yearStart = $("input[name='year_report5']").val()
            if (yearStart.length <= 0) {
                msg = 'Tolong memilih tahun terlebih dahulu'
                showToast(msg)
            } else {
                DownloadFile('{{ url("cronjob/gen-ro-rutin-shop/") }}/' + yearStart)
            }
        });
    });

    $('.date-picker-start2').pickadate({
        format: 'yyyy',
        onClose: function() {
            var year = $('#date-start2').find('.picker__select--year').val()
            var date = [year].join("-")
            $('.date-picker-start2').val(date)
        },
        selectYears: true,
        buttonClear: false
    })

    $('.date-picker-start4').pickadate({
        format: 'yyyy',
        onClose: function() {
            var year = $('#date-start4').find('.picker__select--year').val()
            var date = [year].join("-")
            $('.date-picker-start4').val(date)
        },
        selectYears: true,
        buttonClear: false
    })

    $('.date-picker-start5').pickadate({
        format: 'yyyy',
        onClose: function() {
            var year = $('#date-start5').find('.picker__select--year').val()
            var date = [year].join("-")
            $('.date-picker-start5').val(date)
        },
        selectYears: true,
        buttonClear: false
    })

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

    function DownloadFile(url) {
        Swal.fire({
            title: 'Sedang Membuat Laporan ...',
            html: `Laporan sedang dibuat mohon untuk bersabar
                <br>
                <div style="background-color: transparent; width: 100px; height: 100px; display: flex; transform: translate(180%, 0%); justify-content: center; align-items: center;">
                    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <path fill="#f26f21" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                            <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                        </path>
                    </svg>
                </div>`,
            timerProgressBar: false,
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false
        })

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    showToast('Network response was not ok');
                }

                // Extract the filename from the Content-Disposition header
                const contentDisposition = response.headers.get('content-disposition');
                const filenameMatch = contentDisposition.match(/filename="(.+)"/);

                if (filenameMatch && filenameMatch.length > 1) {
                    const originalFilename = filenameMatch[1];

                    // Create a blob URL for the data
                    return response.blob().then(blob => {
                        return {
                            blob,
                            originalFilename
                        };
                    });
                } else {
                    showToast('Could not extract original filename from response headers');
                }
            })
            .then(({
                blob,
                originalFilename
            }) => {
                // Create a blob URL for the data
                const blobUrl = URL.createObjectURL(blob);

                // Create a hidden link and trigger the download with the original filename
                const a = document.createElement('a');
                a.href = blobUrl;
                a.download = originalFilename;
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();

                // Clean up
                URL.revokeObjectURL(blobUrl);

                Swal.close()
            })
            .catch(error => {
                Swal.close()
                showToast('Data kosong');
            });
    }

    function showToast(msg) {
        toastr.warning(msg, "Warning", {
            positionClass: "toast-top-right",
            timeOut: 2e3,
            closeButton: !0,
            debug: !1,
            newestOnTop: !0,
            progressBar: 0,
            preventDuplicates: 0,
            onclick: null,
            showDuration: "300",
            hideDuration: "500",
            extendedTimeOut: "500",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
            tapToDismiss: !1
        })
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