<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-feedermate sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{url('dashboard')}}">
        <div class="sidebar-brand-icon">
            <img src="{{asset('img/court.png')}}" alt="SIMUTAPSPLN" height="40px">
        </div>
        <div class="sidebar-brand-text mx-3">
            SIMUTAPSPLN
            {{-- <sup>2</sup> --}}
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ 
        request()->is('dashboard') ? 'active' : '' 
        }}">
        <a class="nav-link" href="{{url('dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>


    @if((Sentinel::getUser()->inRole('mahasiswa')))
    @include('Admin.sidebar-detail.menu-mhs')
    @endif

    @if((Sentinel::getUser()->inRole('mahasiswa')))
    @else
    <!-- Nav Item - Charts -->
    <li class="nav-item {{ 
            request()->is('user-profile') ? 'active' : '' 
            }}">
        <a class="nav-link" href="{{url('user-profile')}}">
            <i class="fas fa-fw fa-id-badge"></i>
            <span>Profile</span></a>
    </li>
    @endif
    <hr class="sidebar-divider">

    {{-- <li class="nav-item {{ 
        request()->is('permohonan-mutasi') ? 'active' : '' 
        }}">
        <a class="nav-link" href="{{url('permohonan-mutasi')}}">
            <i class="fas fa-fw fa-users"></i>
            <span>Permohonan Mutasi</span></a>
    </li>

    <li class="nav-item {{ 
        request()->is('riwayat-permohonan') ? 'active' : '' 
        }}">
        <a class="nav-link" href="{{url('riwayat-permohonan')}}">
            <i class="fas fa-fw fa-users"></i>
            <span>Riwayat Permohonan</span></a>
    </li> --}}
    <li class="nav-item {{ request()->is('permohonan-mutasi*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePermohonanMutasi"
            aria-expanded="true" aria-controls="collapsePermohonanMutasi">
            <i class="fas fa-fw fa-database"></i>
            <span>Permohonan Mutasi</span>
        </a>
        <div id="collapsePermohonanMutasi" class="collapse {{ request()->is('permohonan-mutasi*') ? 'show' : '' }}"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('permohonan-mutasi/permohonan') ? 'active' : '' }}" href="{{ url('permohonan-mutasi/permohonan') }}">
                    Permohonan
                </a>
                <a class="collapse-item {{ request()->is('permohonan-mutasi/riwayat') ? 'active' : '' }}" href="{{ url('permohonan-mutasi/riwayat') }}">
                    Riwayat
                </a>
            </div>
        </div>
    </li>
    {{-- @if((Sentinel::getUser()->inRole('prodi-admin')))
    @include('Admin.sidebar-detail.menu-prodi')
    @endif

    @if((Sentinel::getUser()->inRole('wakil-rektor-akademik')))
    @include('Admin.sidebar-detail.menu-wr1')
    @endif --}}
    
    @if((Sentinel::getUser()->inRole('peserta')))
    @include('Admin.sidebar-detail.menu-peserta')
    @endif

    @if((Sentinel::getUser()->inRole('super-admin')))
    @include('Admin.sidebar-detail.menu-sadmin')
    @endif 


    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->