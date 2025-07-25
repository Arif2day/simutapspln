@extends('Admin.main')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Unit</h1>
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
                {{-- <div class="col-xl-4 mb-2">
                    <label class="small mb-1">Jenis Role</label>
                    <select id="role" class="form-select" aria-label="role">
                        <option value="all" selected>Semua</option>
                        @foreach ($roles as $role)                            
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div> --}}
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
                {{-- <div class="col-xl-4 mt-xl-3" style="align-self: center;">
                    <button class="btn btn-sm btn-primary" onclick="execFil()">
                        <i class="fa fa-search"></i> Tampilkan Data</button>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Detail Profil --}}
        <div class="col-xl-12 col-md-12 col-lg-12 mb-1">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Data Unit</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row float-right mr-0 mb-2">
                        {{-- <button class="btn btn-sm btn-primary mr-2" disabled>
                            <i class="fas fa-fw fa-print"></i> Cetak Laporan</button> --}}
                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#addUnitModal">
                            <i class="fas fa-fw fa-plus-circle"></i> Add Unit</button>
                    </div>
                    <div class="text-xs">
                        <table class="table units-datatable display" style="width:100%;">
                            <thead class="text-center">
                                <th style="width: 20px">NO</th>
                                <th>NAME</th>
                                <th>UNIT TYPE</th>
                                <th>ADDRESS</th>
                                <th style="width: 100px">ACTION</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" id="urllist" name="urllist" value="{{url('master/units/list')}}">
                        <input type="hidden" id="urldel" name="urldel" value="{{url('master/units')}}">                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Admin.SUPER.units.modal.add')
    @include('Admin.SUPER.units.modal.edit')

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

    var table = $('.units-datatable').DataTable({   
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
            $(cells[2]).addClass('text-center text-sm')                   
            $(cells[3]).addClass('text-center text-sm')                   
            $(cells[4]).addClass('text-center text-sm')                   
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'get_unit_type.unit_type_name', name: 'get_unit_type.unit_type_name'},
            {data: 'address', name: 'address'},
            {data: 'action', name:'action'},               
        ]       
    });

    function execFil() {    
        table.ajax.reload();
    }

    function deleteUnit(params) {
        Swal.fire({
            title: 'Yakin?',
            text: "Anda akan menghapus Data Unit.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus sekarang!'
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
    function clearFormUnit() {
        document.getElementById('name').value='';
        document.getElementById('address').value='';
        $('#unit_type').val(-1);
    } 

    function clearEditFormUnit() {
        document.getElementById('e_id').value='';
        document.getElementById('e_name').value='';
        document.getElementById('e_address').value='';
        $('#e_unit_type').val(-1);
    } 

    function saveUnit() {
        let name = $('input[id=name').val();
        let address = $('textarea#address').val();        
        let unit_type_id = $('#unit_type').val();
        
        if (name=="") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Name Required!",});
        }else if (unit_type_id=="-1") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Pilih Unit Type Dahulu!",});
        }else if (address=="") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Address Required!",});
        }else{
            let datar = {};
            datar['_method']='POST';
            datar['_token']=$('._token').data('token');
            datar['name']=name;
            datar['address']=address;
            datar['unit_type_id']=unit_type_id;
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
                    clearFormUnit();
                    $('#addUnitModal').modal('hide');
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

    function updateUnit() {
        let id = $('input[id=e_id').val();
        let name = $('input[id=e_name').val();
        let address = $('textarea#e_address').val();        
        let unit_type_id = $('#e_unit_type').val();
        
        if (name=="") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Name Required!",});
        }else if (unit_type_id=="-1") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Pilih Unit Type Dahulu!",});
        }else if (address=="") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Address Required!",});
        }else{
            let datar = {};
            datar['_method']='POST';
            datar['_token']=$('._token').data('token');
            datar['id']=id;
            datar['name']=name;
            datar['address']=address;
            datar['unit_type_id']=unit_type_id;
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
                    clearEditFormUnit();
                    $('#editUnitModal').modal('hide');
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

    $(document).on('click', '.editUnitBtn', function () {
        // Ambil data dari atribut tombol
        var id = $(this).data('id');
        var name = $(this).data('name');
        var address = $(this).data('address');
        var unit_type_id = $(this).data('unit_type_id');
        
        // Set data ke form dalam modal
        $('#editUnitModal input[id="e_id"]').val(id);
        $('#editUnitModal input[id="e_name"]').val(name);
        $('#editUnitModal textarea#e_address').val(address);
        $('#editUnitModal select[id="e_unit_type"]').val(unit_type_id);        
    });
</script>
@endsection