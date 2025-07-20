    {{-- Modal Tambah UnitType --}}
    <div class="modal fade" id="addUnitTypeModal" tabindex="-1" role="dialog" aria-labelledby="addUnitTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUnitTypeModalLabel">Add Unit Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearFormUnitType()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddUnitType">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Unit Type Name:</label>
                            <input type="text" class="form-control" id="name" value="">
                        </div>

                        <input type="hidden" id="linked1" name="linked1" value="{{url('master/unit-types')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearFormUnitType()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="saveUnitType()">Save</button>
                </div>
            </div>
        </div>
    </div>