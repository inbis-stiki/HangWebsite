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
            <div class="alert alert-success">{{ session('succ_msg') }}</div>
        @endif
        @if (session('err_msg'))
            <div class="alert alert-danger">{{ session('err_msg') }}</div>
        @endif

        <!-- Add Order -->
        <div class="row">
            <!-- Column starts -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-block">
                        <h4 class="card-title mb-3">Laporan Toko</h4>
                        <div class="form-group">
                            <label for="">Pilih Area</label>
                            <select name="" id="slctArea" class="form-control">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->ID_LOCATION }}" {{ !empty($idLoc) && $idLoc == $location->ID_LOCATION ? "selected" : ""}}>{{ $location->NAME_LOCATION }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            @php
                                $totToko = 0;
                            @endphp
                            @foreach ($shopTypeCounts as $shopTypeCount)
                                <div class="col text-center">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h1 class="text-primary">{{ number_format($shopTypeCount->TOTAL) }}</h1>
                                            <small class="text-default">{{ $shopTypeCount->TYPE_SHOP }}</small>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $totToko += $shopTypeCount->TOTAL;
                                @endphp
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col text-center">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h1 class="text-primary">{{ number_format($totToko) }}</h1>
                                        <small class="text-default">Total Toko</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ url("laporan/lpr-shop/download") }}" method="POST">
                            @csrf
                            <input type="hidden" name="idLocation" value="{{ !empty($idLoc) ? $idLoc : $locations[0]->ID_LOCATION }}">
                            <button type="submit" class="btn btn-primary btn-sm w-100 mb-3">Download Laporan</button>
                        </form>
                        <div class="row">
                            <div class="col-lg-12 overflow-hidden">
                                <div id='map'></div>
                                <canvas id="canvasID" height="480">Canvas not supported</canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--**********************************
    Content body end
***********************************-->
<?php
    $centerCord = get_center($coords);

    function get_center($coords)
    {
        $count_coords = count($coords);
        $xcos = 0.0;
        $ycos = 0.0;
        $zsin = 0.0;

        foreach ($coords as $lnglat) {
            $lat = $lnglat->LAT_SHOP * pi() / 180;
            $lon = $lnglat->LONG_SHOP * pi() / 180;

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
                        'coordinates': [<?= $coords[$i]->LONG_SHOP; ?>, <?= $coords[$i]->LAT_SHOP; ?>]
                    },
                    'properties': {
                        "title": "<?= $coords[$i]->NAME_SHOP; ?>",
                        "description": "<?= $coords[$i]->NAME_SHOP; ?>",
                        "availability": 'Available',
                        "iconSize": [40, 40]
                    }
                },
            <?php } ?>
        ]
    };

    // Initialize map
    let map = new mapboxgl.Map({
        container: 'map',
        style: MAPBOX_STYLE,
        center: [<?= $centerCord[1] ?>, <?= $centerCord[0] ?>],
        zoom: 5
    });

    // Set up Popup
    let popup = new mapboxgl.Popup({
        closeButton: false,
        closeOnClick: false
    });

    // Get marker image
    map.loadImage('<?= asset('images/icon/marker-green.png'); ?>', (err, image) => {
        if (err) console.error(err);
        map.addImage('marker', image);
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
<style>
    #map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 97%;
        height: 400px;
    }
</style>
@include('template/footer')
<script>
    $('#slctArea').change(function(){
        const val = $(this).val();
        window.location.href = "{{ url('laporan/lpr-shop') }}/"+val;
    })
</script>