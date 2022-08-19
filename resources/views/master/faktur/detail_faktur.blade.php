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

        <!-- Add Order -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="grid mb-4">
                                    <span class="fs-20 text-black d-block mb-3">Nama User</span>
                                    <p class="fs-18 ml-4"><?= $faktur->NAME_USER ?></p>
                                    <span class="fs-20 text-black d-block mb-3">Aktifitas</span>
                                    <p class="fs-18 ml-4"><?= $faktur->NAME_TYPE ?></p>
                                    <span class="fs-20 text-black d-block mb-3">Location</span>
                                    <p class="fs-18 ml-4"><?= $faktur->LOCATION_TD ?></p>
                                    <span class="fs-20 text-black d-block mb-3">Area</span>
                                    <p class="fs-18 ml-4"><?= $faktur->AREA_TD ?></p>
                                    <span class="fs-20 text-black d-block mb-3">List Barang</span>
                                        
                                        <p class="fs-18 ml-4">Pax</p>
                                        
                                    <span class="fs-20 text-black d-block mb-3">Total Harga</span>
                                    <p class="fs-18 ml-4">IDR <?= $faktur->TOTAL_TD ?></p>
                                    <span class="fs-20 text-black d-block mb-3">Regional</span>
                                    <p class="fs-18 ml-4"><?= $faktur->REGIONAL_TD ?></p>
                                    <span class="fs-20 text-black d-block mb-3">Tanggal Faktur</span>
                                    <p class="fs-18 ml-4"><?= $faktur->DATEFACTUR_TD ? date_format(date_create($faktur->DATEFACTUR_TD), 'j F Y H:i') : "" ?></p>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="grid mb-4">
                                    @php $img = explode(';', $faktur->FACTUR_TD); @endphp
                                    <span class="fs-20 text-black d-block mb-3">Foto Faktur</span>
                                    <img src="{{$img[0]}}" style="max-width: 300px; margin-bottom: 10px" alt="">
                                    <img src="{{$img[1]}}" style="max-width: 300px; margin-bottom: 10px" alt="">
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