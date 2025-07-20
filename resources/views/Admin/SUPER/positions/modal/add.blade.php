    {{-- Modal Tambah Position --}}
    <div class="modal fade" id="addPositionModal" tabindex="-1" role="dialog" aria-labelledby="addPositionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPositionModalLabel">Add Position</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearFormPosition()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddPosition">
                        <div class="form-group">
                            <label for="title" class="col-form-label">Title:</label>
                            <input type="text" class="form-control" id="title" value="">
                        </div>

                        <input type="hidden" id="linked1" name="linked1" value="{{url('master/positions')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearFormPosition()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="savePosition()">Save</button>
                </div>
            </div>
        </div>
    </div>