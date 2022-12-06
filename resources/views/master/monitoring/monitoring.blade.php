@include('template/header')
@include('template/sidebar')
<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-md-6 flex-column mb-3">
                    <h5 class="font-weight-bold">Date</h5>
                    <div class="card border mb-0 px-0">
                        <input class="datepicker-monitoring form-control" placeholder="<?= (date_format(date_create(date("Y-m-d")), 'j F Y')); ?>" name="datepicker">
                    </div>
                </div>
                <div class="col-md-6 flex-column mb-3">
                    <h5 class="font-weight-bold">Regional</h5>
                    <div class="card border mb-0 px-0">
                        <select name="transaksi" id="SelectRegional" class="select2">
                            <option selected value="0">All Regional</option>
                            @foreach($data_regional as $item)
                            <option value="{{$item->ID_REGIONAL}}">{{$item->NAME_REGIONAL}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Presensi</h5>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <a class="text-primary" href="javascript:void(0)">Lihat Semua ></a>
                </div>
            </div>

            <div class="row">
                <div class="col col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="col-md-12 d-flex justify-content-start">
                                <h5 class="text-success ">
                                    < 07:00</h5>
                            </span>
                            <span class="text-default d-flex justify-content-end" style="font-size: 56px; font-weight: 600; color: #000;" id="presence_1">
                                <img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="col-md-12 d-flex justify-content-start">
                                <h5 class="text-success ">
                                    07:00 - 08:00</h5>
                            </span>
                            <span class="text-default d-flex justify-content-end" style="font-size: 56px; font-weight: 600; color: #000;" id="presence_2">
                                <img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="col-md-12 d-flex justify-content-start">
                                <h5 class="text-primary">
                                    > 08:00</h5>
                            </span>
                            <span class="text-default d-flex justify-content-end" style="font-size: 56px; font-weight: 600; color: #000;" id="presence_3">
                                <img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Transaksi</h5>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <a class="text-primary" href="javascript:void(0)">Lihat Semua ></a>
                </div>
            </div>

            <div class="row">
                <div class="col col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="col-md-12 d-flex justify-content-start">
                                <h5 class="text-primary ">
                                    < 10</h5>
                            </span>
                            <span class="text-default d-flex justify-content-end" style="font-size: 56px; font-weight: 600; color: #000;" id="trans_1">
                                <img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="col-md-12 d-flex justify-content-start">
                                <h5 class="text" style="color: #FFB743 !important;">
                                    11-20</h5>
                            </span>
                            <span class="text-default d-flex justify-content-end" style="font-size: 56px; font-weight: 600; color: #000;" id="trans_2">
                                <img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="col-md-12 d-flex justify-content-start">
                                <h5 class="text-success ">
                                    21-30</h5>
                            </span>
                            <span class="text-default d-flex justify-content-end" style="font-size: 56px; font-weight: 600; color: #000;" id="trans_3">
                                <img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <span class="col-md-12 d-flex justify-content-start">
                                <h5 class="text-success ">
                                    > 30</h5>
                            </span>
                            <span class="text-default d-flex justify-content-end" style="font-size: 56px; font-weight: 600; color: #000;" id="trans_4">
                                <img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Order -->

    </div>
</div>
<!--**********************************
    Content body end
***********************************-->
@include('template/footer')
<script>
    var tgl_trans = "<?= date("Y-m-d"); ?>";
    var RegionalSearch = "0"
    get_data_monitoring()
    $(".datepicker-monitoring").pickadate({
        format: 'd\ mmmm yyyy',
        onSet: function() {
            tgl_trans = this.get('select', 'yyyy-mm-dd');
            get_data_monitoring()
        }
    });

    $('#SelectRegional').change(function(e) {
        RegionalSearch = $('#SelectRegional').val()
        get_data_monitoring()
    });

    function get_data_monitoring() {
        loading()
        $.ajax({
            url: "{{ url('monitoring/monitoring-data') }}",
            type: "POST",
            crossDomain: true,
            beforeSend: function(request) {
                request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            },
            data: {
                date: tgl_trans,
                area: RegionalSearch
            },
            dataType: "json",
            success: function(response) {
                $('#presence_1').html(response.data.PRESENCE[0].PRESENCE_1)
                $('#presence_2').html(response.data.PRESENCE[0].PRESENCE_2)
                $('#presence_3').html(response.data.PRESENCE[0].PRESENCE_3)

                $('#trans_1').html((response.data.TRANS.TRANS_1[0].TOT_TRANS != null) ? response.data.TRANS.TRANS_1[0].TOT_TRANS : 0)
                $('#trans_2').html((response.data.TRANS.TRANS_2[0].TOT_TRANS != null) ? response.data.TRANS.TRANS_2[0].TOT_TRANS : 0)
                $('#trans_3').html((response.data.TRANS.TRANS_3[0].TOT_TRANS != null) ? response.data.TRANS.TRANS_3[0].TOT_TRANS : 0)
                $('#trans_4').html((response.data.TRANS.TRANS_4[0].TOT_TRANS != null) ? response.data.TRANS.TRANS_4[0].TOT_TRANS : 0)
            }
        });
    }

    function loading() {
        $('#presence_1').html("<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>")
        $('#presence_2').html("<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>")
        $('#presence_3').html("<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>")

        $('#trans_1').html("<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>")
        $('#trans_2').html("<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>")
        $('#trans_3').html("<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>")
        $('#trans_4').html("<img src='{{ asset('images/loader.gif') }}' style='max-width: 150px;' alt=''>")
    }
</script>