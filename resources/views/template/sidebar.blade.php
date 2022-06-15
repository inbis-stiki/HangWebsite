<!--**********************************
            Sidebar start
        ***********************************-->
<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li><a href="{{ url('dashboard') }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-home-2"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-menu-2"></i>
                    <span class="nav-text">Master</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url('master/category-product') }}">Kategori Produk</a></li>
                    <li><a href="{{ url('master/product') }}">Produk</a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Lokasi</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('master/location/national') }}">Nasional</a></li>
                            <li><a href="{{ url('master/location/regional') }}">Regional</a></li>
                            <li><a href="{{ url('master/location/area') }}">Area</a></li>
                            <li><a href="{{ url('master/location/district') }}">Kecamatan</a></li>
                            <li><a href="#">Pasar</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ url('master/regional-price') }}">Harga Regional</a></li>
                    <li><a href="#">Target Regional</a></li>
                    <li><a href="#">Konversi</a></li>
                    <li><a href="{{ url('master/user') }}">User</a></li>
                    <li><a href="{{ url('master/role') }}">Role</a></li>
                </ul>
            <li class="active"><a href="widget-basic.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-newspaper"></i>
                    <span class="nav-text">Laporan</span>
                </a>
            </li>
            </li>
        </ul>
    </div>
</div>
<!--**********************************
            Sidebar end
        ***********************************-->