
{{ Form::open(array('id' => 'tagForm')) }}
<div class="modal-body">
    <input type="hidden" name="id" value="{{ $LeadTag->id }}">
    <div class="row">
        <div class="col-md-12 mt-2">
            <label for="">Tag Name</label>
            <input type="text" name="name" value="{{ $LeadTag->tag }}" class="form-control" required placeholder="Enter Your Tag">
        </div>
    </div>
    <div class="row" id="bulk-assign">
        @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
        <div class="col-md-12 mt-2" id="brand_id_div">
            <label for="">Brand</label>
            <select name="brand" class="form form-control select2" id="filter_brand_id">
                @foreach ($filters['brands'] ?? [] as $key => $Brand)
                <option value="{{ $key }}" {{ !empty($LeadTag->brand_id) && $LeadTag->brand_id == $key ? 'selected' : '' }}>{{ $Brand }}</option>
                @endforeach
                @empty($filters['brands'])
                <option value="" disabled>No brands available</option>
                @endempty
            </select>
        </div>
        @endif

        @if(\Auth::user()->type == 'company' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3'))
        <div class="col-md-12 mt-2" id="region_bulkassign_div">
            <label for="">Region</label>
            <select name="region_id" class="form form-control select2" id="filter_region_id">
                @foreach ($filters['regions'] ?? [] as $key => $region)
                <option value="{{ $key }}" {{ !empty($LeadTag->region_id) && $LeadTag->region_id == $key ? 'selected' : '' }}>{{ $region }}</option>
                @endforeach
                @empty($filters['regions'])
                <option value="" disabled>No regions available</option>
                @endempty
            </select>
        </div>
        @endif

        @if(\Auth::user()->type == 'company' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3') || \Auth::user()->can('level 4'))
        <div class="col-md-12 mt-2" id="branch_bulkassign_div">
            <label for="">Branch</label>
            <select name="branch_id" class="form form-control select2" id="filter_branch_id">
                @foreach ($filters['branches'] ?? [] as $key => $branch)
                <option value="{{ $key }}" {{ !empty($LeadTag->branch_id) && $LeadTag->branch_id == $key ? 'selected' : '' }}>{{ $branch }}</option>
                @endforeach
                @empty($filters['branches'])
                <option value="" disabled>No regions available</option>
                @endempty
            </select>
        </div>
        @endif

    </div>
</div>
<br>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="button" onclick="updateTagData()" value="{{__('Update')}}" id="tagupdateappend" class="btn  btn-dark px-2">
</div>
{{Form::close()}}
