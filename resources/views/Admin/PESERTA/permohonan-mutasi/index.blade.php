@extends('Admin.main')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Permohonan Mutasi</h1>
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
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Ketersediaan Peluang Tujuan Mutasi</h6>
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
                        <table class="table availability-datatable display" style="width:100%;">
                            <thead class="text-center">
                                <th>NO</th>
                                <th>UNIT NAME</th>
                                <th>ADDRESS</th>
                                <th>POSITION AVAILIBILITY</th>
                                <th>ALLOCATION</th>
                                <th>ACTION</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" id="urllist" name="urllist" value="{{url('permohonan-mutasi/permohonan/list')}}">
                        <input type="hidden" id="urldel" name="urldel" value="{{url('permohonan-mutasi/permohonan')}}">                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Admin.PESERTA.permohonan-mutasi.modal.ajukan')
    {{-- @include('Admin.SUPER.units.modal.edit') --}}

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

    var table = $('.availability-datatable').DataTable({   
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
            // $(cells[5]).addClass('text-center text-sm')                   
            // $(cells[6]).addClass('text-center text-sm')                   
            // $(cells[7]).addClass('text-center text-sm')                   
            // $(cells[8]).addClass('text-center text-sm')                   
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'unit_name', name: 'unit_name'},
            {data: 'unit_address', name: 'unit_address'},
            {data: 'position_name', name: 'position_name'},
            {data: 'total', name: 'total'},
            {data: 'action', name:'action'},               
        ]       
    });

    function execFil() {    
        table.ajax.reload();
    }

    // function deleteUnit(params) {
    //     Swal.fire({
    //         title: 'Yakin?',
    //         text: "Anda akan menghapus Data Unit.",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Ya, hapus sekarang!'
    //         }).then((result) => {
    //         if (result.isConfirmed) {
    //             let datar = {};
    //             datar['_method']='DELETE';
    //             datar['_token']=$('._token').data('token');
    //             datar['id']=params;
    //             $.ajaxSetup({
    //                 headers: {
    //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                 }
    //             });
    //             $.ajax({
    //                 type: 'delete',
    //                 url: $("#urldel").val(),
    //                 data:datar,
    //                 success: function(data) {
    //                     if (data.error==false) {
    //                         table.ajax.reload();
    //                         Swal.fire({icon: 'success', title: 'Deleted!',text: data.message});
    //                     }else{
    //                         Swal.fire({
    //                         icon: 'error',
    //                         title: 'Oops...',
    //                         text: data.message,
    //                         });
    //                     }
    //                 },
    //             });                
    //         }
    //     });
    // }   

    // Form Sort Function
    

    // function clearEditFormUnit() {
    //     document.getElementById('e_id').value='';
    //     document.getElementById('e_name').value='';
    //     document.getElementById('e_address').value='';
    //     $('#e_unit_type').val(-1);
    // } 

    // function saveUnit() {
    //     let name = $('input[id=name').val();
    //     let address = $('textarea#address').val();        
    //     let unit_type_id = $('#unit_type').val();
        
    //     if (name=="") {
    //         Swal.fire({icon: 'error', title: 'Oops...',text: "Name Required!",});
    //     }else if (unit_type_id=="-1") {
    //         Swal.fire({icon: 'error', title: 'Oops...',text: "Pilih Unit Type Dahulu!",});
    //     }else if (address=="") {
    //         Swal.fire({icon: 'error', title: 'Oops...',text: "Address Required!",});
    //     }else{
    //         let datar = {};
    //         datar['_method']='POST';
    //         datar['_token']=$('._token').data('token');
    //         datar['name']=name;
    //         datar['address']=address;
    //         datar['unit_type_id']=unit_type_id;
    //         $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         });
    //         $.ajax({
    //         type: 'post',
    //         url: $("#linked1").val(),
    //         data:datar,
    //         success: function(data) {
    //             if (data.error==false) {
    //                 table.ajax.reload();
    //                 clearFormUnit();
    //                 $('#addUnitModal').modal('hide');
    //                 Swal.fire({icon: 'success', title: 'Horray...',text: data.message});
    //             }else{
    //                 Swal.fire({
    //                 icon: 'error',
    //                 title: 'Oops...',
    //                 text: data.message,
    //                 });
    //             }
    //         },
    //         });
    //     }
    // } 

    // function updateUnit() {
    //     let id = $('input[id=e_id').val();
    //     let name = $('input[id=e_name').val();
    //     let address = $('textarea#e_address').val();        
    //     let unit_type_id = $('#e_unit_type').val();
        
    //     if (name=="") {
    //         Swal.fire({icon: 'error', title: 'Oops...',text: "Name Required!",});
    //     }else if (unit_type_id=="-1") {
    //         Swal.fire({icon: 'error', title: 'Oops...',text: "Pilih Unit Type Dahulu!",});
    //     }else if (address=="") {
    //         Swal.fire({icon: 'error', title: 'Oops...',text: "Address Required!",});
    //     }else{
    //         let datar = {};
    //         datar['_method']='POST';
    //         datar['_token']=$('._token').data('token');
    //         datar['id']=id;
    //         datar['name']=name;
    //         datar['address']=address;
    //         datar['unit_type_id']=unit_type_id;
    //         $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         });
    //         $.ajax({
    //         type: 'post',
    //         url: $("#linked2").val(),
    //         data:datar,
    //         success: function(data) {
    //             if (data.error==false) {
    //                 table.ajax.reload();
    //                 clearEditFormUnit();
    //                 $('#editUnitModal').modal('hide');
    //                 Swal.fire({icon: 'success', title: 'Horray...',text: data.message});
    //             }else{
    //                 Swal.fire({
    //                 icon: 'error',
    //                 title: 'Oops...',
    //                 text: data.message,
    //                 });
    //             }
    //         },
    //         });
    //     }
    // } 

    $(document).on('click', '.addPengajuanMutasiBtn', function () {
        // Ambil data dari atribut tombol
        var user_id = $(this).data('user_id');
        var unit_id = $(this).data('unit_id');
        var unit_name = $(this).data('unit_name');
        var unit_address = $(this).data('unit_address');
        var position_id = $(this).data('position_id');
        var position_name = $(this).data('position_name');
        var allocation = $(this).data('allocation');
        var a_unit_id = $(this).data('a_unit_id');
        var a_unit_name = $(this).data('a_unit_name');
        var a_unit_address = $(this).data('a_unit_address');
        var a_position_id = $(this).data('a_position_id');
        var a_position_name = $(this).data('a_position_name');
        
        // Set data ke form dalam modal
        $('#addPengajuanMutasiModal select[id="a_unit_name"]').val(a_unit_id);
        $('#addPengajuanMutasiModal input[id="a_address"]').val(a_unit_address);
        $('#addPengajuanMutasiModal select[id="a_position"]').val(a_position_id);

        $('#addPengajuanMutasiModal select[id="t_unit_name"]').val(unit_id);
        $('#addPengajuanMutasiModal input[id="t_address"]').val(unit_address);
        $('#addPengajuanMutasiModal select[id="t_position"]').val(position_id);
        $('#addPengajuanMutasiModal input[id="t_allocation"]').val(allocation);

        $('#addPengajuanMutasiModal input[id="user_id"]').val(user_id);        
    });
</script>
<script>
    let documents = [];

    function uploadDokumen() {
        const fileInput = document.getElementById('fileInput');
        const noteInput = document.getElementById('keterangan');

        if (!fileInput.files.length) {
            alert("Pilih file terlebih dahulu");
            return;
        }

        const file = fileInput.files[0];
        const note = noteInput.value;

        const reader = new FileReader();
        reader.onload = function(e) {
            const url = e.target.result;
            const fileName = file.name;

            documents.push({ url, note, fileName });

            renderTable();
            fileInput.value = '';
            noteInput.value = '';
        };

        reader.readAsDataURL(file); // base64 URL
    }

    function clearForm() {
        document.getElementById('keterangan').value='';
        document.getElementById('fileInput').value='';
    } 

    function renderTable() {
        const tbody = document.querySelector(".document-datatable tbody");
        tbody.innerHTML = "";

        documents.forEach((doc, index) => {
            const link = `<a href="${doc.url}" target="_blank">${doc.fileName}</a>`;

            tbody.innerHTML += `
                <tr>
                    <td class="text-center">${index+1}</td>
                    <td>${link}</td>
                    <td class="text-center">${doc.note}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteDoc(${index})"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            `;
        });
    }


    function deleteDoc(index) {
        documents.splice(index, 1);
        renderTable();
    }

    function clearFormPengajuanMutasi(){        
        clearForm();
        documents=[];
        renderTable();
    }

    function submitPermohonan(draftOrSubmit) {
        if(documents.length===0){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Dokumen masih kosong!',
            });
            return;
        }
        $('.containerr').show();
        let user_id = $('input[id=user_id').val();
        let unit_id_from = $('select[id=a_unit_name]').val();        
        let position_id_from = $('select[id=a_position]').val();
        let unit_id_to = $('select[id=t_unit_name]').val();        
        let position_id_to = $('select[id=t_position]').val();
        
        let datar = {};
        datar['_method']='POST';
        datar['_token']=$('._token').data('token');
        datar['user_id']=user_id;
        datar['unit_id_from']=unit_id_from;
        datar['position_id_from']=position_id_from;
        datar['unit_id_to']=unit_id_to;
        datar['position_id_to']=position_id_to;
        datar['documents']=JSON.stringify(documents);
        datar['mode_simpan']=draftOrSubmit;
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
                clearFormPengajuanMutasi();
                $('#addPengajuanMutasiModal').modal('hide');
                $('.containerr').hide();
                Swal.fire({icon: 'success', title: 'Horray...',text: data.message})
                .then(() => {
                    window.location.href = $("#linked2").val();
                });
            }else{
                $('.containerr').hide();
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message,
                });
            }
        },
        });
    }
</script>
@endsection