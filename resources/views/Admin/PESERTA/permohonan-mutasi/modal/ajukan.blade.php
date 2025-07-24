    {{-- Modal Tambah PengajuanMutasi --}}
    <div class="modal fade" id="addPengajuanMutasiModal" tabindex="-1" role="dialog" aria-labelledby="addPengajuanMutasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPengajuanMutasiModalLabel">Formulir Pengajuan Mutasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearFormPengajuanMutasi()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <!-- Kiri -->
                        <div class="col-md-6">
                            <form id="formdetailUser">
                                <h5 class="mb-3 text-right">Unit Asal Mutasi</h5>
                                <div class="form-group row">
                                    <label for="a_unit_name" class="col-sm-4 col-form-label">Unit Name:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="a_unit_name" disabled>
                                            @foreach ($units as $unit)                            
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="a_address" class="col-sm-4 col-form-label">Address:</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control" id="a_address" value="" disabled>
                                    </div>
                                </div>                                           
                                <div class="form-group row">
                                    <label for="a_position" class="col-sm-4 col-form-label">Position:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="a_position" disabled>
                                            @foreach ($positions as $position)                            
                                                <option value="{{ $position->id }}">{{ $position->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>                              
                                <input type="hidden" name="user_id" id="user_id">
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form id="formdetailRight">
                                <h5 class="mb-3 text-right">Unit Tujuan Mutasi</h5>
                                <div class="form-group row">
                                    <label for="t_unit_name" class="col-sm-4 col-form-label">Unit Name:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="t_unit_name" disabled>
                                            @foreach ($units as $unit)                            
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="t_address" class="col-sm-4 col-form-label">Address:</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control" id="t_address" value="" disabled>
                                    </div>
                                </div>                                           
                                <div class="form-group row">
                                    <label for="t_position" class="col-sm-4 col-form-label">Position:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="t_position" disabled>
                                            @foreach ($positions as $position)                            
                                                <option value="{{ $position->id }}">{{ $position->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="t_allocation" class="col-sm-4 col-form-label">Allocation:</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control" id="t_allocation" value="" disabled>
                                    </div>
                                </div>                        
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-5">
                      <!-- Kiri -->
                      <div class="col-md-6">
                        <form id="formdetailUser">
                            <h5 class="text-right mb-3">Upload Dokumen Pendukung</h5>
                          <div class="form-group row">
                            <label for="keterangan" class="col-sm-4 col-form-label">Keterangan:</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" id="keterangan">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="fileInput" class="col-sm-4 col-form-label">Pilih Dokumen:</label>
                            <div class="col-sm-8">
                              <input type="file" class="form-control" id="fileInput" accept=".pdf, .jpg, .jpeg, .png">
                            </div>
                          </div>
                          <input type="hidden" name="d_id" id="d_id">
                          <div class="row justify-content-center">
                            <div class="btn-group mr-4">
                              <button type="button" class="btn btn-secondary"
                              onclick="clearForm()">Clear</button>
                              <button type="button" class="btn btn-info" id="btnSavePerngajuanMutasi" onclick="uploadDokumen()">Upload</button>
                            </div>
                          </div>
                        </form>
                      </div>
                  
                      <!-- Kanan -->
                      <div class="col-md-6">
                        <form id="formdetailRight">
                            <h5 class="text-right mb-3" id="headerModeSimpan">Daftar Dokumen Pendukung</h5>                          
                            <div class="text-xs">
                                <table class="table document-datatable display" style="width:100%;">
                                    <thead class="text-center">
                                        <th>NO</th>
                                        <th>DOKUMEN</th>
                                        <th>KETERANGAN</th>
                                        <th>ACTION</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                {{-- <input type="hidden" id="urllist" name="urllist" value="{{url('permohonan-mutasi/permohonan/list')}}">
                                <input type="hidden" id="urldel" name="urldel" value="{{url('permohonan-mutasi/permohonan')}}">                         --}}
                            </div>                       
                          {{-- <input type="hidden" id="linked3" name="linked3" value="{{url('user-manager/placement/update')}}">
                          <input type="hidden" id="linked4" name="linked4" value="{{url('user-manager/placement')}}"> --}}
                        </form>
                      </div>
                    </div>                    
                </div>

                <div class="modal-footer text-sm">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <b class="text-danger">* File persyaratan yang perlu diupload:</b>
                                <ul>
                                    <li>1. KTP</li>
                                    <li>2. KK</li>
                                </ul>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-secondary" data-dismiss="modal"
                                onclick="clearFormPengajuanMutasi()">
                                    <i class="fa fa-times"></i> Cancel
                                </button>
                                {{-- <button class="btn btn-primary submitDraft" onclick="submitPermohonan('draft')">
                                    💾 Simpan Sebagai Draft
                                </button> --}}
                                <button class="btn btn-info submitPermohonan" onclick="submitPermohonan('submitted')">
                                    🚀 Submit Permohonan
                                </button>
                                <input type="hidden" name="linked1" id="linked1" value="{{ url('permohonan-mutasi/permohonan') }}">
                                <input type="hidden" name="linked2" id="linked2" value="{{ url('permohonan-mutasi/riwayat') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>