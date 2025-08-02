@extends('Admin.main')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Permohonan Mutasi</h1>
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
                @if(!Sentinel::getUser()->inRole('peserta'))                    
                    <div class="col-xl-4 mb-2">
                        <label class="small mb-1">Jenis Review</label>
                        <select id="is_reviewed" class="form-select" aria-label="is_reviewed">
                            <option value="all" selected>Semua</option>
                            <option value="0">Belum Direview</option>
                        </select>
                    </div>
                    <div class="col-xl-4 mt-xl-3" style="align-self: center;">
                        <button class="btn btn-sm btn-primary" onclick="execFil()">
                            <i class="fa fa-search"></i> Tampilkan Data</button>
                    </div>
                @endif
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
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Detail Profil --}}
        <div class="col-xl-12 col-md-12 col-lg-12 mb-1">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Riwayat Permohonan Mutasi</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row float-right mr-0 mb-2">
                        {{-- <button class="btn btn-sm btn-primary mr-2" disabled>
                            <i class="fas fa-fw fa-print"></i> Cetak Laporan</button> --}}
                        {{-- <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#addUnitModal">
                            <i class="fas fa-fw fa-plus-circle"></i> Add Unit</button> --}}
                    </div>
                    <div class="text-xs">
                        <table class="table history-datatable display" style="width:100%;">
                            <thead class="text-center">
                                <th>NO</th>
                                <th>PEMOHON</th>
                                <th>CURR. UNIT NAME</th>
                                <th>DEST. UNIT NAME</th>
                                <th>DEST. POSITION</th>
                                <th>STATUS</th>
                                <th>PREV STEP</th>
                                <th>NEXT STEP</th>
                                <th>DOCUMENTS</th>
                                <th>ACTION</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" id="urllist" name="urllist" value="{{url('permohonan-mutasi/riwayat/list')}}">
                        <input type="hidden" id="urldel" name="urldel" value="{{url('permohonan-mutasi/riwayat')}}">                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @include('Admin.PESERTA.permohonan-mutasi.modal.ajukan') --}}
    {{-- @include('Admin.SUPER.units.modal.edit') --}}

</div>
@endsection
@section('script')
<script>
    var table;
    $(document).ready(function() {
        // Ambil parameter dari URL
        const params = new URLSearchParams(window.location.search);
        const isReviewed = params.get('is_reviewed');

        // Set nilai <select> berdasarkan parameter
        if (isReviewed === '0') {
            $('#is_reviewed').val('0').trigger('change');
        } else {
            $('#is_reviewed').val('all').trigger('change'); // kosong / all
        }
        

        table = $('.history-datatable').DataTable({   
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
                    d.is_reviewed =  @json(Sentinel::getUser()->inRole('peserta'))?'all': $('#is_reviewed option:selected').val()
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
                $(cells[8]).addClass('text-sm')                   
                $(cells[9]).addClass('text-center text-sm')                   
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'user.nama', name: 'user.nama'},
                {data: 'unit_from.name', name: 'unit_from.name'},
                {data: 'unit_to.name', name: 'unit_to.name'},
                {data: 'position_to.title', name: 'position_to.title'},
                {data: 'status', name: 'status'},
                {data: 'prev_step', name: 'prev_step'},
                {data: 'next_step', name: 'next_step'},
                {data: 'document_view', name: 'document_view'},
                {data: 'action', name:'action'},               
            ]       
        });

    });

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
    
    function execFil() {    
        table.ajax.reload();
    }
</script>
@endsection
