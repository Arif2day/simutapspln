    {{-- Modal Tambah User --}}
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearEditFormUser()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditUser">
                        <div class="form-group">
                            <label for="f_name" class="col-form-label">First Name:</label>
                            <input type="text" class="form-control" id="e_f_name" value="">
                        </div>
                        <div class="form-group">
                            <label for="l_name" class="col-form-label">Last Name:</label>
                            <input type="text" class="form-control" id="e_l_name" value="">
                        </div>
                        <div class="form-group">
                            <label for="e_mail" class="col-form-label">Email:</label>
                            <input type="text" class="form-control" id="e_e_mail" value="">
                        </div>
                        <div class="form-group">
                            <label for="p_hone" class="col-form-label">Phone:</label>
                            <input type="text" class="form-control" id="e_p_hone" value="" maxlength="15">
                        </div>
                        <div class="form-group">
                            <label for="e_birthdate" class="col-form-label">Birthdate:</label>
                            <input type="date" class="form-control" id="e_birthdate" value="" >
                        </div>
                        <div class="form-group">
                            <label for="role_insert" class="col-form-label">Role:</label>
                            <select id="e_role_insert" class="form-select" aria-label="e_role_insert">
                                <option value="-1" selected>--Pilih Role--</option>
                                @foreach ($roles as $role)                            
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Password" class="col-form-label">Password:</label>
                            <input type="password" class="form-control" id="e_Password">
                        </div>
                        <div class="form-group">
                            <label for="rePassword" class="col-form-label">Retype Password:</label>
                            <input type="password" class="form-control" id="e_rePassword">
                        </div>
                        <div class="text-right">
                            <div class="custom-control custom-checkbox small ml-2">
                                <input type="checkbox" class="custom-control-input" id="e_customCheck">
                                <label class="custom-control-label p-1" for="e_customCheck">Show Password</label>
                            </div>
                        </div>
                        <input type="hidden" name="e_id" id="e_id" >
                        <input type="hidden" id="linked2" name="linked2" value="{{url('user-manager/update')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearEditFormUser()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="updateUser()">Update</button>
                </div>
            </div>
        </div>
    </div>