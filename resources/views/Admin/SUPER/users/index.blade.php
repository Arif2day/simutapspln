@extends('Admin.main')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employee Manager</h1>
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
                    <h6 class="m-0 font-weight-bold text-primary">Data Employees</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row float-right mr-0 mb-2">
                        {{-- <button class="btn btn-sm btn-primary mr-2" disabled>
                            <i class="fas fa-fw fa-print"></i> Cetak Laporan</button> --}}
                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#addUserModal">
                            <i class="fas fa-fw fa-plus-circle"></i> Add Employees</button>
                    </div>
                    <div class="text-xs">
                        <table class="table users-datatable display" style="width:100%;">
                            <thead class="text-center">
                                <th>NO</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                {{-- <th>PHONE</th> --}}
                                <th>CLOSE 56th</th>
                                <th>ROLE</th>
                                <th>REGION</th>
                                <th>LAST STATUS</th>
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
    @include('Admin.SUPER.users.modal.detail')

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
            $(cells[6]).addClass('text-center text-sm')                   
            $(cells[7]).addClass('text-center text-sm')                   
            // $(cells[8]).addClass('text-center text-sm')                   
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nama', name: 'nama'},
            {data: 'email', name: 'email'},
            // {data: 'phone', name: 'phone'},
            {data: 'retire', name: 'retire'},
            {data: 'nama_role', name: 'role'},
            {data: 'unit_address', name: 'unit_address'},
            {data: 'last_status', name: 'last_status'},
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
        document.getElementById('birthdate').value='';
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
        document.getElementById('e_birthdate').value='';
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
        let birthdate = $('input[id=birthdate').val();
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
        }else if(birthdate==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Birthdate Required",});            
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
            datar['birthdate']=birthdate;
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
        let birthdate = $('input[id=e_birthdate').val();
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
        }else if(birthdate==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Birthdate Required",});            
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
            datar['birthdate']=birthdate;
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
                    clearEditFormUser();
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
        var birthdate =$(this).data('birthdate');

        // Set data ke form dalam modal
        $('#editUserModal input[id="e_id"]').val(id);
        $('#editUserModal input[id="e_f_name"]').val(firstName);
        $('#editUserModal input[id="e_l_name"]').val(lastName);
        $('#editUserModal input[id="e_e_mail"]').val(email);
        $('#editUserModal input[id="e_p_hone"]').val(phone);
        $('#editUserModal input[id="e_birthdate"]').val(birthdate);
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
<script>
    var table2 = $('.placements-datatable').DataTable({   
        pageLength : 25,
        dom: 'lfrtip',        
        processing: true,
        serverSide: true,
        ordering: true,    
        "scrollX":true,
        rowId:  'id',
        ajax: {
            url:$('#urllistPlacement').val(),
            type:"POST",
            data:function(d){
                d._token = $('._token').data('token')
                d.user_id = $('#user_id_placement_list').val()
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
            $(cells[6]).addClass('text-center text-sm')                   
            $(cells[7]).addClass('text-center text-sm')                   
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'get_unit.name', name: 'get_unit.name'},
            {data: 'get_position.title', name: 'get_position.title'},
            {data: 'placement_start', name: 'placement_start'},
            {data: 'placement_end', name: 'placement_end'},
            {data: 'placement_end_reason', name: 'placement_end_reason'},
            {data: 'status', name: 'status'},
            {data: 'action', name:'action'},               
        ]       
    });

    function execFilPlacement() {    
        table2.ajax.reload();
        table.ajax.reload();
    }    

    $(document).on('click', '.addPlacementBtn', function () {
        $('form#formdetailRight').show();
        $('#detailUserModal input[id="mode_simpan"]').val('new');
        clearFormDetailUser();
        document.getElementById('btnModeSimpan').innerHTML='Save';  
        document.getElementById('headerModeSimpan').innerHTML='Add Employee Placement';                
    });

    $(document).on('click', '.detailUserBtn', function () {
        // Ambil data dari atribut tombol
        var id = $(this).data('id');
        var firstName = $(this).data('first_name');
        var lastName = $(this).data('last_name');
        var email = $(this).data('email');
        var phone = $(this).data('phone');
        var role = $(this).data('role');
        var birthdate =$(this).data('birthdate');


        // Set data ke form dalam modal
        $('#user_id_placement_list').val(id);
        $('#detailUserModal input[id="d_id"]').val(id);
        $('#detailUserModal input[id="d_f_name"]').val(firstName);
        $('#detailUserModal input[id="d_l_name"]').val(lastName);
        $('#detailUserModal input[id="d_e_mail"]').val(email);
        $('#detailUserModal input[id="d_p_hone"]').val(phone);
        $('#detailUserModal input[id="d_birthdate"]').val(birthdate);
        $('#detailUserModal select[id="d_role_insert"]').val(role);
        $('form#formdetailRight').hide();
        execFilPlacement();
    });

    $(document).on('click', '.editUserPlacementBtn', function () {
        $('form#formdetailRight').show();
        var id = $(this).data('id');
        var user_id = $(this).data('user_id');
        var unit_id = $(this).data('unit_id');
        var position_id = $(this).data('position_id');
        var placement_start = $(this).data('placement_start');
        var placement_end = $(this).data('placement_end');
        var placement_end_reason = $(this).data('placement_end_reason');
        var status = $(this).data('status');


        // Set data ke form dalam modal
        $('#user_id_placement_list').val(user_id);
        $('#detailUserModal input[id="d_id_placement"]').val(id);
        $('#detailUserModal select[id="d_unit"]').val(unit_id);
        $('#detailUserModal select[id="d_position"]').val(position_id);
        $('#detailUserModal input[id="d_start"]').val(placement_start);
        $('#detailUserModal input[id="d_end"]').val(placement_end);
        $('#detailUserModal select[id="d_reason"]').val(placement_end_reason==""?'-1':placement_end_reason);
        $('#detailUserModal select[id="d_status"]').val(status);
        $('#detailUserModal input[id="mode_simpan"]').val('update');
        document.getElementById('btnModeSimpan').innerHTML='Update';        
        document.getElementById('headerModeSimpan').innerHTML='Edit Employee Placement';                
    });

    function saveDetailUser() {
        let user_id = $('#user_id_placement_list').val();
        let id = $('#detailUserModal input[id="d_id_placement"]').val();

        let placement_start = $('input[id=d_start').val();
        let placement_end = $('input[id=d_end').val();
        let placement_end_reason = $('#d_reason option:selected').val();
        let status = $('#d_status option:selected').val();
        let unit_id = $('#d_unit option:selected').val();
        let position_id = $('#d_position option:selected').val();
      
        if (unit_id=="-1") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Unit Required!",});
        }
        else if (position_id=="-1") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Position Required!",});
        }
        else if(placement_start==""){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Placement Start Required!",});            
        }else if(status=="-1"){
            Swal.fire({icon: 'error', title: 'Oops...',text: "Status Required!",});            
        }else{
            let datar = {};
            datar['_method']='POST';
            datar['_token']=$('._token').data('token');
            if($('#detailUserModal input[id="mode_simpan"]').val()!='new'){
                datar['id']=id;
            }
            datar['user_id']=user_id;
            datar['placement_start']=placement_start;
            datar['placement_end']=placement_end;
            datar['placement_end_reason']=placement_end_reason;
            datar['unit_id']=unit_id;
            datar['position_id']=position_id;
            datar['status']=status;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
            type: 'post',
            url: $('#detailUserModal input[id="mode_simpan"]').val()=='new'?$("#linked4").val():$("#linked3").val(),
            data:datar,
            success: function(data) {
                if (data.error==false) {
                    execFilPlacement();
                    closePlacementForm();
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

    function closePlacementForm() {
        $('#d_reason').val(-1);
        $('#d_status').val(-1);
        $('#d_unit').val(-1);
        $('#d_position').val(-1);
        $('#d_start').val('');
        $('#d_end').val('');
        $('form#formdetailRight').hide();
    }

    function clearFormDetailUser() {
        document.getElementById('d_id').value='';
        document.getElementById('d_f_name').value='';
        document.getElementById('d_l_name').value='';
        document.getElementById('d_e_mail').value='';
        document.getElementById('d_p_hone').value='';
        document.getElementById('d_birthdate').value='';
        document.getElementById('d_start').value='';
        document.getElementById('d_end').value='';
        $('#d_role_insert').val(-1);
        $('#d_status').val(-1);
        $('#d_unit').val(-1);
        $('#d_position').val(-1);
        // .prop('checked', true);
    } 

    function deleteUserPlacement(params) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Anda akan menghapus data employee placement.",
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
                    url: $("#urldelPlacement").val(),
                    data:datar,
                    success: function(data) {
                        if (data.error==false) {
                            table2.ajax.reload();
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
</script>
@endsection