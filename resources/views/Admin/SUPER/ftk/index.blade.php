@extends('Admin.main')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Formasi Tenaga Kerja</h1>
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
                {{-- <div class="col-xl-4 mb-2">
                    <label class="small mb-1">Position</label> 
                    <select id="position" class="form-select" aria-label="position">
                        <option value="all" selected>Semua</option>
                        @foreach($positions as $position)
                        <option value="{{$position->id}}">{{$position->title}}</option>
                        @endforeach
                    </select>
                    
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
                    <h6 class="m-0 font-weight-bold text-primary">Data Formasi Tenaga Kerja</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row float-right mr-0 mb-2">
                        {{-- <button class="btn btn-sm btn-primary mr-2" disabled>
                            <i class="fas fa-fw fa-print"></i> Cetak Laporan</button> --}}
                        {{-- <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#addUnitResReqModal">
                            <i class="fas fa-fw fa-plus-circle"></i> Add Unit Resource Requirement</button> --}}
                    </div>
                    <div class="text-xs">
                        <table class="table ftk-datatable display" style="width:100%;">
                            <thead class="text-center">
                                <th style="width: 20px">NO</th>
                                <th>UNIT</th>
                                <th>PEGAWAI AKTIF</th>
                                <th>PEGAWAI MENDEKATI 56th</th>
                                <th style="width: 100px">ALOKASI</th>
                                {{-- <th style="width: 100px">% FTK TANPA 56th</th> --}}
                                <th style="width: 100px">% FTK</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" id="urllist" name="urllist" value="{{url('ftk/ftk/list')}}">
                        <input type="hidden" id="urldel" name="urldel" value="{{url('ftk/ftk')}}">                        
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    var table = $('.ftk-datatable').DataTable({   
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
                // d.position = $('#position option:selected').val()
            }}, 
        createdRow: function(row, data, dataIndex, cells) {
            // console.log( data.FeederAKM );
            $(row).addClass('transparentClass') 
            $(cells[0]).addClass('text-center text-sm')
            $(cells[1]).addClass('text-sm')                 
            $(cells[2]).addClass('text-center text-sm')                   
            $(cells[3]).addClass('text-center text-sm')                   
            $(cells[4]).addClass('text-center text-sm')                   
            $(cells[5]).addClass('text-center text-sm')                   
            // $(cells[6]).addClass('text-center text-sm')                   
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'unit_name', name: 'unit_name'},
            {data: 'total_pegawai_aktif', name: 'total_pegawai_aktif'},
            {data: 'close_56', name: 'close_56'},    
            {data: 'total_allocation', name: 'total_allocation'},
            // {data: 'persentase_ftk', name:'persentase_ftk'},               
            {data: 'persentase_ftk_with_56', name:'persentase_ftk_with_56'},               
        ]       
    });

    function execFil() {    
        table.ajax.reload();
    }
</script>
@endsection