<!-- Nav Item - Tables -->
<li class="nav-item {{ 
    request()->is('user-manager') ? 'active' : '' 
    }}">
    <a class="nav-link" href="{{url('user-manager')}}">
        <i class="fas fa-fw fa-users"></i>
        <span>User Manager</span></a>
</li>

<!-- Divider -->
{{-- <hr class="sidebar-divider"> --}}
<!-- Heading -->
{{-- <div class="sidebar-heading">
    SYNC
</div>

<li class="nav-item {{ 
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