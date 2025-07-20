    {{-- Modal Tambah EmployeeStatus --}}
    <div class="modal fade" id="addEmployeeStatusModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeStatusModalLabel">Add Employee Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearFormEmployeeStatus()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddEmployeeStatus">
                        <div class="form-group">
                            <label for="status_name" class="col-form-label">Status Name:</label>
                            <input type="text" class="form-control" id="status_name" value="">
                        </div>

                        <input type="hidden" id="linked1" name="linked1" value="{{url('master/employee-status')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearFormEmployeeStatus()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="saveEmployeeStatus()">Save</button>
                </div>
            </div>
        </div>
    </div>