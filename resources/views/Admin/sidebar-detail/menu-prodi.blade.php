<?php
    $req = request();
    $req = explode("/",$req);
    ?>
<li class="nav-item {{ 
    request()->is('lpro-daftar-mahasiswa') ? 'active' : ($req[1]=='lpro-daftar-mahasiswa' ? 'active' : '') 
    }}">
    <a class="nav-link" href="{{url('lpro-daftar-mahasiswa')}}">
        <i class="fas fa-fw fa-users"></i>
        <span>Daftar Mahasiswa</span></a>
</li>


<li class="nav-item {{ 
    request()->is('lpro-daftar-dosen') ? 'active' : 
    (request()->is('lpro-penugasan-dosen') ? 'active' : '')
    }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDosen" aria-expanded="false"
        aria-controls="collapseDosen">
        <i class="fas fa-fw fa-folder"></i>
        <span>Dosen</span>
    </a>
    <div id="collapseDosen" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar" style="">
        <div class="bg-white py-2 collapse-inner rounded">
            {{-- <h6 class="collapse-header">Persiapan:</h6> --}}
            <a class="collapse-item" href="{{url('lpro-list-dosen')}}">
                <i class="fas fa-fw fa-users"></i>
                <span>Daftar Dosen</span></a>
            <a class="collapse-item" {{-- href="{{url('lpro-penugasan-dosen')}}" --}} href="#">
                <i class="fas fa-fw fa-envelope"></i>
                <span>Penugasan Dosen</span></a>
        </div>
    </div>
</li>


<li class="nav-item {{ 
    request()->is('validasi-rencana-studi') ? 'active' : 
    (request()->is('lpro-kelas-perkuliahan') ? 'active' :  
    (request()->is('lpro-nilai-perkuliahan') ? 'active' : 
    (request()->is('lpro-mhs-lulus-do') ? 'active' : 
    (request()->is('lpro-lulusan') ? 'active' : 
    (request()->is('lpro-recheck-akm') ? 'active' : '')))))        
    }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePerkuliahan"
        aria-expanded="false" aria-controls="collapsePerkuliahan">
        <i class="fas fa-fw fa-folder"></i>
        <span>Perkuliahan</span>
    </a>
    <div id="collapsePerkuliahan" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar"
        style="">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Persiapan:</h6>
            <a class="collapse-item" href="{{url('validasi-rencana-studi')}}">
                <i class="fas fa-fw fa-bookmark"></i>
                <span>Validasi KRS</span></a>
            <div class="collapse-divider"></div>
            <h6 class="collapse-header">Pelaksanaan:</h6>
            <a class="collapse-item" href="{{url('lpro-kelas-perkuliahan')}}">
                <i class="fas fa-fw fa-bookmark"></i>
                <span>Kelas Perkuliahan</span></a>
            <div class="collapse-divider"></div>
            <h6 class="collapse-header">Evaluasi:</h6>
            <a class="collapse-item" href="{{url('lpro-nilai-perkuliahan')}}">
                <i class="fas fa-fw fa-bookmark"></i>
                <span>Nilai Perkuliahan</span>
            </a>
            <div class="collapse-divider"></div>
            <h6 class="collapse-header">Monitoring:</h6>
            <a class="collapse-item" href="{{url('lpro-recheck-akm')}}">
                <i class="fas fa-fw fa-bookmark"></i>
                <span>Cek AKM</span></a>
            <a class="collapse-item" href="{{url('lpro-mhs-lulus-do')}}">
                <i class="fas fa-fw fa-bookmark"></i>
                <span>Mahasiswa Lulus / DO</span></a>
            <a class="collapse-item" href="{{url('lpro-lulusan')}}">
                <i class="fas fa-fw fa-bookmark "></i>
                <span>Lulusan</span></a>
        </div>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLiveMonitor"
        aria-expanded="false" aria-controls="collapseLiveMonitor">
        <i class="fas fa-fw fa-folder"></i>
        <span>Live Monitor</span>
    </a>
    <div id="collapseLiveMonitor" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar"
        style="">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Perkuliahan:</h6>
            <a class="collapse-item" href="#">
                <span>Presensi</span></a>
            <div class="collapse-divider"></div>
        </div>
    </div>
</li>



{{-- <li class="nav-item {{ 
    request()->is('validasi-rencana-studi') ? 'active' : '' 
    }}">
    <a class="nav-link" href="{{url('validasi-rencana-studi')}}">
        <i class="fas fa-fw fa-gavel"></i>
        <span>Validasi KRS</span></a>
</li> --}}
{{-- <li class="nav-item {{ 
    request()->is('recheck-akm') ? 'active' : '' 
    }}">
    <a class="nav-link" href="{{url('recheck-akm')}}">
        <i class="fas fa-fw fa-retweet"></i>
        <span>Cek AKM</span></a>
</li> --}}
{{-- <li class="nav-item {{ 
    request()->is('aktifitas-kuliah-mahasiswa') ? 'active' : '' 
    }}">
    <a class="nav-link" href="{{url('aktifitas-kuliah-mahasiswa')}}">
        <i class="fas fa-fw fa-handshake"></i>
        <span>Review for AKM</span></a>
</li> --}}

<!-- Divider -->
<hr class="sidebar-divider">