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
                                <h4 class="card-title">Total Transaksi per type</h4>
                            </div>
                            <div class="card-body text-center">
                            <h4 class="card-title">Pedagang Sayur <span class="mx-3 badge badge-sm light badge-success">{{ $transaction[0]['TOT_PS'] }}</span></h4>
                            <h4 class="card-title">Retail <span class="mx-3 badge badge-sm light badge-success">{{ $transaction[0]['TOT_RETAIL'] }}</span></h4>
                            <h4 class="card-title">Loss <span class="mx-3 badge badge-sm light badge-success">{{ $transaction[0]['TOT_LOSS'] }}</span></h4>
                            <h4 class="card-title">Permanen <span class="mx-3 badge badge-sm light badge-success">{{ $transaction[0]['TOT_PERMA'] }}</span></h4>
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
                                        @if($data_spread['TYPE_SHOP'] == 'Pedagang Sayur')
                                        <span class="badge badge-success">Pedagang Sayur</span>
                                        @elseif($data_spread['TYPE_SHOP'] == 'Retail')
                                        <span class="badge badge-secondary">Retail</span>
                                        @elseif($data_spread['TYPE_SHOP'] == 'Loss')
                                        <span class="badge badge-light">Loss</span>
                                        @elseif($data_spread['TYPE_SHOP'] == 'Permanen')
                                        <span class="badge badge-danger">Permanen</span>
                                        @endif
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

                            foreach ($shop_trans as $other_shop) {
                                array_push(
                                    $coords,
                                    array('loc' => $other_shop->NAME_SHOP, 'lat' => $other_shop->LAT_SHOP, 'lng' => $other_shop->LONG_SHOP, 'total' => $other_shop->TOTAL)
                                );
                            }

                            foreach ($shop_no_trans as $other_shop) {
                                array_push(
                                    $other_coords,
                                    array('loc' => $other_shop->NAME_SHOP, 'lat' => $other_shop->LAT_SHOP, 'lng' => $other_shop->LONG_SHOP)
                                );
                            }

                            foreach ($shop_no_con2_trans as $other_shop) {
                                array_push(
                                    $other_coords_con2,
                                    array('loc' => $other_shop->NAME_SHOP, 'lat' => $other_shop->LAT_SHOP, 'lng' => $other_shop->LONG_SHOP)
                                );
                            }
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
                    <style>
                        #map {
                            width: 100%;
                            height: 640px;
                            border-radius: 10px;
                        }

                        @media screen and (max-width: 600px) {
                            #map {
                                max-height: 70vh;
                            }
                        }

                        .filter-control label {
                            display: flex;
                            padding: 10px;
                            font-weight: 600;
                            color: #fff;
                            text-transform: uppercase;
                            cursor: pointer;
                            background-color: #017025;
                            border-radius: 3px;
                            box-shadow: 0 0 2px rgba(255, 205, 56, 0.5);
                        }

                        .filter-control input[type="checkbox"]:checked:after {
                            background-color: #5BB318;
                        }
                    </style>
                    <script>
                        const MAPBOX_API_KEY = 'pk.eyJ1IjoiZGV2aXNhcCIsImEiOiJjbDZ5aHY1bzUwdmh2M2JwZG1zNDhkaXRlIn0.Wl1xSypefSjvpjdL7iGb7Q';
                        const MAPBOX_STYLE = 'mapbox://styles/mapbox/streets-v11';

                        mapboxgl.accessToken = MAPBOX_API_KEY;

                        let features = {
                            'type': 'FeatureCollection',
                            'features': [
                                <?php for ($i = 0; $i < count($coords); $i++) { ?> {
                                        'type': 'Feature',
                                        'geometry': {
                                            'type': 'Point',
                                            // 'coordinates': [Longitude, Latitude]
                                            'coordinates': [<?= $coords[$i]['lng']; ?>, <?= $coords[$i]['lat']; ?>]
                                        },
                                        'properties': {
                                            "title": "<?= $coords[$i]['loc']; ?>",
                                            "description": "<strong><?= $coords[$i]['loc']; ?></strong><p>Total Penjualan : <?= $coords[$i]['total']; ?></p>",
                                            "availability": 'Available',
                                            "iconSize": [40, 40]
                                        }
                                    },
                                <?php } ?>
                                <?php for ($i = 0; $i < count($other_coords); $i++) { ?> {
                                        'type': 'Feature',
                                        'geometry': {
                                            'type': 'Point',
                                            // 'coordinates': [Longitude, Latitude]
                                            'coordinates': [<?= $other_coords[$i]['lng']; ?>, <?= $other_coords[$i]['lat']; ?>]
                                        },
                                        'properties': {
                                            "title": "<?= $other_coords[$i]['loc']; ?>",
                                            "description": "<strong><?= $other_coords[$i]['loc']; ?></strong><p>Toko dengan transaksi lain</p>",
                                            "iconSize": [40, 40]
                                        }
                                    },
                                <?php } ?>
                                <?php for ($i = 0; $i < count($other_coords_con2); $i++) { ?> {
                                        'type': 'Feature',
                                        'geometry': {
                                            'type': 'Point',
                                            // 'coordinates': [Longitude, Latitude]
                                            'coordinates': [<?= $other_coords_con2[$i]['lng']; ?>, <?= $other_coords_con2[$i]['lat']; ?>]
                                        },
                                        'properties': {
                                            "title": "<?= $other_coords_con2[$i]['loc']; ?>",
                                            "description": "<strong><?= $other_coords_con2[$i]['loc']; ?></strong><p>Toko tidak melakukan transaksi</p>",
                                            "availability": 'Available-con2',
                                            "iconSize": [40, 40]
                                        }
                                    },
                                <?php } ?>
                            ]
                        };

                        // Initialize map
                        const map = new mapboxgl.Map({
                            container: 'map',
                            style: MAPBOX_STYLE,
                            center: [<?= $coords[0]['lng']; ?>, <?= $coords[0]['lat']; ?>],
                            zoom: 15.5
                        });

                        // Set up Popup
                        const popup = new mapboxgl.Popup({
                            closeButton: false,
                            closeOnClick: false
                        });

                        // Get marker image TotalTrans = 0
                        map.loadImage('<?= asset('images/icon/marker-red.png'); ?>', (err, image) => {
                            if (err) console.error(err);
                            map.addImage('marker', image);
                        });

                        // Get marker image isTrans = 0
                        map.loadImage('<?= asset('images/icon/marker-blue.png'); ?>', (err, image3) => {
                            if (err) console.error(err);
                            map.addImage('marker-2', image3);
                        });

                        // Get marker image
                        map.loadImage('<?= asset('images/icon/marker-green.png'); ?>', (err, image2) => {
                            if (err) console.error(err);
                            map.addImage('marker-3', image2);
                        });

                        map.setRenderWorldCopies(false);
                        map.resize();
                        // Add map controls
                        map.addControl(new mapboxgl.NavigationControl({
                            showCompass: false,
                        }));
                        map.addControl(new mapboxgl.AttributionControl({
                            compact: true,
                        }));
                        map.addControl(new mapboxgl.FullscreenControl());

                        function FilterControl() {}
                        FilterControl.prototype.onAdd = function(map) {
                            this._map = map;
                            this._container = document.createElement('div');
                            this._container.classList.add('mapboxgl-ctrl', 'filter-control');
                            const html = '<div>FilterControl</div>';
                            this._container.innerHTML = `
                                <div>
                                <label>
                                    <input type="checkbox" id="filterToggle">
                                    <span class="checkmark"></span>
                                    &nbsp&nbspShow Shop With Other Transaction
                                </label>
                                </div>
                            `;
                            return this._container;
                        }

                        FilterControl.prototype.onRemove = function() {
                            this._container.parentNode.removeChild(this._container);
                            this._map = undefined;
                        }

                        FilterControl.prototype.getDefaultPosition = function() {
                            return 'top-left';
                        }

                        map.addControl(new FilterControl());

                        map.on('load', createMapMarkers);

                        // Add markers to map
                        function createMapMarkers() {
                            // Add source data TotalTrans = 0
                            map.addSource('properties', {
                                type: 'geojson',
                                data: {
                                    type: "FeatureCollection",
                                    features: features.features.filter((property) => property.properties.availability === 'Available')
                                },
                                cluster: true,
                                clusterMaxZoom: 11,
                                clusterRadius: 40,
                            });

                            // Add markers TotalTrans = 0
                            map.addLayer({
                                id: 'property-layer',
                                type: 'symbol',
                                source: 'properties',
                                filter: ['!has', 'point_count'],
                                layout: {
                                    'symbol-placement': 'point',
                                    'icon-image': 'marker',
                                    'icon-size': 1,
                                    'icon-anchor': 'bottom',
                                    'icon-allow-overlap': true,
                                    'text-field': ['get', 'title'],
                                    'text-font': [
                                        'Open Sans Semibold',
                                        'Arial Unicode MS Bold'
                                    ],
                                    'text-offset': [0, 0],
                                    'text-anchor': 'top'
                                }
                            });

                            // Add source data isTrans = 0
                            map.addSource('properties-con2', {
                                type: 'geojson',
                                data: {
                                    type: "FeatureCollection",
                                    features: features.features.filter((property) => property.properties.availability === 'Available-con2')
                                },
                                cluster: true,
                                clusterMaxZoom: 11,
                                clusterRadius: 40,
                            });

                            // Add markers isTrans = 0
                            map.addLayer({
                                id: 'property-layer-con2',
                                type: 'symbol',
                                source: 'properties-con2',
                                filter: ['!has', 'point_count'],
                                layout: {
                                    'symbol-placement': 'point',
                                    'icon-image': 'marker-2',
                                    'icon-size': 1,
                                    'icon-anchor': 'bottom',
                                    'icon-allow-overlap': true,
                                    'text-field': ['get', 'title'],
                                    'text-font': [
                                        'Open Sans Semibold',
                                        'Arial Unicode MS Bold'
                                    ],
                                    'text-offset': [0, 0],
                                    'text-anchor': 'top'
                                }
                            });

                            // Add source data no condition
                            map.addSource('other-properties', {
                                type: 'geojson',
                                data: {
                                    type: "FeatureCollection",
                                    features: features.features.filter((property) => property.properties.availability === 'Available')
                                },
                                cluster: true,
                                clusterMaxZoom: 11,
                                clusterRadius: 40,
                            });

                            // Add markers no condition
                            map.addLayer({
                                id: 'other-property-layer',
                                type: 'symbol',
                                source: 'other-properties',
                                filter: ['!has', 'point_count'],
                                layout: {
                                    'symbol-placement': 'point',
                                    'icon-image': 'marker-3',
                                    'icon-size': 1,
                                    'icon-anchor': 'bottom',
                                    'icon-allow-overlap': true,
                                    'text-field': ['get', 'title'],
                                    'text-font': [
                                        'Open Sans Semibold',
                                        'Arial Unicode MS Bold'
                                    ],
                                    'text-offset': [0, 0],
                                    'text-anchor': 'top'
                                }
                            });

                            // Set up filtering
                            const filterToggle = document.getElementById('filterToggle');
                            map.getSource('properties').setData({
                                type: "FeatureCollection",
                                features: features.features.filter((property) => property.properties.availability === 'Available-con2')
                            });
                            filterToggle.addEventListener('change', function(e) {
                                let properties_data;
                                if (this.checked) {
                                    properties_data = features;
                                } else {
                                    properties_data = {
                                        type: "FeatureCollection",
                                        features: features.features.filter((property) => property.properties.availability === 'Available-con2')
                                    }
                                }
                                map.getSource('properties').setData(properties_data);
                            });
                            // End Filtering

                            // POPUP
                            // Set Popup TotalTrans = 0
                            map.on('mouseenter', 'other-property-layer', (e) => {
                                // Change the cursor style as a UI indicator.
                                map.getCanvas().style.cursor = 'pointer';

                                // Copy coordinates array.
                                const coordinates = e.features[0].geometry.coordinates.slice();
                                const description = e.features[0].properties.description;
                                while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                                    coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                                }
                                popup.setLngLat(coordinates).setHTML(description).addTo(map);
                            });

                            map.on('mouseleave', 'other-property-layer', () => {
                                map.getCanvas().style.cursor = '';
                                popup.remove();
                            });

                            // Set Popup no condition
                            map.on('mouseenter', 'property-layer', (e) => {
                                // Change the cursor style as a UI indicator.
                                map.getCanvas().style.cursor = 'pointer';

                                // Copy coordinates array.
                                const coordinates = e.features[0].geometry.coordinates.slice();
                                const description = e.features[0].properties.description;
                                while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                                    coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                                }
                                popup.setLngLat(coordinates).setHTML(description).addTo(map);
                            });

                            map.on('mouseleave', 'property-layer', () => {
                                map.getCanvas().style.cursor = '';
                                popup.remove();
                            });

                            // Set Popup no condition
                            map.on('mouseenter', 'property-layer-con2', (e) => {
                                // Change the cursor style as a UI indicator.
                                map.getCanvas().style.cursor = 'pointer';

                                // Copy coordinates array.
                                const coordinates = e.features[0].geometry.coordinates.slice();
                                const description = e.features[0].properties.description;
                                while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                                    coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                                }
                                popup.setLngLat(coordinates).setHTML(description).addTo(map);
                            });

                            map.on('mouseleave', 'property-layer-con2', () => {
                                map.getCanvas().style.cursor = '';
                                popup.remove();
                            });
                            // END POPUP

                            // Add clusters
                            map.addLayer({
                                id: 'cluster-circles',
                                type: 'circle',
                                source: 'properties',
                                filter: ['has', 'point_count'],
                                paint: {
                                    'circle-color': '#017025',
                                    'circle-radius': [
                                        'step',
                                        ['get', 'point_count'], 15, 10, 20, 25, 30, 50, 35, 75, 50,
                                    ],
                                    'circle-opacity': 1,
                                    'circle-stroke-width': 1,
                                    'circle-stroke-color': '#fff',
                                    'circle-stroke-opacity': 0.5,
                                }
                            });
                            // Add cluster counter
                            map.addLayer({
                                id: 'cluster-count',
                                type: 'symbol',
                                source: 'properties',
                                filter: ['has', 'point_count'],
                                layout: {
                                    'text-field': '{point_count_abbreviated}',
                                    'text-size': 12,
                                },
                                paint: {
                                    'text-color': '#fff'
                                }
                            });
                        }
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
    #map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 97%;
    }
</style>
@include('template/footer')