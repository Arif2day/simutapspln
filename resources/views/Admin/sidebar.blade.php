<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-feedermate sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{url('dashboard')}}">
        <div class="sidebar-brand-icon">
            <img src="{{asset('img/court.png')}}" alt="sipp" height="40px">
        </div>
        <div class="sidebar-brand-text mx-3">
            SIPP
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

    {{-- @if((Sentinel::getUser()->inRole('prodi-admin')))
    @include('Admin.sidebar-detail.menu-prodi')
    @endif

    @if((Sentinel::getUser()->inRole('wakil-rektor-akademik')))
    @include('Admin.sidebar-detail.menu-wr1')
    @endif

    @if((Sentinel::getUser()->inRole('bau-admin')))
    @include('Admin.sidebar-detail.menu-bau')
    @endif --}}

    @if((Sentinel::getUser()->inRole('super-admin')))
    @include('Admin.sidebar-detail.menu-sadmin')
    @endif 


    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->