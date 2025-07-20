<!DOCTYPE html>
<html lang="en">
    <style>
        .image-link {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 2;
            text-indent: -9999px; /* sembunyikan teks */
            background: rgba(0,0,0,0); /* transparan */
        }
    </style>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIMUTAPSPLN - Login</title>

    <!-- Custom fonts for this template-->
    {!!Html::style('vendor/fontawesome-free/css/all.min.css')!!}
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    {!!Html::style('css/sb-admin-2.min.css')!!}
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.ico') }}">
    <style>
        @media (max-width: 991.98px) {
        .border-right-lg-only {
            border-right: none !important;
        }
        }
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
    </style>

</head>

<body class="bg-gradient-custom">
    <div class="_token" data-token="{{ csrf_token() }}"></div>

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row px-5 py-5" style="
                        align-items: center;">                        
                            <div class="col-lg-6 d-lg-block bg-login-image">
                                <a href="{{ url('/') }}" class="image-link" aria-label="Beranda"></a>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-lg-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">SIMUTAPSPLN LOGIN!</h1>
                                    </div>
                                    <form class="user">
                                        <div class="form-group">
                                            {{-- <input type="email" class="form-control" id="username"
                                                aria-describedby="emailHelp" placeholder="Enter Email Address..."
                                                required> --}}
                                            <input type="text" class="form-control form-control-user" id="email"
                                                aria-describedby="emailHelp" placeholder="Email" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password"
                                                placeholder="Password" required>
                                        </div>
                                        {{-- <div class="form-group">
                                            <select name="role" id="role" class="form-control" required>
                                                <option value="0">Mahasiswa</option>
                                                <option value="1">Dosen</option>
                                                <option value="2">Admin</option>
                                            </select>
                                        </div> --}}
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <input type="hidden" id="linked" name="linked" value="{{url('login')}}">
                                        <input type="hidden" id="linkedsc" name="linkedsc" value="{{url('dashboard')}}">
                                        <input type="submit" class="btn btn-custom btn-user btn-block" value="Login">
                                        {{-- <a href="index.html" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </a> --}}
                                        {{--
                                        <hr>
                                        <a href="index.html" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a> --}}
                                    </form>
                                    {{--
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div> --}}
                                </div>
                            </div>
                            {{-- <div class="col-lg-6 mt-5 mt-lg-0 pl-lg-5">                                
                                <div class="row flex-nowrap my-2">
                                    <a href="{{ url('/survei') }}" class="col-lg-6 text-white text-center btn btn-sm btn-primary mr-1 d-flex flex-column justify-content-center align-items-center">
                                      <i class="fa fa-graduation-cap" style="font-size: 30px;"></i>
                                      <span class="mt-1">Statistik Survei Kepuasan Mahasiswa</span>
                                    </a>
                                  
                                    <div class="col-lg-6 text-white text-center btn btn-sm btn-secondary ml-1 disabled d-flex flex-column justify-content-center align-items-center" style="pointer-events: none;min-height:100px">
                                      <i class="fa fa-bell" style="font-size: 30px;"></i>
                                      <span class="mt-1">Statistik Survei Kepuasan ...</span>
                                    </div>
                                </div>
                                <div class="row flex-nowrap my-2">
                                    <div class="col-lg-6 text-white text-center btn btn-sm btn-secondary mr-1 disabled d-flex flex-column justify-content-center align-items-center" style="pointer-events: none;min-height:100px">
                                      <i class="fa fa-certificate" style="font-size: 30px;"></i>
                                      <span class="mt-1">Statistik Survei Kepuasan ...</span>
                                    </div>
                                  
                                    <div class="col-lg-6 text-white text-center btn btn-sm btn-secondary ml-1 disabled d-flex flex-column justify-content-center align-items-center" style="pointer-events: none;min-height:100px">
                                      <i class="fa fa-building" style="font-size: 30px;"></i>
                                      <span class="mt-1">Statistik Survei Kepuasan ...</span>
                                    </div>
                                </div>
                                <div class="row flex-nowrap my-2">
                                    <div class="col-lg-6 text-white text-center btn btn-sm btn-secondary mr-1 disabled d-flex flex-column justify-content-center align-items-center" style="pointer-events: none;min-height:100px">
                                      <i class="fa fa-heartbeat" style="font-size: 30px;"></i>
                                      <span class="mt-1">Statistik Survei Kepuasan ...</span>
                                    </div>
                                  
                                    <div class="col-lg-6 text-white text-center btn btn-sm btn-secondary ml-1 disabled d-flex flex-column justify-content-center align-items-center" style="pointer-events: none;min-height:100px">
                                      <i class="fa fa-microphone" style="font-size: 30px;"></i>
                                      <span class="mt-1">Statistik Survei Kepuasan ...</span>
                                    </div>
                                </div>                                
                            </div> --}}
                        </div>
                    </div>
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const form = document.querySelector('form');
        let inputs = [];

        form.addEventListener('submit',(e)=>{
            e.preventDefault();
            let datar = {};
            datar['_method']='POST';
            datar['_token']=$('._token').data('token');
            datar['email']=$("#email").val();
            datar['password']=$("#password").val();
            datar['role']=$("#role").val();
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
            type: 'post',
            url: $("#linked").val(),
            data:datar,
            success: function(data) {
                if (data.error==false) {
                    window.location.assign($("#linkedsc").val())
                }else{
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message,
                    });
                }
            },
            });        
        })
    </script>
</body>

</html>