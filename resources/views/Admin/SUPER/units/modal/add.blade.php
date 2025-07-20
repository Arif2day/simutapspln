    {{-- Modal Tambah Unit --}}
    <div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="addUnitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUnitModalLabel">Add Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="clearFormUnit()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddUnit">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Name:</label>
                            <input type="text" class="form-control" id="name" value="">
                        </div>
                        <div class="form-group">
                            <label for="unit_type" class="col-form-label">Unit Type:</label>
                            <select class="form-control" id="unit_type">
                                <option value="-1" selected>--Pilih Unit Type--</option>
                                @foreach ($unit_types as $unit_type)                            
                                    <option value="{{ $unit_type->id }}">{{ $unit_type->unit_type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address" class="col-form-label">Address:</label>
                            <textarea name="address" class="form-control" id="address" cols="30" rows="3"></textarea>
                        </div>

                        <input type="hidden" id="linked1" name="linked1" value="{{url('master/units')}}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="clearFormUnit()">Cancel</button>
                    <button type="button" class="btn btn-info" onclick="saveUnit()">Save</button>
                </div>
            </div>
        </div>
    </div>