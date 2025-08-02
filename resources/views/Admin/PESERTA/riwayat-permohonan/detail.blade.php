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
                                        <th>TIPE</th>
                                        <th>DOKUMEN</th>
                                        <th>UPLOADED BY</th>
                                        <th>UPLOADER</th>
                                        <th>UPLOADED AT</th>
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
                    <h6 class="m-0 font-weight-bold text-primary">Proses Verifikasi</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row mx-1 justify-content-center mb-2">                        
                        @if ($apsrequest->next_verificator_id==Sentinel::getUser()->id&&$apsrequest->next_verificator_id!=$apsrequest->user_id)                            
                            @if ($apsrequest->prev_step=="bpo_tujuan"||($apsrequest->prev_step=="htd_tujuan"&&$apsrequest->next_step=="htd_asal"))
                                <button  class="btn btn-sm btn-success mr-2" onclick="uploadNotaDinas()">Upload/Terbitkan Nota Dinas</button>                        
                                @else
                                @if ($apsrequest->next_step=="htd_korporat")
                                    <button  class="btn btn-sm btn-success mr-2" onclick="approveRequest()">Approve & Proses Penerbitan SK </button>                        
                                @else
                                    <button  class="btn btn-sm btn-success mr-2" onclick="approveRequest()">Approve</button>                        
                                @endif
                                <button  class="btn btn-sm btn-danger" onclick="rejectRequest()">Reject</button>                        
                            @endif  
                        @else
                            @if ($apsrequest->status=='rejected')                            
                            Ajuan telah selesai dengan status<br><span class="text-danger font-weight-bold">{{ strtoupper($apsrequest->status) }}</span>
                            @elseif ($apsrequest->status=='approved')
                            Ajuan telah selesai dengan status<br><span class="text-success font-weight-bold">{{ strtoupper($apsrequest->status) }}</span>
                            @else
                            Menunggu Verifikasi Oleh  
                            <br>
                            <span class="text-primary font-weight-bold">{{ strtoupper($apsrequest->next_step) }}</span>
                            @endif
                        @endif
                        
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Verifikasi</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row float-right mx-1 mb-2">
                        
                        <div class="container_div">
                            <span class="text_div text_end" title="{{ $apsrequest->user->nama }}">Pemohon</span>
                            <div class="divider_div divider_div_done">
                                <span class="icon">&#10003;</span> <!-- Unicode centang -->
                            </div>
                            <span class="text_div text_end" title="{{ $apsrequest->created_at->translatedFormat('d F Y H:i') }}">Submitted</span>
                        </div>
                        @foreach ($approvals as $item)                            
                            <div class="container_div">
                                <?php
                                $appr_title = '';
                                switch ($item->step) {
                                    case 'bpo_asal':
                                        $appr_title = 'GM Asal';
                                        # code...
                                        break;
                                    case 'htd_asal':
                                        $appr_title = 'HTD Asal';
                                        # code...
                                        break;
                                    case 'bpo_tujuan':
                                        $appr_title = 'GM Tujuan';
                                        # code...
                                        break;
                                    case 'htd_tujuan':
                                        $appr_title = 'HTD Tujuan';
                                        # code...
                                        break;
                                    case 'htd_korporat':
                                        $appr_title = 'HTD Korporat';
                                        # code...
                                        break;
                                    default:
                                        $appr_title = 'Pemohon';
                                        # code...
                                        break;
                                }
                                ?>
                                <span class="text_div text_end" title="{{ $item->approver->nama }}">{{ $appr_title }}</span>
                                <div class="divider_div divider_div_done">
                                    @if ($item->status=='approved')
                                        <span class="icon">&#10003;</span> 
                                    @else
                                        <span class="icon" style="color:red!important">&#x2718;</span> <!-- Unicode silang -->                                
                                    @endif
                                </div>
                                <span class="text_div text_end" title="{{ \Carbon\Carbon::parse($item->approved_at)->translatedFormat('d F Y H:i') }}">{{ ucfirst($item->status) }}</span>
                            </div>
                        @endforeach
                        <div class="container_div">
                            <?php
                                $title = '';
                                switch ($apsrequest->next_step) {
                                    case 'bpo_asal':
                                        $title = 'GM Asal';
                                        # code...
                                        break;
                                    case 'htd_asal':
                                        $title = 'HTD Asal';
                                        # code...
                                        break;
                                    case 'bpo_tujuan':
                                        $title = 'GM Tujuan';
                                        # code...
                                        break;
                                    case 'htd_tujuan':
                                        $title = 'HTD Tujuan';
                                        # code...
                                        break;
                                    case 'htd_korporat':
                                        $title = 'HTD Korporat';
                                        # code...
                                        break;
                                    default:
                                        $title = 'Pemohon';
                                        # code...
                                        break;
                                }
                                ?>
                            <span class="text_div text_end" title="{{ $apsrequest->verificator->nama }}">{{$title}}</span>
                            <div class="divider_div">
                                @if ($apsrequest->status == 'approved')                                    
                                    <span class="icon" >&#10003;</span> <!-- Unicode silang -->   
                                @elseif($apsrequest->status == 'rejected')
                                    <span class="icon" style="color:red!important">&#x2718;</span> <!-- Unicode silang -->                                
                                @else                             
                                    <span class="icon" >&#x231B;</span> <!-- Unicode silang -->                                
                                @endif
                            </div>
                            <span class="text_div text_end" title="{{ 
                                $apsrequest->status == 'approved' || $apsrequest->status == 'rejected' 
                                    ? $apsrequest->updated_at->translatedFormat('d F Y H:i') 
                                    : 'Waiting for response' 
                                }}">
                                @if ($apsrequest->status=='approved'||$apsrequest->status=='rejected')
                                    Done
                                    @else
                                    Waiting
                                @endif
                            </span>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="urlresponse" id="urlresponse" value="{{ url('permohonan-mutasi/riwayat/response') }}">
<input type="hidden" name="urlresponse2" id="urlresponse2" value="{{ url('permohonan-mutasi/riwayat/response-reject') }}">
<input type="hidden" name="urlresponse3" id="urlresponse3" value="{{ url('permohonan-mutasi/riwayat/response-upload') }}">
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
            {data: 'document_type', name: 'document_type'},
            {data: 'document_view', name: 'document_view'},
            {data: 'uploader.nama_role', name: 'uploader.nama_role'},
            {data: 'uploader.nama', name:'uploader.nama'},               
            {data: 'uploaded_at', name: 'uploaded_at'},
        ]       
    });

    function execFil() {    
        table.ajax.reload();
    }

    function approveRequest() {
        if("{{ Sentinel::getUser()->roles()->first()->slug }}"=="super-admin"){
            approveCorporation();
        }else{
            approveNonCorporation();
        }
    }

    function rejectRequest() {
        Swal.fire({
            title: 'Upload Surat Jawaban',
            html: `
                <p>Pastikan surat jawaban sudah benar sebelum menolak.</p>
                <div class="form-group row">
                    <label for="surat_jawaban" class="col-sm-4 col-form-label">Pilih Dokumen:</label>
                    <div class="col-sm-8">                        
                        <input type="file" id="surat_jawaban" class="form-control" accept=".pdf" />
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#ccc',
            confirmButtonText: 'Ya, tolak!',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const file = document.getElementById('surat_jawaban').files[0];
                if (!file) {
                    Swal.showValidationMessage('Mohon unggah dokumen Surat Jawaban terlebih dahulu.');
                    return false;
                }
                return file;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                let file = result.value;
                let formData = new FormData();
                formData.append('_method', 'POST');
                formData.append('_token', $('._token').data('token'));
                formData.append('aps_request_id', {{ $apsrequest->id }});
                formData.append('surat_jawaban', file);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: $("#urlresponse2").val(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.error === false) {
                            Swal.fire({ icon: 'success', title: 'Rejected!', text: data.message })
                            .then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.message,
                            });
                        }
                    }
                });
            }
        }); 
    }

    function approveCorporation() {
        Swal.fire({
            title: 'Upload SK Terbit',
            html: `
                <p>Pastikan SK sudah benar sebelum menyetujui.</p>
                <div class="form-group row">
                    <label for="sk_terbit" class="col-sm-4 col-form-label">Pilih Dokumen:</label>
                    <div class="col-sm-8">                        
                        <input type="file" id="sk_terbit" class="form-control" accept=".pdf" />
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#ccc',
            confirmButtonText: 'Ya, setujui!',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const file = document.getElementById('sk_terbit').files[0];
                if (!file) {
                    Swal.showValidationMessage('Mohon unggah dokumen SK Terbit terlebih dahulu.');
                    return false;
                }
                return file;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                let file = result.value;
                let formData = new FormData();
                formData.append('_method', 'POST');
                formData.append('_token', $('._token').data('token'));
                formData.append('aps_request_id', {{ $apsrequest->id }});
                formData.append('sk_terbit', file);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: $("#urlresponse").val(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.error === false) {
                            Swal.fire({ icon: 'success', title: 'Approved!', text: data.message })
                            .then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.message,
                            });
                        }
                    }
                });
            }
        });
    }

    function approveNonCorporation() {
        Swal.fire({
            title: 'Yakin akan menyetujui?',
            text: "Data ini tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#ccc',
            confirmButtonText: 'Ya, setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
            // AJAX call here
            let datar = {};
                datar['_method']='POST';
                datar['_token']=$('._token').data('token');
                datar['aps_request_id']={{ $apsrequest->id }};
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'post',
                    url: $("#urlresponse").val(),
                    data:datar,
                    success: function(data) {
                        if (data.error==false) {
                            table.ajax.reload();
                            Swal.fire({icon: 'success', title: 'Approved!',text: data.message})
                            .then(() => {
                                location.reload();                                
                            });
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

    function uploadNotaDinas() {
        Swal.fire({
            title: 'Upload Nota Dinas',
            html: `
                <p>Pastikan Nota Dinas sudah benar sebelum mengupload.</p>
                <div class="form-group row">
                    <label for="nota_dinas" class="col-sm-4 col-form-label">Pilih Dokumen:</label>
                    <div class="col-sm-8">                        
                        <input type="file" id="nota_dinas" class="form-control" accept=".pdf" />
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#ccc',
            confirmButtonText: 'Ya, upload!',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const file = document.getElementById('nota_dinas').files[0];
                if (!file) {
                    Swal.showValidationMessage('Mohon unggah dokumen Nota Dinas terlebih dahulu.');
                    return false;
                }
                return file;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                let file = result.value;
                let formData = new FormData();
                formData.append('_method', 'POST');
                formData.append('_token', $('._token').data('token'));
                formData.append('aps_request_id', {{ $apsrequest->id }});
                formData.append('nota_dinas', file);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: $("#urlresponse3").val(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.error === false) {
                            Swal.fire({ icon: 'success', title: 'Uploaded!', text: data.message })
                            .then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.message,
                            });
                        }
                    }
                });
            }
        });
    }

</script>
@endsection