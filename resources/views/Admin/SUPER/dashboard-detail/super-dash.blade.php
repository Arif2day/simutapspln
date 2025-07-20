{{-- <div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Mahasiswa (AKTIF)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$mhs_aktif}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Progress Pembayaran
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$progress_lunas}}%</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: {{$progress_lunas}}%" aria-valuenow="{{$progress_lunas}}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Lunas Tagihan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{"Rp
                            ".number_format($total_lunas,2,',','.')}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Belum Lunas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{"Rp ".number_format($total_belum,2,',','.')}}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}













<div class="row">
    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Progres Permohonan Mutasi APS</h6>
                <select name="periode" id="periode">
                    @foreach ($periode as $item)
                    <option value="{{$item->id_semester}}">{{$item->nama_semester}}</option>
                    @endforeach
                </select>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                {{-- @foreach ($progress_prodi as $item)
                <h4 class="small font-weight-bold">{{$item['nama_prodi']}} <span
                        class="float-right">{{$item['of_lunas']}} of
                        {{$item['of_all']}}
                        - {{$item['lunas']}}%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar {{$item['bg']}}" role="progressbar" style="width: {{$item['lunas']}}%"
                        aria-valuenow="{{$item['lunas']}}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                @endforeach --}}
                <canvas id="myBarChart" style="display: block; width: 385px; height: 208px;"
                    class="chartjs-render-monitor" width="385" height="208"></canvas>
                <input type="hidden" name="linkAKMPRODITA" id="linkAKMPRODITA" value="{{url('dashboard/periode')}}">
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Permohonan Mutasi APS Aktif</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-1 pb-1">
                    <canvas id="myPieChart" style="display: block; width: 385px; height: 208px;"
                        class="chartjs-render-monitor" width="385" height="208"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
// (Chart.defaults.global.defaultFontFamily = "Metropolis"),
// '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
// Chart.defaults.global.defaultFontColor = "#858796";

// Pie Chart Example
if (@json(Sentinel::getUser()->inRole('super-admin'))||@json(Sentinel::getUser()->inRole('wakil-rektor-akademik'))) {    
    var ctx = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: @json($nama_prodi),
            datasets: [{
                data: @json($isi_prodi),
                backgroundColor: [
                    "rgba(0,0,0,0.6)",
                    "rgba(0,0,128,0.6)",	
                    "rgba(0,0,255,0.6)",	
                    "rgba(0,128,0,0.6)",	
                    "rgba(0,128,128,0.6)",	
                    "rgba(0,255,0,0.6)",	
                    "rgba(0,255,255,0.6)",	
                    "rgba(128,0,0,0.6)",	
                    "rgba(128,0,128,0.6)",	
                    "rgba(128,128,0,0.6)",	
                    "rgba(128,128,128,0.6)",	
                    "rgba(192,192,192,0.6)",	
                    "rgba(255,0,0,0.6)",	
                    "rgba(255,0,255,0.6)",	
                    "rgba(255,255,0,0.6)"
                ],
                hoverBackgroundColor: [
                    "rgba(0,0,0,0.6)",
                    "rgba(0,0,128,0.6)",	
                    "rgba(0,0,255,0.6)",	
                    "rgba(0,128,0,0.6)",	
                    "rgba(0,128,128,0.6)",	
                    "rgba(0,255,0,0.6)",	
                    "rgba(0,255,255,0.6)",	
                    "rgba(128,0,0,0.6)",	
                    "rgba(128,0,128,0.6)",	
                    "rgba(128,128,0,0.6)",	
                    "rgba(128,128,128,0.6)",	
                    "rgba(192,192,192,0.6)",	
                    "rgba(255,0,0,0.6)",	
                    "rgba(255,0,255,0.6)",	
                    "rgba(255,255,0,0.6)"
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)"
            }]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10
            },
            legend: {
                display: true,
                position:"bottom"
            },
            cutoutPercentage: 70
        }
    });
}

var mbc = document.getElementById("myBarChart");

var data = {
  labels: @json($label_akm_prodi_at_ta),
  datasets: @json($dataset_akm_prodi_at_ta)
};

var myBarChart = new Chart(mbc, {
    type: 'bar',
    data: data,
    options: {
        indexAxis: 'y',
        scales: {
            x: {
            stacked: true,
            // min: 10,
            // max: 50,
            },
            y: {
            stacked: true,
            // min: 10,
            // max: 100,
            },
        },      
        responsive: true,
        legend: {
            display: true,
            position:"top",
        },
    },
});

$(document).ready(function() {
    $periode = $('#periode option:selected').val();
    updateChart($periode);
});

$('#periode').change(function(){
    $periode = $('#periode option:selected').val();
    updateChart($periode);
});

function updateChart(periode) {
    let datar = {};
    datar['_method']='POST';
    datar['_token']=$('._token').data('token');
    datar['periode']=$periode;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'post',
        url: $("#linkAKMPRODITA").val(),
        data:datar,
        success: function(dataq) {
            myBarChart.data.labels = dataq.label_akm_prodi_at_ta;
            myBarChart.data.datasets = dataq.dataset_akm_prodi_at_ta;
            myBarChart.update();
        },
    });
}
</script>
@endsection