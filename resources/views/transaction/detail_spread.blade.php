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
            <!-- Column starts -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-block">
                        <h4 class="card-title">Location</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id='map'></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="accordion-two" class="accordion accordion-danger-solid">
                            <?php
                            $no = 0;
                            $coords = array();
                            foreach ($transaction as $data_ublp) :
                            ?>
                                <div class="accordion__item">
                                    <div class="accordion__header collapsed" data-toggle="collapse" data-target="#bordered_collapse<?= $no; ?>" aria-expanded="false">
                                        <span class="accordion__header--text"><?= $data_ublp['LOCATION']; ?></span>
                                        <span class="accordion__header--indicator"></span>
                                    </div>
                                    <div id="bordered_collapse<?= $no; ?>" class="collapse accordion__body">
                                        <div class="accordion__body--text">
                                            <span class="fs-20 text-black d-block mb-3">Detail Informasi</span>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="fs-18 ml-3">Nama</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="fs-18 float-right"><?= $data_ublp['NAME_USER']; ?></p>
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="fs-18 ml-3">Tanggal</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="fs-18 float-right"><?= $data_ublp['DATE_TRANS']; ?></p>
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="fs-18 ml-3">Alamat</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="fs-18 float-right"><?= $data_ublp['LOCATION']; ?></p>
                                                </div>
                                            </div>
                                            <span class="fs-20 text-black d-block mb-3">Produk Terjual</span>
                                            @foreach ($data_ublp['DETAIL'] as $data_ublp_detail)
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="fs-18 ml-3"><?= $data_ublp_detail->NAME_PRODUCT; ?></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="fs-18 mr-3 float-right text-center">
                                                        <?= $data_ublp_detail->QTY_TD; ?>
                                                        <hr style="border-top: 3px solid #bbb; margin: 0px; width: 30px;">
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            <span class="fs-20 text-black d-block mb-3">Foto Transaksi</span>
                                            <div class="row">
                                                <div class="col-md-12 d-flex">
                                                    <div class="form-group col-md-6">
                                                        <label for="">Foto 1</label>
                                                        <br>
                                                        <img src="<?= $data_ublp['IMAGE'][0][0]; ?>" style="max-width: 300px; margin-bottom: 10px" alt="">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="">Foto 2</label>
                                                        <br>
                                                        <img src="<?= $data_ublp['IMAGE'][0][1]; ?>" style="max-width: 300px; margin-bottom: 10px" alt="">
                                                    </div>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php
                                array_push(
                                    $coords,
                                    array('loc' => $data_ublp['LOCATION'], 'lat' => $data_ublp['LAT_TRANS'], 'lng' => $data_ublp['LONG_TRANS'])
                                );
                                $no++;
                            endforeach;
                            ?>
                        </div>
                    </div>
                    <?php
                    $centerCord = get_center($coords);

                    function get_center($coords)
                    {
                        $count_coords = count($coords);
                        $xcos = 0.0;
                        $ycos = 0.0;
                        $zsin = 0.0;

                        foreach ($coords as $lnglat) {
                            $lat = $lnglat['lat'] * pi() / 180;
                            $lon = $lnglat['lng'] * pi() / 180;

                            $acos = cos($lat) * cos($lon);
                            $bcos = cos($lat) * sin($lon);
                            $csin = sin($lat);
                            $xcos += $acos;
                            $ycos += $bcos;
                            $zsin += $csin;
                        }

                        $xcos /= $count_coords;
                        $ycos /= $count_coords;
                        $zsin /= $count_coords;
                        $lon = atan2($ycos, $xcos);
                        $sqrt = sqrt($xcos * $xcos + $ycos * $ycos);
                        $lat = atan2($zsin, $sqrt);

                        return array($lat * 180 / pi(), $lon * 180 / pi());
                    }
                    ?>
                    <script>
                        mapboxgl.accessToken = 'pk.eyJ1IjoiZGV2aXNhcCIsImEiOiJjbDZ5aHY1bzUwdmh2M2JwZG1zNDhkaXRlIn0.Wl1xSypefSjvpjdL7iGb7Q';

                        const map = new mapboxgl.Map({
                            container: 'map',
                            style: 'mapbox://styles/mapbox/outdoors-v11',
                            center: [<?= $centerCord[1] ?>, <?= $centerCord[0] ?>],
                            zoom: 16
                        });

                        map.loadImage(
                            'https://docs.mapbox.com/mapbox-gl-js/assets/custom_marker.png',
                            (error, image) => {
                                if (error) throw error;
                                map.addImage('custom-marker', image);
                                map.setRenderWorldCopies(false);

                                // Add a GeoJSON source with 2 points
                                <?php for ($i = 0; $i < count($coords); $i++) { ?>
                                    map.addSource('points<?= $i; ?>', {
                                        'type': 'geojson',
                                        'data': {
                                            'type': 'FeatureCollection',
                                            'features': [{
                                                'type': 'Feature',
                                                'geometry': {
                                                    'type': 'Point',
                                                    // 'coordinates': [Longitude, Latitude]
                                                    'coordinates': [<?= $coords[$i]['lng']; ?>, <?= $coords[$i]['lat']; ?>]
                                                },
                                                'properties': {
                                                    'title': '<?= $coords[$i]['loc']; ?>'
                                                }
                                            }]
                                        }
                                    });

                                    // Add a symbol layer
                                    map.addLayer({
                                        'id': 'points<?= $i; ?>',
                                        'type': 'symbol',
                                        'source': 'points<?= $i; ?>',
                                        'layout': {
                                            'icon-image': 'custom-marker',
                                            'text-field': ['get', 'title'],
                                            'text-font': [
                                                'Open Sans Semibold',
                                                'Arial Unicode MS Bold'
                                            ],
                                            'text-offset': [0, 1.25],
                                            'text-anchor': 'top'
                                        }
                                    });

                                <?php } ?>
                            }
                        );
                    </script>
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
    .mapboxgl-canvas {
        width: 100%;
        height: auto;
        position: relative;
    }
</style>
@include('template/footer')