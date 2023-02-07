@include('template/header')
@include('template/sidebar')

<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- container starts -->
    <div class="container-fluid">
        <!-- Row starts -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Location</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div id='map'></div>
                                <canvas id="canvasID" style="border-radius: 10px !important;" height="480">Canvas not supported</canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-block">
                                <h4 class="card-title">Effective Calls</h4>
                            </div>
                            <div class="card-body text-center">
                                @php
                                $totTrans = count($shop_trans2);
                                $totNoTrans = count($shop_no_trans2);
                                $totAllTrans = $totTrans + $totNoTrans;
                                @endphp
                                <h1 class="text-primary">{{ ($totNoTrans != 0 ? number_format(($totTrans / $totAllTrans)*100, 0) : "100") }}%</h1>
                                <small class="text-default">{{ $totTrans }} Transaksi dari {{ $totAllTrans }} Kunjungan</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-block">
                                <h4 class="card-title">Total Penjualan <span class="mx-3 badge badge-sm light badge-success">{{ $all_sell }}</span></h4>
                            </div>
                            <div class="card-body">
                                <div class="card text-black">
                                    <div class="card-body">
                                        <ul class="nav nav-pills justify-content-center mb-2">
                                            @php
                                            $isActive = "active";
                                            @endphp
                                            @foreach ($prodCats as $prodCat)
                                            <li class=" nav-item">
                                                <a href="#navpills2-{{ $prodCat->ID_PC }}" class="nav-link {{ $isActive }}" style="font-size: 10px;" data-toggle="tab" aria-expanded="false">{{ $prodCat->NAME_PC }}</a>
                                            </li>

                                            @php $isActive = "";@endphp
                                            @endforeach
                                        </ul>
                                        <div class="tab-content">
                                            @php
                                            $isActive = "active";
                                            $sumVal = 0;
                                            @endphp
                                            @foreach ($prodCats as $prodCat)
                                            <div id="navpills2-{{ $prodCat->ID_PC }}" class="tab-pane {{ $isActive }}" style="height: 290px;overflow: hidden;overflow-y: scroll;">
                                                <div class="table-responsive">
                                                    <table id="table" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="fs-12" scope="col">Nama</th>
                                                                <th class="fs-12" scope="col" width="10%">Qty</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($transDetails[$prodCat->ID_PC]))
                                                            @foreach ($transDetails[$prodCat->ID_PC] as $name => $total)
                                                            @php $sumVal += $total; @endphp
                                                            <tr class="alert alert-dismissible fs-12">
                                                                <td>{{ $name }} </td>
                                                                <td>{{ $total }}</td>
                                                            </tr>
                                                            @endforeach
                                                            @else
                                                            <tr>
                                                                <td colspan="2" class="text-center fs-12">Tidak ada transaksi</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                        Total: <?= $sumVal ?>
                                                    </table>
                                                </div>
                                            </div>
                                            @php
                                            $isActive = "";
                                            $sumVal = 0;
                                            @endphp
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Column starts -->
            <div class="col-xl-12">
                <div class="card">
                    {{-- <div class="card-header d-block">
                        <h4 class="card-title">Location</h4>
                        <div class="row">
                            <div class="col-lg-8 overflow-hidden">
                                <div id='map'></div>
                                <canvas id="canvasID" style="border-radius: 10px" height="780">Canvas not supported</canvas>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header d-block">
                                                <h4 class="card-title">Effective Calls</h4>
                                            </div>
                                            <div class="card-body text-center">
                                                <h1 class="text-primary">40%</h1>
                                                <h4 class="text-secondary">2 Transaksi dari 5 Kunjungan</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="card-body">
                        <div id="accordion-two" class="accordion accordion-primary-solid">
                            <?php
                            $no = 0;
                            $coords = array();
                            $other_coords = array();
                            $other_coords_con2 = array();
                            foreach ($transaction as $data_spread) {
                                $bg = "";
                                $bg_border = "";
                                if ($data_spread['TOTAL'] == 0) {
                                    $bg = 'style="background: #1F51FF;border-color: #1F51FF;"';
                                    $bg_border = 'style="border: 2px solid #1F51FF;"';
                                }

                                if ($data_spread['TOTAL'] != 0) {
                                    $bg = "";
                                    $bg_border = "";
                                }
                            ?>
                                <div class="accordion__item">
                                    <div class="accordion__header collapsed" data-toggle="collapse" data-target="#bordered_collapse<?= $no; ?>" aria-expanded="false" <?= $bg ?>>
                                        <span class="accordion__header--text"><?= $data_spread['NAME_SHOP']; ?></span>
                                        <span class="accordion__header--text float-right mr-4"><?= date_format(date_create($data_spread['DATE_TRANS']), 'j F Y - H:i'); ?></span>
                                        <span class="accordion__header--indicator"></span>
                                    </div>
                                    <div id="bordered_collapse<?= $no; ?>" class="collapse accordion__body" data-parent="#accordion-two" <?= $bg_border ?>>
                                        <div class="accordion__body--text">
                                            <span class="fs-20 text-black d-block mb-3">Detail Informasi</span>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="fs-18 ml-3">Nama</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="fs-18 float-right"><?= $data_spread['NAME_SHOP']; ?></p>
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="fs-18 ml-3">Tanggal</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="fs-18 float-right"><?= date_format(date_create($data_spread['DATE_TRANS']), 'j F Y H:i'); ?></p>
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="fs-18 ml-3">Alamat</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="fs-18 float-right"><?= $data_spread['DETLOC_SHOP']; ?></p>
                                                </div>
                                            </div>
                                            <span class="fs-20 text-black d-block mb-3">Produk Terjual</span>
                                            <?php if (!empty($data_spread['DETAIL'])) { ?>
                                                <?php foreach ($data_spread['DETAIL'] as $data_spread_detail) : ?>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <p class="fs-18 ml-3"><?= $data_spread_detail->NAME_PRODUCT; ?></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="fs-18 mr-3 float-right text-center">
                                                                <?= $data_spread_detail->QTY_TD; ?>
                                                                <hr style="border-top: 3px solid #bbb; margin: 0px; width: 30px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php } else { ?>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <p class="fs-18 ml-3">Anda Belum Transaksi</p>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <span class="fs-20 text-black d-block mb-3">Foto Transaksi</span>
                                            <div class="row">
                                                <div class="col-md-12 d-flex">
                                                    <?php if (!empty($data_spread['IMAGE'])) { ?>
                                                        <?php foreach ($data_spread['IMAGE'] as $image) { ?>
                                                            <div class="form-group col-md-6">
                                                                <img decoding="async" src="<?= $image; ?>" style="max-width: 300px; margin-bottom: 10px; content-visibility: auto;" alt="">
                                                            </div>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <div class="form-group col-md-6">
                                                            <label for="">NO IMAGE</label>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php
                                $no++;
                            }

                                
                            ?>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
            <!-- Column ends -->
        </div>
        <!-- Row ends -->
    </div>
    <!-- container ends -->
</div>
<!--**********************************
    Content body end
***********************************-->
<style>
    #map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 97%;
    }
</style>
@include('template/footer')