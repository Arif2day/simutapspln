<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-warning" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter text-sm" id="counterNotif">
                    @if($unreadNotifCount > 0)
                        <span class="badge badge-danger">{{ $unreadNotifCount }}</span>
                    @endif
                </span>
                {{-- <input type="hidden" id="linkednotif" name="linkednotif" value="{{url('getSync')}}"> --}}
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Notifikasi Baru
                </h6>
                @foreach ($unread as $item)                    
                    <div id="permohonan">
                        <a class="dropdown-item d-flex align-items-center" href="{{url($item->data['url']).'?notification_id=' . $item->id}}">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-hands text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">Permohonan Mutasi APS</div>
                                <span id="span-diskusi">{{ $item->data['message'] }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
                {{-- <div id="jawaban">
                    <a class="dropdown-item d-flex align-items-center" href="{{url('answer')}}">
                        <div class="mr-3">
                            <div class="icon-circle bg-success">
                                <i class="fas fa-user-edit text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">Monitor Hasil Ujian</div>
                            <span id="span-jawaban">0 submit baru</span>
                        </div>
                    </a>
                </div> --}}

                {{-- <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a> --}}
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?php
                    $user = Sentinel::check();
                    ?>
                    {{$user->name}}
                    @if(Sentinel::check())
                    @else
                    Douglas McGee
                    @endif
                </span>
                <img class="img-profile rounded-circle"
                    src="{{Sentinel::check()->profile_pic==null?asset('img/undraw_profile.svg'):asset(Sentinel::check()->profile_pic)}}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

                @if((Sentinel::getUser()->inRole('mahasiswa')))
                <a class="dropdown-item" href="{{url('profil-mhs/detail')}}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil
                </a>
                @else
                <a class="dropdown-item" href="{{url('user-profile')}}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil
                </a>
                @endif
                {{-- <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a> --}}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->