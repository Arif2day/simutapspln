    {{-- Modal Tambah Unit --}}
    <div class="modal fade" id="addUnitResReqModal" tabindex="-1" role="dialog" aria-labelledby="addUnitResReqModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUnitResReqModalLabel">Add Unit Resource Requirement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearFormUnitResReq()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddUnitResReq">
                        <div class="form-group">
                            <label for="unit" class="col-form-label">Unit:</label>
                            <select class="form-control" id="unit_id">
                                <option value="-1" selected>--Pilih Unit--</option>
                                @foreach ($units as $unit)                            
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="position" class="col-form-label">Position:</label>
                            <select class="form-control" id="position_id">
                                <option value="-1" selected>--Pilih Position--</option>
                                @foreach ($positions as $position)                            
                                <option value="{{ $position->id }}">{{ $position->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="allocation" class="col-form-label">Allocation:</label>
                            <input type="number" class="form-control" id="allocation" value="">
                        </div>

                        <input type="hidden" id="linked1" name="linked1" value="{{url('ftk/unit-resource-requirements')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearFormUnitResReq()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="saveUnitResReq()">Save</button>
                </div>
            </div>
        </div>
    </div>