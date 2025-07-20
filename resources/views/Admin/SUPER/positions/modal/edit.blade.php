    {{-- Modal Ubah Position --}}
    <div class="modal fade" id="editPositionModal" tabindex="-1" role="dialog" aria-labelledby="editPositionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPositionModalLabel">Edit Position</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearEditFormPosition()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddPosition">
                        <div class="form-group">
                            <label for="e_title" class="col-form-label">Title:</label>
                            <input type="text" class="form-control" id="e_title" value="">
                        </div>
                        <input type="hidden" name="e_id" id="e_id">
                        <input type="hidden" id="linked2" name="linked2" value="{{url('master/positions/update')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearEditFormPosition()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="updatePosition()">Update</button>
                </div>
            </div>
        </div>
    </div>