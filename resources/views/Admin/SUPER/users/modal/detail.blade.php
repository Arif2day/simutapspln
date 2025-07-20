    {{-- Modal Detail User --}}
    <div class="modal fade" id="detailUserModal" tabindex="-1" role="dialog" aria-labelledby="detailUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailUserModalLabel">Detail Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearFormDetailUser()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                      <!-- Kiri -->
                      <div class="col-md-6">
                        <form id="formdetailUser">
                            <h5 class="text-right mb-3">Employee Profile</h5>
                          <div class="form-group row">
                            <label for="d_f_name" class="col-sm-4 col-form-label">First Name:</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" id="d_f_name" value="" disabled>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="d_l_name" class="col-sm-4 col-form-label">Last Name:</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" id="d_l_name" value="" disabled>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="d_e_mail" class="col-sm-4 col-form-label">Email:</label>
                            <div class="col-sm-8">
                              <input type="email" class="form-control" id="d_e_mail" disabled>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="d_p_hone" class="col-sm-4 col-form-label">Phone:</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" id="d_p_hone" value="" disabled>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="d_birthdate" class="col-sm-4 col-form-label">Birthdate:</label>
                            <div class="col-sm-8">
                              <input type="date" class="form-control" id="d_birthdate" value="" disabled>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="d_role_insert" class="col-sm-4 col-form-label">Role:</label>
                            <div class="col-sm-8">
                              <select class="form-control" id="d_role_insert" disabled>
                                <option value="-1">--Pilih Role--</option>
                                @foreach ($roles as $role)                            
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <input type="hidden" name="d_id" id="d_id">
                        </form>
                      </div>
                  
                      <!-- Kanan -->
                      <div class="col-md-6">
                        <form id="formdetailRight">
                            <h5 class="text-right mb-3" id="headerModeSimpan">Employee Placement</h5>                          
                          <div class="form-group row">
                            <label for="d_Unit" class="col-sm-4 col-form-label">Unit:</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="d_unit">
                                    <option value="-1" selected>--Pilih Unit--</option>
                                    @foreach ($units as $unit)                            
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="d_position" class="col-sm-4 col-form-label">Position:</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="d_position">
                                    <option value="-1" selected>--Pilih Posisi--</option>
                                    @foreach ($positions as $position)                            
                                        <option value="{{ $position->id }}">{{ $position->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="d_start_placement" class="col-sm-4 col-form-label">Placement Start:</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="d_start" value="">                                
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="d_end_placement" class="col-sm-4 col-form-label">Placement End:</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="d_end" value="">                                
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="d_reason" class="col-sm-4 col-form-label">End Reason:</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="d_reason">
                                    <option value="-1" selected>--Pilih Reason--</option>
                                    @foreach ($employee_statuses as $employee_status)                            
                                        <option value="{{ $employee_status->id }}">{{ $employee_status->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="d_status" class="col-sm-4 col-form-label">Status:</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="d_status">
                                    <option value="-1" selected>--Pilih Status--</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                          </div>
                          <div class="row justify-content-center">
                            <div class="btn-group mr-4">
                              <button type="button" class="btn btn-secondary"
                              onclick="closePlacementForm()">Cancel</button>
                              <button type="button" class="btn btn-info" id="btnModeSimpan" onclick="saveDetailUser()">Save</button>
                            </div>
                          </div>
                          <input type="hidden" id="mode_simpan">                        
                          <input type="hidden" id="d_id_placement">                        
                          <input type="hidden" id="linked3" name="linked3" value="{{url('user-manager/placement/update')}}">
                          <input type="hidden" id="linked4" name="linked4" value="{{url('user-manager/placement')}}">
                        </form>
                      </div>
                    </div>                    
                </div>
                  
                <div class="modal-footer" style="background-color: #d3d3d326">
                  <div class="card-body">
                    <div class="row justify-content-between align-items-center mb-2">
                      <div class="col">
                        <h1 class="h5">Employee Placement History</h1>
                      </div>
                      <div class="col-auto">
                        <button class="btn btn-sm btn-info addPlacementBtn">
                          <i class="fas fa-fw fa-plus-circle"></i> Add Placement
                        </button>
                      </div>
                    </div>                    
                    <div class="text-xs">
                        <table class="table placements-datatable display" style="width:100%;">
                            <thead class="text-center">
                                <th style="max-width: 20px">NO</th>
                                <th>UNIT</th>
                                <th style="max-width: 35px">POSITION</th>
                                <th style="max-width: 45px">START</th>
                                <th style="max-width: 45px">END</th>
                                <th style="max-width: 30px">REASON</th>
                                <th style="max-width: 25px">STATUS</th>
                                <th style="max-width: 33px">ACTION</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" id="user_id_placement_list">
                        <input type="hidden" id="urllistPlacement" name="urllistPlacement" value="{{url('user-manager/placement/list')}}">
                        <input type="hidden" id="urldelPlacement" name="urldelPlacement" value="{{url('user-manager/placement')}}">                        
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>