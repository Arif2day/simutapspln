<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIPP</title>

    <!-- Custom fonts for this template-->
    {!!Html::style('vendor/fontawesome-free/css/all.min.css')!!}

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    {!!Html::style('css/sb-admin-2.min.css')!!}
    <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.ico') }}">

    {!!Html::style('css/magnifier.css')!!}
    {!!Html::style('css/loader.css')!!}
    {!!Html::style('css/datepicker3.css')!!}
    {!!Html::style('css/addition.css')!!}
    {{-- {!!Html::style('css/styles.css')!!} --}}
</head>

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

        @include('Admin.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                @include('Admin.navbar')

                @yield('content')

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center mb-2">
                        <strong><span class="text-primary">SELAMAT DATANG,
                                {{Sentinel::getUser()->username}} -
                                {{Str::upper(Sentinel::getUser()->first_name)}}                               
                                [{{ Sentinel::getUser()->nama_role }}]
                            </span>
                        </strong>
                    </div>
                    <div class="copyright text-center my-auto">
                        <span>COPYRIGHT &copy; SIPP {{ now()->year }}</span>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{url('logout')}}">Logout</a>
                </div>
            </div>
        </div>
    </div>

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


        // $(function(){
        //     getNotif();
        //     setInterval(function () {getNotif();}, 4000);
        
        //   });
        
        //   var token = $('meta[name="csrf-token"]').attr('content');
        //   var linked = $("#linkednotif").val();
        //   var uri = linked;
        //   function getNotif(){
        //     $.ajaxSetup({
        //       headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //       }
        //     });
        //     $.ajax({
        //         url : uri,
        //         type : "post",
        //         data : {
        //           '_method':'post',
        //           _token: token
        //         },
        //         success : function msg(response){
        //             if(response['countNotif']==0){
        //                 document.getElementById("counterNotif").style.display='none';
        //             }else{
        //                 document.getElementById("counterNotif").style.display='block';
        //                 $('#counterNotif').html(response['countNotif']);
        //             }
        //             if(response['diskusi']!=0||response['komentar']!=0){
        //                 document.getElementById("diskusi").style.display='block';
        //                 $('#span-diskusi').html(response['diskusi']+" diskusi "+"dan "+
        //                 response['komentar']+" komentar baru");
        //             }else{
        //                 document.getElementById("diskusi").style.display='none';
        //             }
        //             if(response['jawaban']!=0){
        //                 document.getElementById("jawaban").style.display='block';
        //                 $('#span-jawaban').html(response['jawaban']+" submit baru");
        //             }else{
        //                 document.getElementById("jawaban").style.display='none';
        //             }
        //         }
        //     });
        //   }
    </script>
    @yield('script')
</body>

</html>