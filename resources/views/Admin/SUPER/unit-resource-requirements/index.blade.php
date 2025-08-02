@extends('Admin.main')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Unit Resource Requirement</h1>
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
                    <label class="small mb-1">Unit</label>
                    <select id="unit" class="form-select" aria-label="unit">
                        <option value="all" selected>Semua</option>
                        @foreach ($units as $unit)                            
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-4 mb-2">
                    <label class="small mb-1">Position</label> 
                    <select id="position" class="form-select" aria-label="position">
                        <option value="all" selected>Semua</option>
                        @foreach($positions as $position)
                        <option value="{{$position->id}}">{{$position->title}}</option>
                        @endforeach
                    </select>
                    
                </div>
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
                    <h6 class="m-0 font-weight-bold text-primary">Data Unit Resource Requirement</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row float-right mr-0 mb-2">
                        {{-- <button class="btn btn-sm btn-primary mr-2" disabled>
                            <i class="fas fa-fw fa-print"></i> Cetak Laporan</button> --}}
                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#addUnitResReqModal">
                            <i class="fas fa-fw fa-plus-circle"></i> Add Unit Resource Requirement</button>
                    </div>
                    <div class="text-xs">
                        <table class="table unit-res-reqs-datatable display" style="width:100%;">
                            <thead class="text-center">
                                <th style="width: 20px">NO</th>
                                <th>UNIT</th>
                                <th>POSITION</th>
                                <th style="width: 100px">ALLOCATION</th>
                                <th style="width: 100px">ACTION</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" id="urllist" name="urllist" value="{{url('ftk/unit-resource-requirements/list')}}">
                        <input type="hidden" id="urldel" name="urldel" value="{{url('ftk/unit-resource-requirements')}}">                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Admin.SUPER.unit-resource-requirements.modal.add')
    @include('Admin.SUPER.unit-resource-requirements.modal.edit')

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

    var table = $('.unit-res-reqs-datatable').DataTable({   
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
                d.unit = $('#unit option:selected').val()
                d.position = $('#position option:selected').val()
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
            {data: 'unit.name', name: 'unit.name'},
            {data: 'position.title', name: 'position.title'},
            {data: 'allocation', name: 'allocation'},
            {data: 'action', name:'action'},               
        ]       
    });

    function execFil() {    
        table.ajax.reload();
    }

    function deleteUnitResReq(unit_id, position_id) {
        Swal.fire({
            title: 'Yakin?',
            text: "Anda akan menghapus Data Unit Resource Requirement.",
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
                datar['unit_id']=unit_id;
                datar['position_id']=position_id;
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
    function clearFormUnitResReq() {
        document.getElementById('allocation').value='';
        $('#unit_id').val(-1);
        $('#position_id').val(-1);
    } 

    function clearEditFormUnitResReq() {
        document.getElementById('e_id').value='';
        document.getElementById('e_allocation').value='';
        $('#e_unit_id').val(-1);
        $('#e_position_id').val(-1);
    } 

    function saveUnitResReq() {
        let allocation = $('input[id=allocation').val();      
        let unit_id = $('#unit_id').val();
        let position_id = $('#position_id').val();
        
        if (unit_id=="-1") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Pilih Unit Dahulu!",});
        }else if (position_id=="-1") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Pilih Position Dahulu!",});
        }else if (allocation<=0) {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Allocation Must More Than 0!",});
        }else{
            let datar = {};
            datar['_method']='POST';
            datar['_token']=$('._token').data('token');
            datar['allocation']=allocation;
            datar['unit_id']=unit_id;
            datar['position_id']=position_id;
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
                    clearFormUnitResReq();
                    $('#addUnitResReqModal').modal('hide');
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

    function updateUnitResReq() {
        let id = $('input[id=e_id').val();
        let allocation = $('input[id=e_allocation').val();      
        let unit_id = $('#e_unit_id').val();
        let position_id = $('#e_position_id').val();
        
        if (unit_id=="-1") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Pilih Unit Dahulu!",});
        }else if (position_id=="-1") {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Pilih Position Dahulu!",});
        }else if (allocation<=0) {
            Swal.fire({icon: 'error', title: 'Oops...',text: "Allocation Must More Than 0!",});
        }else{
            let datar = {};
            datar['_method']='POST';
            datar['_token']=$('._token').data('token');
            datar['id']=id;
            datar['allocation']=allocation;
            datar['unit_id']=unit_id;
            datar['position_id']=position_id;
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
                    clearEditFormUnitResReq();
                    $('#editUnitResReqModal').modal('hide');
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

    $(document).on('click', '.editUnitResReqBtn', function () {
        // Ambil data dari atribut tombol
        var id = $(this).data('id');
        var unit = $(this).data('unit_id');
        var position = $(this).data('position_id');
        var allocation = $(this).data('allocation');
        
        // Set data ke form dalam modal
        $('#editUnitResReqModal input[id="e_id"]').val(id);
        $('#editUnitResReqModal input[id="e_allocation"]').val(allocation);
        $('#editUnitResReqModal select[id="e_unit_id"]').val(unit);        
        $('#editUnitResReqModal select[id="e_position_id"]').val(position);        
    });
</script>
@endsection