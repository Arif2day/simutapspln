    {{-- Modal Ubah UnitType --}}
    <div class="modal fade" id="editUnitTypeModal" tabindex="-1" role="dialog" aria-labelledby="editUnitTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUnitTypeModalLabel">Edit Unit Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearEditFormUnitType()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddUnitType">
                        <div class="form-group">
                            <label for="e_name" class="col-form-label">Unit Type Name:</label>
                            <input type="text" class="form-control" id="e_name" value="">
                        </div>
                        <input type="hidden" name="e_id" id="e_id">
                        <input type="hidden" id="linked2" name="linked2" value="{{url('master/unit-types/update')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearEditFormUnitType()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="updateUnitType()">Update</button>
                </div>
            </div>
        </div>
    </div>