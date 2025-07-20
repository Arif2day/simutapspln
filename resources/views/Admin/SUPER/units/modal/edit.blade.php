    {{-- Modal Ubah Unit --}}
    <div class="modal fade" id="editUnitModal" tabindex="-1" role="dialog" aria-labelledby="editUnitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUnitModalLabel">Edit Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearEditFormUnit()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddUnit">
                        <div class="form-group">
                            <label for="e_name" class="col-form-label">Name:</label>
                            <input type="text" class="form-control" id="e_name" value="">
                        </div>
                        <div class="form-group">
                            <label for="e_unit_type" class="col-form-label">Unit Type:</label>
                            <select class="form-control" id="e_unit_type">
                                <option value="-1" selected>--Pilih Unit Type--</option>
                                @foreach ($unit_types as $unit_type)                            
                                    <option value="{{ $unit_type->id }}">{{ $unit_type->unit_type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="e_address" class="col-form-label">Address:</label>
                            <textarea id="e_address" class="form-control" name="e_address" cols="30" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="e_id" id="e_id">
                        <input type="hidden" id="linked2" name="linked2" value="{{url('master/units/update')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearEditFormUnit()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="updateUnit()">Update</button>
                </div>
            </div>
        </div>
    </div>