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
                                    <option selected value="">All Regional</option>
                                    @foreach($data_regional as $item)
                                    <option value="{{$item->NAME_REGIONAL}}">{{$item->NAME_REGIONAL}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <h4 class="card-title">Tipe Toko / Kategori Produk</h4>
                                <select name="shopProd" id="SelectShopKat" class="select2">
                                    <option selected value="">Show All</option>
                                    @foreach($shop_prod as $key => $item)
                                    @if($key == 'SHOP_CATEGORY')
                                    <optgroup label="Tipe Toko">
                                        @foreach($item as $itemChild)
                                        <option value="{{$itemChild->NAMA}}" data-tipeFilter='{{$itemChild->TYPE}}'>{{$itemChild->NAMA}}</option>
                                        @endforeach
                                    </optgroup>
                                    @endif
                                    @if($key == 'PRODUCT_CATEGORY')
                                    <optgroup label="Kategori Produk">
                                        @foreach($item as $itemChild)
                                        <option value="{{$itemChild->ID_CAT}}" data-tipeFilter='{{$itemChild->TYPE}}'>{{$itemChild->NAMA}}</option>
                                        @endforeach
                                    </optgroup>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <div class="d-flex align-items-end justify-content-end h-100">
                                    <button type="button" id="btn-filter-omset" class="btn btn-primary mr-3"><i class="fa-solid fa-filter"></i></button>
                                    <button type="button" id="btn-download-omset" class="btn btn-primary"><i class="fa-solid fa-file-arrow-down"></i></button>
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
    var typeShopProduct = $('#SelectShopKat').find(':selected').attr('data-tipeFilter')
    const tableElem = $('#datatable')

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

    $('#btn-filter-omset').click(function() {
        regional = $('#SelectRegional').val()
        shopProduct = $('#SelectShopKat').val()
        typeShopProduct = $('#SelectShopKat').find(':selected').attr('data-tipeFilter')

        tableElem.DataTable().destroy();
        filterDataOmset()
    })
</script>