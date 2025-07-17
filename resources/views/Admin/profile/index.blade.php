@extends('Admin.main')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Profile</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <!-- Profile Summary -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Your Summary</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <img class="img-profile rounded-circle mx-5" src="img/undraw_profile.svg">
                    <div class="mt-4 text-center small">
                        <h4 class="card-title m-t-10 text-lg text-center" id="v_nama2">{{$resu->nama}}
                        </h4>
                        <h6 class="card-subtitle" id="v_email2">{{$resu->email}}</h6>
                    </div>
                </div>
            </div>
        </div>
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">More Summary</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 col-sm-6 col-4">Name</div>
                        <div class="col-xl-9 col-md-6 col-sm-6 col-8" id="v_fname">: {{$resu->nama}}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-xl-3 col-md-6 col-sm-6 col-4">Email Address</div>
                        <div class="col-xl-9 col-md-6 col-sm-6 col-8" id="v_email">: {{$resu->email}}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-xl-3 col-md-6 col-sm-6 col-4">Phone Number</div>
                        <div class="col-xl-9 col-md-6 col-sm-6 col-8" id="v_phone">: {{$resu->phone}}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-xl-3 col-md-6 col-sm-6 col-4">Role As</div>
                        <div class="col-xl-9 col-md-6 col-sm-6 col-8">:
                            {{$resu->nama_role}}
                        </div>
                    </div>
                    <div class="row mt-4">
                        {{-- <button class="btn btn-sm btn-info m-2">
                            Ubah Password
                        </button> --}}
                        <button type="button" class="btn btn-info btn-sm m-2" data-toggle="modal"
                            data-target="#ubahPasswordModal">
                            Ubah Password
                        </button>
                        <button type="button" class="btn btn-sm btn-info m-2" data-toggle="modal"
                            data-target="#ubahProfilModal">
                            Ubah Profil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Ubah Password --}}
    <div class="modal fade" id="ubahPasswordModal" tabindex="-1" role="dialog" aria-labelledby="ubahPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahPasswordModalLabel">Ubah Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearForm()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formUbahPassword">
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email:</label>
                            <input type="text" class="form-control" id="email" value="{{$resu->email}}">
                        </div>
                        <div class="form-group">
                            <label for="oldPassword" class="col-form-label">Old Password:</label>
                            <input type="password" class="form-control" id="oldPassword">
                        </div>
                        <div class="form-group">
                            <label for="newPassword" class="col-form-label">New Password:</label>
                            <input type="password" class="form-control" id="newPassword">
                        </div>
                        <div class="form-group">
                            <label for="renewPassword" class="col-form-label">Retype New Password:</label>
                            <input type="password" class="form-control" id="renewPassword">
                        </div>
                        <div class="text-right">
                            <div class="custom-control custom-checkbox small ml-2">
                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                <label class="custom-control-label p-1" for="customCheck">Show Password</label>
                            </div>
                        </div>
                        <input type="hidden" id="linked2" name="linked2"
                            value="{{url('user-profile-update-password')}}">
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearForm()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="savePassword()">Update</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Ubah Profile --}}
    <div class="modal fade" id="ubahProfilModal" tabindex="-1" role="dialog" aria-labelledby="ubahProfilModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahProfilModalLabel">Ubah Profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearFormProfil()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formUbahProfil">
                        <div class="form-group">
                            <label for="f_name" class="col-form-label">First Name:</label>
                            <input type="text" class="form-control" id="f_name" value="{{$resu->first_name}}">
                        </div>
                        <div class="form-group">
                            <label for="l_name" class="col-form-label">Last Name:</label>
                            <input type="text" class="form-control" id="l_name" value="{{$resu->last_name}}">
                        </div>
                        <div class="form-group">
                            <label for="e_mail" class="col-form-label">Email:</label>
                            <input type="text" class="form-control" id="e_mail" value="{{$resu->email}}">
                        </div>
                        <div class="form-group">
                            <label for="p_hone" class="col-form-label">Phone:</label>
                            <input type="text" class="form-control" id="p_hone" value="{{$resu->phone}}" maxlength="15">
                        </div>
                        <input type="hidden" id="linked1" name="linked1" value="{{url('user-profile-update-profil')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearFormProfil()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="saveProfile()">Update</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@section('script')
<script>
    function clearForm() {
        document.getElementById('oldPassword').value='';
        document.getElementById('newPassword').value='';
        document.getElementById('renewPassword').value='';
    }
    function clearFormProfil() {
        document.getElementById('f_name').value=@json($resu->first_name);
        document.getElementById('l_name').value=@json($resu->last_name);
        document.getElementById('e_mail').value=@json($resu->email);
        document.getElementById('p_hone').value=@json($resu->phone);
    }
    function savePassword(){
        let email = $('input[id=email').val();
        let oldPwd = $('input[id=oldPassword').val();
        let newPwd = $('input[id=newPassword').val();
        let renewPwd = $('input[id=renewPassword').val();
        const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        if(email==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Email Required!",});            
        }else if(!re.test(String(email).toLowerCase())){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Wrong Email Format!",});            
        }else if(oldPwd==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Old Password Required!",});            
        }else if(newPwd==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "New Password Required!",});            
        }else if(renewPwd==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Retype New Password Required!",});            
        }else if(newPwd!=renewPwd){
            Swal.fire({icon: 'error', title: 'Oops...',text: "New Password Doesn't Match!",});            
        } else {
            let datar = {};
            datar['_method']='POST';
            datar['_token']=$('._token').data('token');
            datar['id']=@json($resu->id);
            datar['email']=email;
            datar['oldPwd']=oldPwd;
            datar['newPwd']=newPwd;
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
            type: 'post',
            url: $("#linked2").val(),
            data:datar,
            success: function(data) {
                if (data.error==false) {
                    $('#ubahPasswordModal').modal('hide');
                    Swal.fire({icon: 'success', title: 'Horray...',text: data.message});
                }else{
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message,
                    });
                }
            },
            });   
        }
    }
    function saveProfile() {
        let fina = $('input[id=f_name').val();
        let lana = $('input[id=l_name').val();
        let mail = $('input[id=e_mail').val();
        let telp = $('input[id=p_hone').val();
        const regex = /^(\+62|62|0)8[1-9][0-9]{8,11}$/;
        const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        if (fina=="") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "First Name Required!",});
        }
        // else if (lana=="") {
        //     Swal.fire({icon: 'error', title: 'Oops...',text: "Last Name Required!",});
        // }  
        else if(mail==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Email Required!",});            
        }else if(!re.test(String(mail).toLowerCase())){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Wrong Email Format!",});            
        } else if(!regex.test(telp)){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Wrong Phone Number Format!\n08xxxxxxxxxx or +628xxxxxxxxxx",});            
        } else {
            let datar = {};
            datar['_method']='POST';
            datar['_token']=$('._token').data('token');
            datar['id']=@json($resu->id);
            datar['fname']=fina;
            datar['lname']=lana;
            datar['email']=mail;
            datar['phone']=telp;
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
            type: 'post',
            url: $("#linked1").val(),
            data:datar,
            success: function(data) {
                if (data.error==false) {
                    document.getElementById('v_fname').innerHTML = ': '+data.data.first_name+' '+data.data.last_name;
                    document.getElementById('v_nama2').innerHTML = ': '+data.data.first_name+' '+data.data.last_name;
                    document.getElementById('v_email').innerHTML = ': '+data.data.email;
                    document.getElementById('v_email2').innerHTML = ': '+data.data.email;
                    document.getElementById('v_phone').innerHTML = ': '+data.data.phone;                    
                    $('#ubahProfilModal').modal('hide');
                    Swal.fire({icon: 'success', title: 'Horray...',text: data.message});
                }else{
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message,
                    });
                }
            },
            });        
        }
    }
    document.getElementById("customCheck").addEventListener('change',function(e) {
        if (e.srcElement.checked) {
            document.getElementById('oldPassword').type='text';
            document.getElementById('newPassword').type='text';
            document.getElementById('renewPassword').type='text';
        }else{
            document.getElementById('oldPassword').type='password';
            document.getElementById('newPassword').type='password';
            document.getElementById('renewPassword').type='password';
        }
    });
</script>
@endsection