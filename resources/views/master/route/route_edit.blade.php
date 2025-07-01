@extends('template.header')
@extends('template.sidebar')

<style>
    .custom-content-body {
        min-height: 0px !important;
        height: 120px !important;
    }
</style>

<div class="content-body custom-content-body">
    <div class="container-fluid">

        @if ($errors->any())
        <div class="alert alert-danger" style="margin-top: 1rem;">{{ $errors->first() }}</div>
        @endif

        <form id="form-update-rute" action="{{ url('master/rute/update') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-xl-6 col-xxl-6 col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <h4 class="card-title">Timeline</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="ID_USER">User Saat Ini</label>
                                <!-- $user_route->NAME_USER -->
                                <h6 class="text-bold"><?= $user_route->NAME_USER ?></h6>
                                <input type="hidden" name="ID_USER" class="form-control" value="<?= $user_route->ID_USER ?>">
                            </div>
                            <div class="form-group">
                                <label for="ID_USER">Pilih User Baru</label>
                                <select name="ID_USER_NEW" id="ID_USER_NEW" class="form-control">
                                    <option value="">-- Pilih User --</option>
                                    @foreach($user_data as $user)
                                    @if($user_route->ID_USER != $user->ID_USER)
                                    <option value="{{ $user->ID_USER }}" data-nama-user="{{ $user->NAME_USER }}">{{ $user->NAME_USER }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <small class="text-danger">* Jika tidak ingin mengganti dengan user lain, tidak perlu merubah pilihan ini.</small>
                            </div>
                            <div class="form-group">
                                <label for="WEEK">Minggu</label>
                                <input type="number" name="WEEK" id="WEEK" class="form-control" value="<?= $user_route->WEEK ?>" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="ROUTE_GROUP">Rute Grup</label>
                                <input type="number" name="ROUTE_GROUP" id="ROUTE_GROUP" class="form-control" value="<?= $user_route->ROUTE_GROUP ?>" required readonly>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="submitForm()">Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-xxl-6 col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <h4 class="card-title">Rute Toko</h4>
                        </div>
                        <div class="card-body p-0">
                            <div id="DZ_W_TimeLine11" class="widget-timeline dz-scroll my-4 px-4 style-1" style="height: 620px;">
                                <ul class="timeline">
                                    <?php if (!empty($user_route)) { ?>
                                        <?php
                                        $pastelColors = ["#FE634E", "#2421DA", "#2BC155", "#FF6D4D"];
                                        $styles = ''; // Initialize the styles string
                                        ?>
                                        <?php foreach (explode(';', $user_route->ID_SHOP) as $key => $ID_SHOP) { ?>
                                            <?php
                                            $namaToko = explode(';', $user_route->NAME_SHOP)[$key];
                                            $namaArea = explode(';', $user_route->NAME_AREA)[$key];
                                            $idToko = $ID_SHOP;
                                            $selectedColor = $pastelColors[array_rand($pastelColors)];

                                            // Append the dynamic styles to the $styles variable
                                            $styles .= "
                                                    #DZ_W_TimeLine11 .timeline-badge-{$key} + .timeline-panel {
                                                        border-color: {$selectedColor} !important;
                                                    }

                                                    #DZ_W_TimeLine11 .timeline-badge-{$key}:after {
                                                        background-color: {$selectedColor} !important;
                                                        box-shadow: 0 5px 10px 0 rgba(255, 109, 77, 0.2);
                                                    }

                                                    #DZ_W_TimeLine11 .timeline-badge-{$key} + .timeline-panel:after {
                                                        background: {$selectedColor} !important;
                                                    }
                                                ";
                                            ?>
                                            <li>
                                                <div class="timeline-badge dark timeline-badge-<?= $key ?>">
                                                </div>
                                                <a class="timeline-panel text-muted" href="#">
                                                    <h6 class="mb-0"><?= $namaToko ?></h6>
                                                    <span><?= $namaArea ?></span>
                                                </a>
                                                <input type="hidden" name="ID_SHOP[]" value="<?= $ID_SHOP ?>">
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>

                            <!-- Add the generated styles outside the loop -->
                            <style>
                                <?= $styles ?>
                            </style>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function submitForm() {
        var IDUserSelected = $('select[name=ID_USER_NEW]').val()
        var namaSelected = $('select[name=ID_USER_NEW] option:selected').data('nama-user')
        if (IDUserSelected) {
            Swal.fire({
                title: 'Apakah kamu yakin ingin mengubah data user ?',
                text: `User akan berganti menjadi '${namaSelected}' !`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                customClass: {
                    actions: 'd-flex flex-row-reverse',
                    confirmButton: 'btn btn-primary ml-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-update-rute').submit()
                }
            })
        } else {
            Toast.fire({
                icon: "error",
                title: "Jika ingin melakukan simpan harap untuk mengganti user!"
            });
        }
    }
</script>

@extends('template.footer')