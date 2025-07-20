    {{-- Modal Ubah EmployeeStatus --}}
    <div class="modal fade" id="editEmployeeStatusModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeStatusModalLabel">Edit Employee Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearEditFormEmployeeStatus()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddEmployeeStatus">
                        <div class="form-group">
                            <label for="e_status_name" class="col-form-label">Status Name:</label>
                            <input type="text" class="form-control" id="e_status_name" value="">
                        </div>
                        <input type="hidden" name="e_id" id="e_id">
                        <input type="hidden" id="linked2" name="linked2" value="{{url('master/employee-status/update')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearEditFormEmployeeStatus()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="updateEmployeeStatus()">Update</button>
                </div>
            </div>
        </div>
    </div>