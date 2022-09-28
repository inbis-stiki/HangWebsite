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
                            <div class="col-lg-12 overflow-hidden">
                                <div id='map'></div>
                                <canvas id="canvasID" height="480">Canvas not supported</canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="accordion-two" class="accordion accordion-primary-solid">
                            <?php
                            $no = 0;
                            $coords = array();
                            $other_coords = array();
                            foreach ($transaction as $data_ublp) :
                            ?>
                                <div class="accordion__item">
                                    <div class="accordion__header collapsed" data-toggle="collapse" data-target="#bordered_collapse<?= $no; ?>" aria-expanded="false">
                                        <span class="accordion__header--text"><?= $data_ublp['LOCATION']; ?></span>
                                        <span class="accordion__header--text float-right mr-4"><?= date_format(date_create($data_ublp['DATE_TRANS']), 'H:i'); ?></span>
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
                                                    <p class="fs-18 float-right"><?= date_format(date_create($data_ublp['DATE_TRANS']), 'j F Y H:i'); ?></p>
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
                                                    <?php if (!empty($data_ublp['IMAGE'])) { ?>
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
                                array_push(
                                    $coords,
                                    array('loc' => $data_ublp['LOCATION'], 'lat' => $data_ublp['LAT_TRANS'], 'lng' => $data_ublp['LONG_TRANS'], 'total' => $data_ublp['TOTAL'])
                                );

                                foreach ($shop_no_trans as $other_shop) {
                                    if ($other_shop->ID_SHOP <> $data_ublp['ID_SHOP']) {
                                        array_push(
                                            $other_coords,
                                            array('loc' => $other_shop->NAME_SHOP, 'lat' => $other_shop->LAT_SHOP, 'lng' => $other_shop->LONG_SHOP)
                                        );
                                    }
                                }
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
                    <style>
                        #map {
                            width: 100%;
                            height: 480px;
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
                            background-color: #f26f21;
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
                                            "description": "<strong><?= $other_coords[$i]['loc']; ?></strong>",
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
                            center: [<?= $centerCord[1] ?>, <?= $centerCord[0] ?>],
                            zoom: 15.5
                        });

                        // Set up Popup
                        const popup = new mapboxgl.Popup({
                            closeButton: false,
                            closeOnClick: false
                        });

                        // Get marker image
                        map.loadImage('<?= asset('images/icon/map-marker-red.png'); ?>', (err, image) => {
                            if (err) console.error(err);
                            map.addImage('marker', image);
                        });

                        // Get marker image-2
                        map.loadImage('<?= asset('images/icon/map-marker-green.png'); ?>', (err, image2) => {
                            if (err) console.error(err);
                            map.addImage('marker-2', image2);
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
                                    &nbsp&nbspShow All Shop
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
                            // Add source data
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

                            // Add markers
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

                            // Add source data
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

                            // Add markers
                            map.addLayer({
                                id: 'other-property-layer',
                                type: 'symbol',
                                source: 'other-properties',
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

                            // Add clusters
                            map.addLayer({
                                id: 'cluster-circles',
                                type: 'circle',
                                source: 'properties',
                                filter: ['has', 'point_count'],
                                paint: {
                                    'circle-color': '#ffcd38',
                                    'circle-radius': [
                                        'step',
                                        ['get', 'point_count'], 15, 10, 20, 25, 30, 50, 35, 75, 50,
                                    ],
                                    'circle-opacity': 0.85,
                                    'circle-stroke-width': 3,
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
                                    'text-color': '#111'
                                }
                            });

                            // Set up filtering
                            const filterToggle = document.getElementById('filterToggle');
                            filterToggle.addEventListener('change', function(e) {
                                let properties;
                                if (this.checked) {
                                    properties = features;
                                } else {
                                    properties = {
                                        type: "FeatureCollection",
                                        features: features.features.filter((property) => property.properties.availability === 'Available')
                                    }
                                }
                                map.getSource('properties').setData(properties);
                            });

                            // Set Popup
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

                            // Set Popup 2
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