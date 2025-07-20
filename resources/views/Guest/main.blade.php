<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIMUTAPSPLN</title>

    <!-- Custom fonts for this template-->
    {!!Html::style(secure_asset('vendor/fontawesome-free/css/all.min.css'))!!}

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    {!!Html::style(secure_asset('css/sb-admin-2.min.css'))!!}
    <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.ico') }}">

    {!!Html::style(secure_asset('css/magnifier.css'))!!}
    {!!Html::style(secure_asset('css/loader.css'))!!}
    {!!Html::style(secure_asset('css/datepicker3.css'))!!}
    {!!Html::style(secure_asset('css/addition.css'))!!}
    {{-- {!!Html::style(secure_asset('css/styles.css'))!!} --}}
</head>
<style>
    .bg-gradient-custom {
        background-color: #56068f;
        background-image: linear-gradient(346deg, #50028b 15%, #03266a 90%);
        background-size: cover;
    }

    .btn-custom,
    .btn-custom:hover {
        color: #fff;
        background-color: #50028b;
        border-color: #1c002b;
    }
    /* add */
    #sticky-menu {
    z-index: 1030; /* agar di atas tabel */
    }

    #main-header {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    }

    #main-header img {
        max-height: 110px;
        object-fit: contain;
    }

    @media (max-width: 768px) {
        #main-header {
            text-align: center;
        }
    }

    .hide-header {
        top: -100px;
        position: relative;
    }
    /* table-stripped-color */
    /* Striping untuk baris ganjil */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f3e9ff; /* Ungu muda */
    }

    /* Jika pakai DataTables */
    table.dataTable.stripe tbody tr.odd {
        background-color: #f3e9ff !important;
    }

    /* Hover effect */
    .table-hover tbody tr:hover {
        background-color: #e5d4f9 !important; /* Ungu hover */
    }

    /* Header */
    .table thead th {
        background-color: #5e1a94;
        color: #fff;
        text-align: center;
    }

    /* Kolom No, Link, dsb jika ingin diatur tengah */
    .table td, .table th {
        vertical-align: middle;
    }

    /* Contoh border lebih tegas */
    .table-bordered td, .table-bordered th {
        border: 1px solid #b28bd0;
    }
    
    /*slider  */
    #main-carousel {
    margin-bottom: 20px;
    }

    /* .carousel-item img {
        max-height: 400px;
        object-fit: cover;
        object-position: center;
    } */

    .carousel-item img {
    max-height: 400px;
    width: auto;
    margin: 0 auto;
    display: block;
    }

    .navbar-red-footer {
  background-color: #03266a;
  color: #fff;
}
.has-bg-image3 {
  background-image: url('https://pn-kepanjen.go.id/assets/global/images/backgrounds/indonesia-map.png');
  background-repeat: no-repeat;
  background-position: center;
}
</style>
<body id="page-top">
    <div class='containerr' style="display: none">
        <div class='loader'>
            <div class='loader--dot'></div>
            <div class='loader--dot'></div>
            <div class='loader--dot'></div>
            <div class='loader--dot'></div>
            <div class='loader--dot'></div>
            <div class='loader--dot'></div>
            <div class='loader--text'></div>
        </div>
    </div>
    <div class="_token" data-token="{{ csrf_token() }}"></div>

    <!-- Page Wrapper -->
    <div id="wrapper">
        
        {{-- @include('Admin.sidebar') --}}

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="overflow:visible">

            <!-- Main Content -->
            <div id="content">
                
            <!-- Header dengan Logo dan Judul -->
            <div id="main-header" class="bg-white py-3 px-4 border-bottom">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <!-- Kiri: Logo dan Nama -->
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('img/court.png') }}" alt="Logo" height="110" class="me-4">
                        <div>
                            <h1 class="ml-3 h4 mb-1 text-primary"><b>Sistem Mutasi APS</b></h1>
                            <h1 class="ml-3 h5 mb-0 text-primary"><b>Perusahaan Listrik Negara</b></h1>
                        </div>
                    </div>
                
                    <!-- Kanan: Logo BerAKHLAK -->
                    <div class="mt-3 mt-md-0">
                        <img src="https://assets.kompasiana.com/statics/crawl/552887e36ea83443058b456b.png?t=o&v=300" 
                            alt="PLNBersihNoSuap" 
                            height="70" 
                            class="img-fluid ms-md-4">
                    </div>
                </div>
            </div>
            
            

                @include('Guest.navbar')
                @include('Guest.slider')
                @include('Guest.statistik')

                @yield('content')


                @include('Guest.footer')

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-primary" style="background-color: #000e2b !important">
                <div class="container my-auto">
                    <div class="copyright text-center mb-2">
                        <strong><span class="text-primary">SELAMAT DATANG DI SISTEM MUTASI APS                            
                            </span>
                        </strong>
                    </div>
                    <div class="copyright text-center my-auto">
                        <span>COPYRIGHT &copy; PERUSAHAAN LISTRIK NEGARA {{ now()->year }}</span>
                        <a href="{{ url('/login') }}">.</a>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    {!!Html::script('vendor/jquery/jquery.min.js')!!}
    {!!Html::script('vendor/bootstrap/js/bootstrap.bundle.min.js')!!}

    <!-- Core plugin JavaScript-->
    {!!Html::script('vendor/jquery-easing/jquery.easing.min.js')!!}

    <!-- Custom scripts for all pages-->
    {!!Html::script('js/sb-admin-2.min.js')!!}

    <!-- Page level plugins -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- {!!Html::script('vendor/chart.js/Chart.min.js')!!} --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include a polyfill for ES6 Promises (optional) for IE11 -->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.2.1/js/dataTables.fixedColumns.min.js"></script>


    {!!Html::script('js/Event.js')!!}
    {!!Html::script('js/Magnifier.js')!!}
    {!!Html::script('js/bootstrap-datepicker.js')!!}
    <script>
        let lastScrollTop = 0;
        const header = document.getElementById('main-header');
    
        window.addEventListener('scroll', function () {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
            if (scrollTop > lastScrollTop) {
                // scroll ke bawah
                header.classList.add('hide-header');
            } else {
                // scroll ke atas
                header.classList.remove('hide-header');
            }
    
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });
    </script>
    <script>
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartPerkara').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Masuk', 'Ditolak', 'Disetujui', 'Dalam Proses'],
                datasets: [{
                    label: 'Jumlah Perkara',
                    data: [215, 198, 17, 8],
                    backgroundColor: [
                        '#6a0dad', '#3b82f6', '#f59e0b', '#ef4444'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    
    @yield('script')
</body>

</html>