@extends('Admin.main')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Manager</h1>
    </div>

    {{-- Filter --}}
    <div class="card border-bottom-primary shadow h-100 py-2 mb-4">
        <div class="d-flex justify-content-between mx-3 align-items-center" id="accordion">
            <div class="">
                Filter
            </div>
            <div class="">
                <a class="font-weight-bold btn btn-link btn-sm" id="toogFil" data-toggle="collapse"
                    data-target="#collapseFilter" aria-expanded="true" aria-controls="collapseFilter">
                    -
                </a>
            </div>
        </div>
        <div id="collapseFilter" class="collapse show" aria-labelledby="headingFilter" data-parent="#accordion">
            <div class="row m-2">
                <div class="col-xl-4 mb-2">
                    <label class="small mb-1">Jenis Role</label>
                    <select id="role" class="form-select" aria-label="role">
                        <option value="all" selected>Semua</option>
                        @foreach ($roles as $role)                            
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-xl-4 mb-2">
                    <label class="small mb-1">Status</label> --}}
                    {{-- <select id="status_mhs" class="form-select" aria-label="Status">
                        <option value="all" selected>Semua</option>
                        @foreach($status_mhs as $index => $v)
                        <option value="{{$v}}" {{$v=="AKTIF" ?"Selected":""}}>{{ucfirst(strtolower($v))}}</option>
                        @endforeach
                    </select> --}}
                    {{--
                </div> --}}
                <div class="col-xl-4 mt-xl-3" style="align-self: center;">
                    <button class="btn btn-sm btn-primary" onclick="execFil()">
                        <i class="fa fa-search"></i> Tampilkan Data</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Detail Profil --}}
        <div class="col-xl-12 col-md-12 col-lg-12 mb-1">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Data Users</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row float-right mr-0 mb-2">
                        {{-- <button class="btn btn-sm btn-primary mr-2" disabled>
                            <i class="fas fa-fw fa-print"></i> Cetak Laporan</button> --}}
                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#addUserModal">
                            <i class="fas fa-fw fa-plus-circle"></i> Add Users</button>
                    </div>
                    <div class="text-xs">
                        <table class="table users-datatable display" style="width:100%;">
                            <thead class="text-center">
                                <th>NO</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>PHONE</th>
                                <th>ROLE</th>
                                <th>ACTION</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" id="urllist" name="urllist" value="{{url('user-manager/list')}}">
                        <input type="hidden" id="urldel" name="urldel" value="{{url('user-manager')}}">                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Admin.SUPER.users.modal.add')
    @include('Admin.SUPER.users.modal.edit')

</div>
@endsection
@section('script')
<script>
    // Filter Sort Function
    $('#toogFil').click(function(){ //you can give id or class name here for $('button')
        $(this).text(function(i,old){
            return old=='+' ?  '-' : '+';
        });
    });
    
    // Data Sort Function
    $(document).ajaxComplete(function(){
        if($('#DataTables_Table_0_length').length != 0) {
            $('#DataTables_Table_0_length').css('margin-right', '17px');
        }
    });

    var table = $('.users-datatable').DataTable({   
        pageLength : 25,
        dom: 'lfrtip',        
        processing: true,
        serverSide: true,
        ordering: true,    
        "scrollX":true,
        rowId:  'id',
        ajax: {
            url:$('#urllist').val(),
            type:"POST",
            data:function(d){
                d._token = $('._token').data('token')
                d.role = $('#role option:selected').val()
            }}, 
        createdRow: function(row, data, dataIndex, cells) {
            // console.log( data.FeederAKM );
            $(row).addClass('transparentClass') 
            $(cells[0]).addClass('text-center text-sm')
            $(cells[1]).addClass('text-sm')
            $(cells[2]).addClass('text-sm')
            $(cells[3]).addClass('text-center text-sm')                   
            $(cells[4]).addClass('text-center text-sm')                   
            $(cells[5]).addClass('text-center text-sm')                   
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nama', name: 'nama'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'nama_role', name: 'role'},
            {data: 'action', name:'action'},               
        ]       
    });

    function execFil() {    
        table.ajax.reload();
    }

    function deleteUser(params) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! All correlated data which use this user id will also delete.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                let datar = {};
                datar['_method']='DELETE';
                datar['_token']=$('._token').data('token');
                datar['id']=params;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'delete',
                    url: $("#urldel").val(),
                    data:datar,
                    success: function(data) {
                        if (data.error==false) {
                            table.ajax.reload();
                            Swal.fire({icon: 'success', title: 'Deleted!',text: data.message});
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
        });
    }   

    // Form Sort Function
    function clearFormUser() {
        document.getElementById('f_name').value='';
        document.getElementById('l_name').value='';
        document.getElementById('e_mail').value='';
        document.getElementById('p_hone').value='';
        document.getElementById('Password').value='';
        document.getElementById('rePassword').value='';
        document.getElementById('Password').type='password';
        document.getElementById('rePassword').type='password';
        document.getElementById('customCheck').checked = false;
        $('#role_insert').val(-1);
        // .prop('checked', true);
    } 

    function clearEditFormUser() {
        document.getElementById('e_id').value='';
        document.getElementById('e_f_name').value='';
        document.getElementById('e_l_name').value='';
        document.getElementById('e_e_mail').value='';
        document.getElementById('e_p_hone').value='';
        document.getElementById('e_Password').value='';
        document.getElementById('e_rePassword').value='';
        document.getElementById('e_Password').type='password';
        document.getElementById('e_rePassword').type='password';
        document.getElementById('e_customCheck').checked = false;
        $('#role_insert').val(-1);
        // .prop('checked', true);
    } 

    function saveUser() {
        let fina = $('input[id=f_name').val();
        let lana = $('input[id=l_name').val();
        let mail = $('input[id=e_mail').val();
        let phone = $('input[id=p_hone').val();
        let Pwd = $('input[id=Password').val();
        let rePwd = $('input[id=rePassword').val();
        let telp = $('input[id=p_hone').val();
        let role = $('#role_insert option:selected').val();

        const regex = /^(\+62|62|0)8[1-9][0-9]{8,11}$/;
        const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        if (fina=="") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "First Name Required!",});
        }
        else if (lana=="") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Last Name Required!",});
        }
        else if(mail==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Email Required!",});            
        }else if(!re.test(String(mail).toLowerCase())){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Wrong Email Format!",});            
        } else if(!regex.test(telp)){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Wrong Phone Number Format!\n08xxxxxxxxxx or +628xxxxxxxxxx",});            
        }else if(role=="-1"){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Role Required!",});            
        }else if(Pwd==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Password Required!",});            
        }else if(rePwd==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Retype Password Required!",});            
        }else if(Pwd!=rePwd){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Password Doesn't Match!",});            
        }else{
            let datar = {};
            datar['_method']='POST';
            datar['_token']=$('._token').data('token');
            datar['fname']=fina;
            datar['lname']=lana;
            datar['email']=mail;
            datar['phone']=telp;
            datar['password']=Pwd;
            datar['role']=role;
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
                    table.ajax.reload();
                    clearFormUser();
                    $('#addUserModal').modal('hide');
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

    function updateUser() {
        let id = $('input[id=e_id').val();
        let fina = $('input[id=e_f_name').val();
        let lana = $('input[id=e_l_name').val();
        let mail = $('input[id=e_e_mail').val();
        let phone = $('input[id=e_p_hone').val();
        let Pwd = $('input[id=e_Password').val();
        let rePwd = $('input[id=e_rePassword').val();
        let telp = $('input[id=e_p_hone').val();
        let role = $('#e_role_insert option:selected').val();

        const regex = /^(\+62|62|0)8[1-9][0-9]{8,11}$/;
        const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        if (fina=="") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "First Name Required!",});
        }
        else if (lana=="") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Last Name Required!",});
        }
        else if(mail==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Email Required!",});            
        }else if(!re.test(String(mail).toLowerCase())){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Wrong Email Format!",});            
        } else if(!regex.test(telp)){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Wrong Phone Number Format!\n08xxxxxxxxxx or +628xxxxxxxxxx",});            
        }else if(role=="-1"){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Role Required!",});            
        }else if(Pwd==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Password Required!",});            
        }else if(rePwd==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Retype Password Required!",});            
        }else if(Pwd!=rePwd){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Password Doesn't Match!",});            
        }else{
            let datar = {};
            datar['_method']='POST';
            datar['_token']=$('._token').data('token');
            datar['id']=id;
            datar['fname']=fina;
            datar['lname']=lana;
            datar['email']=mail;
            datar['phone']=telp;
            datar['password']=Pwd;
            datar['role_id']=role;
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
                    table.ajax.reload();
                    clearFormUser();
                    $('#editUserModal').modal('hide');
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

    $(document).on('click', '.editUserBtn', function () {
        // Ambil data dari atribut tombol
        var id = $(this).data('id');
        var firstName = $(this).data('first_name');
        var lastName = $(this).data('last_name');
        var email = $(this).data('email');
        var phone = $(this).data('phone');
        var role = $(this).data('role');

        // Set data ke form dalam modal
        $('#editUserModal input[id="e_id"]').val(id);
        $('#editUserModal input[id="e_f_name"]').val(firstName);
        $('#editUserModal input[id="e_l_name"]').val(lastName);
        $('#editUserModal input[id="e_e_mail"]').val(email);
        $('#editUserModal input[id="e_p_hone"]').val(phone);
        $('#editUserModal select[id="e_role_insert"]').val(role);
    });

    document.getElementById("customCheck").addEventListener('change',function(e) {
        if (e.srcElement.checked) {
            document.getElementById('Password').type='text';
            document.getElementById('rePassword').type='text';
        }else{
            document.getElementById('Password').type='password';
            document.getElementById('rePassword').type='password';
        }
    });

    document.getElementById("e_customCheck").addEventListener('change',function(e) {
        if (e.srcElement.checked) {
            document.getElementById('e_Password').type='text';
            document.getElementById('e_rePassword').type='text';
        }else{
            document.getElementById('e_Password').type='password';
            document.getElementById('e_rePassword').type='password';
        }
    });
</script>
@endsection