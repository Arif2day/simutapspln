<!-- Sticky Menu -->
<nav id="sticky-menu" class="bg-gradient-custom text-white px-4 py-2 sticky-top shadow-sm">
    <ul class="nav">
        <li class="nav-item"><a class="nav-link text-white" href="#">Beranda</a></li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="surveiDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Layanan
            </a>
            <div class="dropdown-menu" aria-labelledby="surveiDropdown">
                <a class="dropdown-item" href="{{ url('/login') }}">Pendaftaran Mutasi APS</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="surveiDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Berita
            </a>
            <div class="dropdown-menu" aria-labelledby="surveiDropdown">
                <a class="dropdown-item" href="#">Statistik Mutasi</a>
            </div>
        </li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Hubungi Kami</a></li>        
    </ul>
</nav>