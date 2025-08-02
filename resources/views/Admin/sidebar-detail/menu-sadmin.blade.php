<!-- Nav Item - Tables -->


<!-- Divider -->
<hr class="sidebar-divider">
<!-- Heading -->
<div class="sidebar-heading">
    DIVISI TALENTA
</div>

<li class="nav-item {{ request()->is('master*') ? 'active' : '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster"
        aria-expanded="true" aria-controls="collapseMaster">
        <i class="fas fa-fw fa-database"></i>
        <span>Master Data</span>
    </a>
    <div id="collapseMaster" class="collapse {{ request()->is('master*') ? 'show' : '' }}"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item {{ request()->is('master/employee-status') ? 'active' : '' }}" href="{{ url('master/employee-status') }}">
                Employee Status
            </a>
            <a class="collapse-item {{ request()->is('master/positions') ? 'active' : '' }}" href="{{ url('master/positions') }}">
                Positions
            </a>
            <a class="collapse-item {{ request()->is('master/units') ? 'active' : '' }}" href="{{ url('master/units') }}">
                Units
            </a>
            <a class="collapse-item {{ request()->is('master/unit-types') ? 'active' : '' }}" href="{{ url('master/unit-types') }}">
                Unit Types
            </a>
        </div>
    </div>
</li>
<li class="nav-item {{ request()->is('ftk*') ? 'active' : '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFTK"
        aria-expanded="true" aria-controls="collapseFTK">
        <i class="fas fa-fw fa-database"></i>
        <span>FTK Data</span>
    </a>
    <div id="collapseFTK" class="collapse {{ request()->is('ftk*') ? 'show' : '' }}"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">            
            <a class="collapse-item {{ request()->is('ftk/unit-resource-requirements') ? 'active' : '' }}" href="{{ url('ftk/unit-resource-requirements') }}">
                Unit Res. Requirements
            </a>
            <a class="collapse-item {{ request()->is('ftk/ftk') ? 'active' : '' }}" href="{{ url('ftk/ftk') }}">
                FTK
            </a>
        </div>
    </div>
</li>

<li class="nav-item {{ 
    request()->is('user-manager') ? 'active' : '' 
    }}">
    <a class="nav-link" href="{{url('user-manager')}}">
        <i class="fas fa-fw fa-users"></i>
        <span>Employee Manager</span></a>
</li>
{{-- <li class="nav-item {{ request()->is('layanan*') ? 'active' : '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayanan"
        aria-expanded="true" aria-controls="collapseLayanan">
        <i class="fas fa-fw fa-hands"></i>
        <span>Layanan</span>
    </a>
    <div id="collapseLayanan" class="collapse {{ request()->is('layanan*') ? 'show' : '' }}"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item {{ request()->is('layanan/users') ? 'active' : '' }}" href="{{ url('layanan/users') }}">
                Layanan Mutasi
            </a>
            <a class="collapse-item {{ request()->is('layanan/roles') ? 'active' : '' }}" href="{{ url('layanan/roles') }}">
                Layanan -
            </a>
        </div>
    </div>
</li> --}}




{{-- <li class="nav-item {{ 
request()->is('sync') ? 'active' : '' 
}}">
    <a class="nav-link" href="{{url('sync')}}">
        <i class="fas fa-fw fa-sync-alt"></i>
        <span>Syncronize</span></a>
</li> --}}

<!-- Divider -->
{{-- <hr class="sidebar-divider d-none d-md-block">

<li class="nav-item {{ 
    request()->is('sp-aktifitas-kuliah-mahasiswa') ? 'active' : '' 
    }}">
    <a class="nav-link" href="{{url('/sp-aktifitas-kuliah-mahasiswa')}}">
        <i class="fas fa-fw fa-handshake"></i>
        <span>Review for AKM</span></a>
</li>
<li class="nav-item {{ 
    request()->is('json-injector') ? 'active' : '' 
    }}">
    <a class="nav-link" href="{{url('json-injector')}}">
        <i class="fas fa-fw fa-rocket"></i>
        <span>JSON Injector</span></a>
</li>
<li class="nav-item {{ 
    request()->is('monitoring-input-nilai') ? 'active' : '' 
    }}">
    <a class="nav-link" href="{{url('monitoring-input-nilai')}}">
        <i class="fas fa-fw fa-rocket"></i>
        <span>Monin Nilai</span></a>
</li>
<li class="nav-item {{ 
    request()->is('wr1-nilai-perkuliahan') ? 'active' : '' 
    }}">
    <a class="nav-link" href="{{url('wr1-nilai-perkuliahan')}}">
        <i class="fas fa-fw fa-bookmark"></i>
        <span>Nilai Perkuliahan</span>
    </a>
</li>
<li class="nav-item {{ 
    request()->is('thesis') ? 'active' : '' 
    }}">
    <a class="nav-link" href="{{url('thesis')}}">
        <i class="fas fa-fw fa-bookmark"></i>
        <span>Thesis</span>
    </a>
</li> --}}