<!--**********************************
            Sidebar start
        ***********************************-->
<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li><a href="{{ url('dashboard') }}" class="ai-icon" aria-expanded="false">
                    <i class="fa fa-gauge"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-list-check"></i>
                    <span class="nav-text">Master</span>
                </a>
                <ul aria-expanded="false">
                    @if (Session::get('role') == 1 || Session::get('role') == 2)
                    <li><a href="{{ url('master/activity-category') }}">Kategori Aktivitas</a></li>
                    <li><a href="{{ url('master/category-product') }}">Kategori Produk</a></li>
                    <li><a href="{{ url('master/product') }}">Produk</a></li>
                    @endif
                    
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Lokasi</a>
                        <ul aria-expanded="false">
                            @if (Session::get('role') == 1 || Session::get('role') == 2 )
                            <li style="margin-left: 25px"><a href="{{ url('master/location/national') }}">Nasional</a></li>
                            @endif
                            @if (Session::get('role') == 1 || Session::get('role') == 2 || Session::get('role') == 3)
                            <li style="margin-left: 25px"><a href="{{ url('master/location/regional') }}">Regional</a></li>
                            @endif
                            <li style="margin-left: 25px"><a href="{{ url('master/location/area') }}">Area</a></li>
                            <li style="margin-left: 25px"><a href="{{ url('master/location/district') }}">Kecamatan</a></li>
                            <li style="margin-left: 25px"><a href="{{ url('master/location/market') }}">Pasar</a></li>
                        </ul>
                    </li>
                    @if (Session::get('role') == 1 || Session::get('role') == 2 || Session::get('role') == 4)
                    <li><a href="{{ url('master/shop') }}">Toko</a></li>
                    @endif
                    @if (Session::get('role') == 1 || Session::get('role') == 2)
                    <li><a href="{{ url('master/regional-price') }}">Harga Regional</a></li>
                    <li><a href="#">Target Regional</a></li>
                    <li><a href="#">Konversi</a></li>
                    @endif
                    @if (Session::get('role') < 5)
                    <li><a href="{{ url('master/user') }}">User</a></li>
                    @endif
                    @if (Session::get('role') == 1)
                    <li><a href="{{ url('master/role') }}">Role</a></li>
                    @endif
                </ul>
            <li class="active">
                <a href="{{ url('transaction') }}" class="ai-icon" aria-expanded="false">
                    <i class="fa fa-shuffle"></i>
                    <span class="nav-text">Transaksi</span>
                </a>
            </li>
            <li class="active"><a href="{{ url('presence') }}" class="ai-icon" aria-expanded="false">
                    <i class="fa fa-user-check"></i>
                    <span class="nav-text">Presensi</span>
                </a>
            </li>
            <li class="active"><a href="#" class="ai-icon" aria-expanded="false">
                    <i class="fa fa-newspaper"></i>
                    <span class="nav-text">Laporan</span>
                </a>
            </li>
            <li class="active"><a href="{{ url('logout') }}" class="ai-icon" aria-expanded="false">
                <i class="fa fa-power-off"></i>
                    <span class="nav-text">Log Out</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!--**********************************
            Sidebar end
        ***********************************-->