@extends('Admin.main')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Unit</div>
                            <div class="h1 mb-0 font-weight-bold text-gray-800">
                                {{ $units }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-4x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pegawai Mendekati 56th</div>
                            <div class="h1 mb-0 font-weight-bold text-gray-800">
                                {{ $own_unit_count[0]->close_56 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-3x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="text-xs font-weight-bold text-success mt-3">
                            {{ $own_unit->latestPlacement->getUnit->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                KETERPENUHAN ALOKASI
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h7 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ $own_unit_count[0]->total_pegawai_aktif }}
                                        of
                                        {{ $own_unit_count[0]->total_allocation }}
                                        ({{ $own_unit_count[0]->persentase_ftk }}%)
                                    </div>
                                </div>                                                              
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-landmark fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="col mt-3">
                        <div class="progress progress-sm mr-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width:{{$own_unit_count[0]->persentase_ftk}}%"
                                aria-valuenow="{{ $own_unit_count[0]->persentase_ftk }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="text-xs font-weight-bold text-info mt-3">
                            {{ $own_unit->latestPlacement->getUnit->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                AJUAN PERLU DIREVIEW</div>
                            <div class="h5 mt-3 mb-0 font-weight-bold text-gray-800 ">
                                {{ $need_reviews }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="mt-3 fas fa-pen fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="text-center mr-2">
                    @if(!Sentinel::getUser()->inRole('peserta'))
                        <a href="{{ url('permohonan-mutasi/riwayat?is_reviewed=0') }}" class="btn btn-sm btn-primary">Selengkapnya</a>                    
                    @else
                        <a href="{{ url('permohonan-mutasi/riwayat') }}" class="btn btn-sm btn-primary">Selengkapnya</a>                    
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">            
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h4 class="center mb-0 text-primary">Grafik Formasi Tenaga Kerja Unit</h4>
                        <a href="{{ url('ftk/ftk') }}" class="btn btn-sm btn-primary">Selengkapnya</a>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <canvas id="ftkChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">       
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">     
                    <img src="{{ asset('uploads/alur.jpeg') }}" width="100%" height="auto">
                </div>
            </div>
        </div>
    </div>

    {{-- @if(Sentinel::getUser()->inRole('super-admin')||Sentinel::getUser()->inRole('wakil-rektor-akademik'))
    @include('Admin.SUPER.dashboard-detail.super-dash')
    @endif --}}

    @if((Sentinel::getUser()->inRole('prodi-admin')))
    {{-- @include('Admin.PRODI.dashboard-detail.prodi-dash') --}}
    @endif

    @if((Sentinel::getUser()->inRole('mahasiswa')))
    {{-- @include('Admin.MAHASISWA.dashboard-detail.mahasiswa-dash') --}}
    @endif

    @if((Sentinel::getUser()->inRole('bau-admin')))
    {{-- @include('Admin.BAU.dashboard-detail.bau-admin-dash') --}}
    @endif


    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        {{-- <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik opo menurutmu</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myBarChart"></canvas>

                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Pie Chart -->
        {{-- <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik opo menurutmu 2</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-male text-primary"></i> Male
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-female text-success"></i> Female
                        </span>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

</div>
<!-- /.container-fluid -->
@endsection
@section('script')
<script>
    const chartData = @json($ftkPerUnit); // kirim dari controller

    const labels = chartData.map(item => item.unit_name);
    const pegawaiAktif = chartData.map(item => parseInt(item.total_pegawai_aktif));
    const close56 = chartData.map(item => parseInt(item.close_56));
    const totalAllocation = chartData.map(item => parseInt(item.total_allocation));
    const persentaseFtk = chartData.map(item => parseFloat(item.persentase_ftk));
    const persentaseFtk56 = chartData.map(item => parseFloat(item.persentase_ftk_with_56));

    const ctx = document.getElementById('ftkChart').getContext('2d');
    const ftkChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pegawai Aktif',
                    data: pegawaiAktif,
                    backgroundColor: '#4e73df',
                },
                {
                    label: 'Pegawai Mendekati 56th',
                    data: close56,
                    backgroundColor: '#f6c23e',
                },
                {
                    label: 'Alokasi Pegawai',
                    data: totalAllocation,
                    backgroundColor: '#1cc88a',
                },
                // {
                //     label: 'Persentase FTK - 56th (%)',
                //     data: persentaseFtk,
                //     type: 'line',
                //     borderColor: '#e74a3b',
                //     backgroundColor: 'transparent',
                //     yAxisID: 'y1',
                // },
                {
                    label: 'Persentase FTK (%)',
                    data: persentaseFtk56,
                    type: 'line',
                    borderColor: '#36b9cc',
                    backgroundColor: 'transparent',
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Persentase (%)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>
@endsection