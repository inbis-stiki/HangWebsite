<style>
    .img-grey {
        filter: brightness(0) saturate(100%) invert(51%) sepia(0%) saturate(0%) hue-rotate(346deg) brightness(96%) contrast(90%)!important;
        color: #7E7E7E !important;
    }
</style>
<!--**********************************
            Sidebar start
        ***********************************-->
<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li class="active">
                <a href="{{ url('dashboard') }}" class="ai-icon" aria-expanded="false">
                    <div class="{{ request()->is('dashboard') ? 'img-grey' : '' }}">
                        <img src="{{ asset('images/icon/dashboard.svg') }}" alt="">&nbsp;
                        <span class="nav-text text-primary">Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="active">
                <a href="{{ url('transaction') }}" class="ai-icon" aria-expanded="false">
                    <div class="{{ request()->is('transaction') ? 'img-grey' : '' }}">
                        <img src="{{ asset('images/icon/transaksi.svg') }}" alt="">&nbsp;
                        <span class="nav-text mt-1 text-primary">Transaksi</span>
                    </div>
                </a>
            </li>
            <li class="active">
                <a href="{{ url('faktur') }}" class="ai-icon" aria-expanded="false">
                    <div class="{{ request()->is('faktur') ? 'img-grey' : '' }}">
                        <img  src="{{ asset('images/icon/faktur.svg') }}" alt="">&nbsp;
                        <span class="nav-text text-primary">Faktur</span>
                    </div>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void()">
                    <img src="{{ asset('images/icon/laporan.svg') }}" alt="">&nbsp;
                    <span class="nav-text text-primary">Laporan</span>
                </a>
                <ul class="" aria-expanded="false">
                    <li><a href="{{ url('laporan/lpr-ranking') }}">Ranking</a></li>
                    <li><a href="{{ url('laporan/lpr-trend') }}">Trend</a></li>
                    <li><a href="{{ url('laporan/lpr-transaction') }}">Transaksi Harian</a></li>
                </ul>
            </li>
            <li class="active">
                <a href="{{ url('presence') }}" class="ai-icon" aria-expanded="false">
                    <div class="{{ request()->is('presence') ? 'img-grey' : '' }}">
                        <img src="{{ asset('images/icon/presensi.svg') }}" alt="">&nbsp;
                        <span class="nav-text text-primary">Presensi</span>
                    </div>
                </a>
            </li>
            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <img src="{{ asset('images/icon/master.svg') }}" alt="">&nbsp;
                    <span class="nav-text text-primary">Master</span>
                </a>
                <ul aria-expanded="false">
                    @if (Session::get('role') == 1 || Session::get('role') == 2)
                        <li><a href="{{ url('master/activity-category') }}">Kategori Aktivitas</a></li>
                        <li><a href="{{ url('master/category-product') }}">Kategori Produk</a></li>
                        <li><a href="{{ url('master/product') }}">Produk</a></li>
                    @endif

                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Lokasi</a>
                        <ul aria-expanded="false">
                            @if (Session::get('role') == 1 || Session::get('role') == 2)
                                <li style="margin-left: 25px"><a
                                        href="{{ url('master/location/national') }}">Nasional</a></li>
                            @endif
                            @if (Session::get('role') == 1 || Session::get('role') == 2 || Session::get('role') == 3)
                                <li style="margin-left: 25px"><a
                                        href="{{ url('master/location/regional') }}">Regional</a></li>
                            @endif
                            <li style="margin-left: 25px"><a href="{{ url('master/location/area') }}">Area</a></li>
                            <li style="margin-left: 25px"><a href="{{ url('master/location/district') }}">Kecamatan</a>
                            </li>
                            <li style="margin-left: 25px"><a href="{{ url('master/location/market') }}">Pasar</a>
                            </li>
                        </ul>
                    </li>
                    @if (Session::get('role') == 1 || Session::get('role') == 2 || Session::get('role') == 4)
                        <li><a href="{{ url('master/shop') }}">Toko</a></li>
                    @endif
                    @if (Session::get('role') == 1 || Session::get('role') == 2)
                        <li><a href="{{ url('master/regional-price') }}">Harga Regional</a></li>
                        <li><a href="{{ url('master/target-activity') }}">Target Aktivitas</a></li>
                        <li><a href="{{ url('master/target-sale') }}">Target Penjualan</a></li>
                        <li><a href="#">Konversi</a></li>
                    @endif
                    @if (Session::get('role') < 5)
                        <li><a href="{{ url('master/user') }}">User</a></li>
                    @endif
                    @if (Session::get('role') == 1 || Session::get('role') == 2)
                        <li><a href="{{ url('master/user-target') }}">User Target</a></li>
                    @endif
                    @if (Session::get('role') == 1)
                        <li><a href="{{ url('master/role') }}">Role</a></li>
                    @endif
                </ul>
                {{-- <li class="active"><a href="{{ url('logout') }}" class="ai-icon" aria-expanded="false">
                <i class="fa fa-power-off"></i>
                    <span class="nav-text">Log Out</span>
                </a>
            </li> --}}
        </ul>
    </div>
</div>
<!--**********************************
            Sidebar end
        ***********************************-->