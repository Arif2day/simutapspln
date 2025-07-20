    {{-- Modal Tambah User --}}
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearFormUser()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddUser">
                        <div class="form-group">
                            <label for="f_name" class="col-form-label">First Name:</label>
                            <input type="text" class="form-control" id="f_name" value="">
                        </div>
                        <div class="form-group">
                            <label for="l_name" class="col-form-label">Last Name:</label>
                            <input type="text" class="form-control" id="l_name" value="">
                        </div>
                        <div class="form-group">
                            <label for="e_mail" class="col-form-label">Email:</label>
                            <input type="text" class="form-control" id="e_mail" value="">
                        </div>
                        <div class="form-group">
                            <label for="p_hone" class="col-form-label">Phone:</label>
                            <input type="text" class="form-control" id="p_hone" value="" maxlength="15">
                        </div>
                        <div class="form-group">
                            <label for="birthdate" class="col-form-label">Birthdate:</label>
                            <input type="date" class="form-control" id="birthdate" value="" maxlength="15">
                        </div>
                        <div class="form-group">
                            <label for="role_insert" class="col-form-label">Role:</label>
                            <select id="role_insert" class="form-select" aria-label="role_insert">
                                <option value="-1" selected>--Pilih Role--</option>
                                @foreach ($roles as $role)                            
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Password" class="col-form-label">Password:</label>
                            <input type="password" class="form-control" id="Password">
                        </div>
                        <div class="form-group">
                            <label for="rePassword" class="col-form-label">Retype Password:</label>
                            <input type="password" class="form-control" id="rePassword">
                        </div>
                        <div class="text-right">
                            <div class="custom-control custom-checkbox small ml-2">
                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                <label class="custom-control-label p-1" for="customCheck">Show Password</label>
                            </div>
                        </div>

                        <input type="hidden" id="linked1" name="linked1" value="{{url('user-manager')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearFormUser()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="saveUser()">Save</button>
                </div>
            </div>
        </div>
    </div>