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
                            <div class="col-4">
                                <h4 class="card-title">Regional</h4>
                                <select name="transaksi" id="SelectRegional" class="select2">
                                    <?php $valAll = []; ?>
                                    <?php foreach ($data_regional as $item) : ?>
                                        <?php array_push($valAll, $item->NAME_REGIONAL); ?>
                                    <?php endforeach; ?>
                                    <option selected value="<?= implode(';', $valAll) ?>" selected>All Regional</option>
                                    @foreach($data_regional as $item)
                                    <option value="{{$item->NAME_REGIONAL}}">{{$item->NAME_REGIONAL}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <h4 class="card-title">Tipe Filter</h4>
                                <select name="shopProd" id="TypeShopKat" class="select2">
                                    <option selected value="">Show All</option>
                                    <option value="SHOP_CATEGORY">Tipe Toko</option>
                                    <option value="PRODUCT_CATEGORY">Kategori Produk</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <h4 class="card-title">Tipe Toko / Kategori Produk</h4>
                                <select name="shopProd" id="SelectShopKat" class="select2">
                                    <option selected value="">Show All</option>
                                </select>
                            </div>
                            <div class="col-12 mt-5">
                                <div class="d-flex align-items-end justify-content-end h-100">
                                    <button type="button" id="btn-download-omset" class="btn btn-primary mr-3"><i class="fa-solid fa-file-arrow-down"></i> Generate Excell</button>
                                    <button type="button" id="btn-filter-omset" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filter Table</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $indMonth = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']; ?>
        <!-- Add Order -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table-bordered display min-w850">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="vertical-align: middle !important; width: 99px !important; background-color: white !important;">APO/SPG</th>
                                        @for ($month = 1; $month <= 12; $month++)
                                            <th colspan="2" class="text-center">{{ $indMonth[($month - 1)] }}</th>
                                            @endfor
                                    </tr>
                                    <tr>
                                        @for ($month = 1; $month <= 12; $month++)
                                            <th>Total Omset</th>
                                            <th>Jumlah Outlet</th>
                                            @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .dataTables_processing {
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: rgba(0, 0, 0, 0.5) !important;
        z-index: 10000 !important;
        justify-content: center !important;
        align-items: center !important;
        margin-left: 0px !important;
        margin-top: 0px !important;
        text-align: center !important;
        padding: 0px !important;
    }

    .prevent-horizontal-scroll {
        overflow-x: hidden !important;
    }

    table.dataTable.display tbody tr:hover td {
        background-color: white !important;
    }
</style>
@include('template/footer')
<script>
    var regional = $('#SelectRegional').val()
    var shopProduct = $('#SelectShopKat').val()
    var typeShopProduct = $('#TypeShopKat').val()
    const tableElem = $('#datatable')

    var dataKat = JSON.parse(`<?= json_encode($shop_prod) ?>`)

    var customColumn = [{
        data: 'NAME_USER'
    }]

    for (let index = 1; index <= 12; index++) {
        customColumn.push({
            data: 'TOTAL_OMSET_' + index,
            mData: 'TOTAL_OMSET_' + index
        }, {
            data: 'JML_OUTLET_' + index,
            mData: 'JML_OUTLET_' + index
        })
    }

    $.fn.dataTable.pipeline = function(opts) {
        var conf = $.extend(opts);

        var cacheLower = -1;
        var cacheUpper = null;
        var cacheLastRequest = null;
        var cacheLastJson = null;

        return function(request, drawCallback, settings) {
            var ajax = false;
            var requestStart = request.start;
            var drawStart = request.start;
            var requestLength = request.length;
            var requestEnd = requestStart + requestLength;

            if (settings.clearCache) {
                ajax = true;
                settings.clearCache = false;
            } else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
                ajax = true;
            } else if (
                JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
            ) {
                ajax = true;
            }

            cacheLastRequest = $.extend(true, {}, request);

            if (ajax) {
                if (requestStart < cacheLower) {
                    requestStart = requestStart - requestLength * (conf.pages - 1);
                    if (requestStart < 0) {
                        requestStart = 0;
                    }
                }

                cacheLower = requestStart;
                cacheUpper = requestStart + requestLength * conf.pages;

                request.start = requestStart;
                request.length = requestLength * conf.pages;


                if (typeof conf.data === 'function') {
                    var d = conf.data(request);
                    if (d) {
                        $.extend(request, d);
                    }
                } else if ($.isPlainObject(conf.data)) {

                    $.extend(request, conf.data);
                }

                return $.ajax({
                    type: conf.method,
                    url: conf.url,
                    data: request,
                    dataType: 'json',
                    cache: false,
                    success: function(json) {
                        cacheLastJson = $.extend(true, {}, json);

                        if (cacheLower != drawStart) {
                            json.data.splice(0, drawStart - cacheLower);
                        }
                        if (requestLength >= -1) {
                            json.data.splice(requestLength, json.data.length);
                        }

                        drawCallback(json);
                    },
                });
            } else {
                json = $.extend(true, {}, cacheLastJson);
                json.draw = request.draw;
                json.data.splice(0, requestStart - cacheLower);
                json.data.splice(requestLength, json.data.length);

                drawCallback(json);
            }
        };
    };

    function filterDataOmset() {
        tableElem.DataTable({
            "fixedColumns": {
                start: 1,
                end: 1
            },
            "scrollX": true,
            "ordering": false,
            "processing": true,
            "serverSide": true,
            "search": {
                return: true,
            },
            "language": {
                "processing": `<svg version="1.1" id="L4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve" style="width: 250px;">
                    <circle fill="#f26e23" stroke="none" cx="6" cy="50" r="6">
                        <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.1"></animate>    
                    </circle>
                    <circle fill="#f26e23" stroke="none" cx="26" cy="50" r="6">
                        <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.2"></animate>       
                    </circle>
                    <circle fill="#f26e23" stroke="none" cx="46" cy="50" r="6">
                        <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.3"></animate>     
                    </circle>
                </svg>
                `,
                "loadingRecords": "Loading...",
                "emptyTable": "  "
            },
            "ajax": $.fn.dataTable.pipeline({
                pages: 3,
                url: "<?= url('master/all-omset-data') ?>",
                crossDomain: false,
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'regional': regional,
                    'typeShopProduct': typeShopProduct,
                    'shopProduct': shopProduct
                },
                method: 'POST',
            }),
            "infoCallback": function(settings, start, end, max, total, pre) {
                return (!isNaN(total) && total > 0) ?
                    "Showing " + start + " to " + end + " of " + total + " entries" +
                    ((total !== max) ? " (filtered from " + max + " total entries)" : "") :
                    "No Data to Show";
            },
            "columns": customColumn,
        })
    }

    tableElem.on('processing.dt', function(e, settings, processing) {
        var tableContainer = $('#datatable_wrapper');

        if (processing) {
            // Add class to prevent horizontal scrolling
            tableContainer.addClass('prevent-horizontal-scroll');
        } else {
            // Remove class to allow horizontal scrolling
            tableContainer.removeClass('prevent-horizontal-scroll');
        }
    });

    filterDataOmset()

    $('#SelectRegional').change(function() {
        setterValue()
    })

    $('#SelectShopKat').change(function() {
        setterValue()
    })

    $('#TypeShopKat').change(function() {
        $('#SelectShopKat').empty();
        $('#SelectShopKat').append(`<option selected value="">Show All</option>`);
        if ($(this).val() == 'PRODUCT_CATEGORY') {
            $.each(dataKat.PRODUCT_CATEGORY, function(index, item) {
                $('#SelectShopKat').append(`<option value="${item.NAMA}" data-tipeFilter="${item.TYPE}">${item.NAMA}</option>`);
            });
        } else if ($(this).val() == 'SHOP_CATEGORY') {
            $.each(dataKat.SHOP_CATEGORY, function(index, item) {
                $('#SelectShopKat').append(`<option value="${item.NAMA}" data-tipeFilter="${item.TYPE}">${item.NAMA}</option>`);
            });
        }
        setterValue()
    });

    $('#btn-filter-omset').click(function() {
        setterValue()
        tableElem.DataTable().destroy();
        filterDataOmset()
    })

    function setterValue() {
        regional = $('#SelectRegional').val()
        shopProduct = $('#SelectShopKat').val()
        typeShopProduct = $('#TypeShopKat').val()
    }

    $('#btn-download-omset').click(function() {
        if (regional) {
            DownloadFile(`<?= url('cronjob/gen-data-omset') ?>?prodShop=${shopProduct}&type=${typeShopProduct}&regional=${regional}`)
        } else {
            showToast('Laporan gagal di generate. jika ingin melakukan generate laporan, regional tidak boleh kosong.');
        }
    })

    function DownloadFile(url) {
        Swal.fire({
            title: 'Sedang Membuat Laporan ...',
            html: `Laporan sedang dibuat mohon untuk bersabar
                <br>
                <div style="background-color: transparent; width: 100px; height: 100px; display: flex; transform: translate(180%, 0%); justify-content: center; align-items: center;">
                    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <path fill="#017025" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
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
                // if (!response.ok) {
                //     showToast('Laporan gagal di generate. Mohon cek kembali jaringan anda.');
                // }

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
                    showToast('Laporan gagal di generate. Gagal mendapatkan nama original file.');
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
                showToast('Laporan gagal di generate. Tidak ada data atau sistem sedang mengalami error.');
            });
    }

    function showToast(msg) {
        toastr.warning(msg, "Warning", {
            positionClass: "toast-top-right",
            timeOut: 3e3,
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