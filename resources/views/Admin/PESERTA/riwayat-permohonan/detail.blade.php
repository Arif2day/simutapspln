@extends('Admin.main')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Permohonan Mutasi</h1>
        <a href="{{ url('permohonan-mutasi/riwayat') }}" class="btn btn-sm btn-danger">Kembali</a>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="col-xl-12 col-md-12 col-lg-12 mb-1">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Biodata Pemohon Mutasi</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-md-6 col-sm-6 col-4">Nama</div>
                            <div class="col-xl-9 col-md-6 col-sm-6 col-8" id="v_fname">: {{ $apsrequest->user->first_name.' '.$apsrequest->user->last_name }}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-xl-3 col-md-6 col-sm-6 col-4">Unit saat ini</div>
                            <div class="col-xl-9 col-md-6 col-sm-6 col-8" id="v_email">: {{ $apsrequest->unitFrom->name }}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-xl-3 col-md-6 col-sm-6 col-4">Posisi</div>
                            <div class="col-xl-9 col-md-6 col-sm-6 col-8" id="v_email">: {{ $apsrequest->positionFrom->title }}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-xl-3 col-md-6 col-sm-6 col-4">Penempatan mulai</div>
                            <div class="col-xl-9 col-md-6 col-sm-6 col-8" id="v_phone">: {{ $apsrequest->user->latestPlacement->placement_start }}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-xl-3 col-md-6 col-sm-6 col-4">Status penempatan</div>
                            <div class="col-xl-9 col-md-6 col-sm-6 col-8" id="v_phone">: {{ $apsrequest->user->latestPlacement->status==1?"Aktif":"Nonaktif" }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-md-12 col-lg-12 mb-1">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Destinasi Mutasi</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col-xl-3 col-md-6 col-sm-6 col-4">Unit tujuan</div>
                            <div class="col-xl-9 col-md-6 col-sm-6 col-8" id="v_email">: {{ $apsrequest->unitTo->name }}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-xl-3 col-md-6 col-sm-6 col-4">Posisi tujuan</div>
                            <div class="col-xl-9 col-md-6 col-sm-6 col-8" id="v_email">: {{ $apsrequest->positionTo->title }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-md-12 col-lg-12 mb-1">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Dokumen Pendukung</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        {{-- <div class="row float-right mr-0 mb-2"> --}}
                            <div class="text-xs">
                                <table class="table documents-datatable display" style="width:100%;">
                                    <thead class="text-center">
                                        <th>NO</th>
                                        <th>KETERANGAN</th>
                                        <th>DOKUMEN</th>
                                        <th>UPLOADED BY</th>
                                        <th>UPLOADED AT</th>
                                        <th>ACTION</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <input type="hidden" id="urllist" name="urllist" value="{{url('permohonan-mutasi/riwayat/listDoc')}}">
                            </div>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Proses Verisikasi</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row float-right mx-1 mb-2">
                        <div class="container_div">
                            <span class="text_div">Peserta</span>
                            <div class="divider_div divider_div_done">
                                <span class="icon">&#10003;</span> <!-- Unicode centang -->
                            </div>
                            <span class="text_div">Submitted</span>
                        </div>
                        <div class="container_div">
                            <span class="text_div">BPO Asal</span>
                            <div class="divider_div divider_div_done">
                                <span class="icon" style="color:red!important">&#x2718;</span> <!-- Unicode silang -->                                
                            </div>
                            <span class="text_div">Waiting</span>
                        </div>
                        <div class="container_div">
                            <span class="text_div">Peserta</span>
                            <div class="divider_div">
                                <span class="icon" >&#x231B;</span> <!-- Unicode silang -->                                
                            </div>
                            <span class="text_div">Waiting</span>
                        </div>
                        
                        <span>{{ Sentinel::getUser()->slug }}</span>
                        
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

    var table = $('.documents-datatable').DataTable({   
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
                d.aps_request_id = {{ $apsrequest->id }}
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
            // $(cells[6]).addClass('text-center text-sm')                   
            // $(cells[7]).addClass('text-center text-sm')                   
            // $(cells[8]).addClass('text-center text-sm')                   
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'document_name', name: 'document_name'},
            {data: 'document_view', name: 'document_view'},
            {data: 'uploader.nama_role', name: 'uploader.nama_role'},
            {data: 'uploaded_at', name: 'uploaded_at'},
            {data: 'action', name:'action'},               
        ]       
    });

    function execFil() {    
        table.ajax.reload();
    }
</script>
@endsection