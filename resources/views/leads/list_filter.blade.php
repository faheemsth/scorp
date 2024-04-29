<div class="filter-data px-3" id="filter-show" <?= (isset($_GET) && !empty($_GET) && empty($_GET['perPage'])) ? '' : 'style="display: none;"' ?>>
    <form action="/leads/list" method="GET" class="">
        @if (!empty($_GET['num_results_on_page']))
            <input type="hidden" name="num_results_on_page" id="num_results_on_page" value="{{ $_GET['num_results_on_page'] }}">
        @endif
        <input type="hidden" name="page" id="page" value="{{ $_GET['page'] ?? 1 }}">
        <div class="row my-3 align-items-end">
            @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
                <div class="col-md-3 mt-2">
                    <label for="">Brand</label>
                    <select name="brand" class="form form-control select2" id="filter_brand_id">
                        @if (!empty($filters['brands']))
                            @foreach ($filters['brands'] as $key => $brand)
                                <option value="{{ $key }}" {{ (!empty($_GET['brand']) && $_GET['brand'] == $key) ? 'selected' : '' }}>{{ $brand }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>No brands available</option>
                        @endif
                    </select>
                </div>
            @endif

            @if(\Auth::user()->type == 'company' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3'))
                <div class="col-md-3 mt-2" id="region_filter_div">
                    <label for="">Region</label>
                    <select name="region_id" class="form form-control select2" id="filter_region_id">
                        @if (!empty($filters['regions']))
                            @foreach ($filters['regions'] as $key => $region)
                                <option value="{{ $key }}" {{ (!empty($_GET['region_id']) && $_GET['region_id'] == $key) ? 'selected' : '' }}>{{ $region }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>No regions available</option>
                        @endif
                    </select>
                </div>
            @endif

            @if(\Auth::user()->type == 'company' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3') || \Auth::user()->can('level 4'))
                <div class="col-md-3 mt-2" id="branch_filter_div">
                    <label for="">Branch</label>
                    <select name="branch_id" class="form form-control select2" id="filter_branch_id">
                        @if (!empty($filters['branches']))
                            @foreach ($filters['branches'] as $key => $branch)
                                <option value="{{ $key }}" {{ (!empty($_GET['branch_id']) && $_GET['branch_id'] == $key) ? 'selected' : '' }}>{{ $branch }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>No branches available</option>
                        @endif
                    </select>
                </div>
            @endif

            <div class="col-md-3 mt-2">
                <label for="">Assigned To</label>
                <span id="assign_to_div">
                    <select name="lead_assgigned_user" id="choices-multiple333" class="form form-control select2" style="width: 95%;">
                        @foreach ($filters['employees'] as $key => $user)
                            <option value="{{ $key }}" <?= (isset($_GET['lead_assgigned_user']) && $key == $_GET['lead_assgigned_user']) ? 'selected' : '' ?>>{{ $user }}</option>
                        @endforeach
                        <option value="null" {{ (isset($_GET['lead_assgigned_user']) && $_GET['lead_assgigned_user'] == 'null') ? 'selected' : '' }}>Not Assign</option>
                    </select>
                </span>
            </div>

            <div class="col-md-3"> <label for="">Name</label>
                <div class="" id="filter-names">
                    <select class="form form-control select2" id="choices-multiple110" name="name[]" multiple style="width: 95%;">
                        <option value="">Select name</option>
                        @foreach ($leads as $lead)
                            <option value="{{ $lead->id }}" <?= (isset($_GET['name']) && in_array($lead->id, $_GET['name'])) ? 'selected' : '' ?>>{{ $lead->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3"> <label for="">Stage</label>
                <select class="form form-control select2" id="choices-multiple444" name="stages[]" multiple style="width: 95%;">
                    <option value="">Select Stage</option>
                    @foreach ($stages as $stage)
                        <option value="{{ $stage->id }}" <?= (isset($_GET['stages']) && in_array($stage->id, $_GET['stages'])) ? 'selected' : '' ?>>{{ $stage->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mt-2">
                <label for="">Created at From</label>
                <input type="date" class="form form-control" name="created_at_from" value="<?= $_GET['created_at_from'] ?? '' ?>" style="width: 95%; border-color:#aaa">
            </div>

            <div class="col-md-3 mt-2">
                <label for="">Created at To</label>
                <input type="date" class="form form-control" name="created_at_to" value="<?= $_GET['created_at_to'] ?? '' ?>" style="width: 95%; border-color:#aaa">
            </div>

            <div class="col-md-3"> <label for="">Tag</label>
                <select class="form form-control select2" id="tags" name="tag" style="width: 95%;">
                    <option value="">Select Tag</option>
                    @foreach ($tags as $key => $tag)
                        <option value="{{ $key }}" <?= (isset($_GET['tag']) && $key == $_GET['tag']) ? 'selected' : '' ?>>{{ $tag }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-5 mt-3 d-flex">
                <br>
                <input type="submit" class="btn form-btn bg-dark" style=" color:white;">
                <a href="/leads/list" style="margin: 0px 3px;" class="btn form-btn bg-dark" style="color:white;">Reset</a>
                <a type="button" id="save-filter-btn" onClick="saveFilter('leads',<?= sizeof($leads) ?>)" class="btn form-btn me-2 bg-dark" style=" color:white;display:none;">Save Filter</a>
            </div>
        </div>
    </form>
</div>
