<div class="filter-data px-3" id="filter-show"  <?= (isset($_GET) && !empty($_GET) && empty($_GET['perPage'])) ? '' : 'style="display: none;"' ?>>
    <form action="/trainer" method="GET" class="">
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

            <div class="col-md-5 mt-3 d-flex">
                <br>
                <input type="submit" class="btn form-btn bg-dark" style=" color:white;">
                <a href="/trainer" style="margin: 0px 3px;" class="btn form-btn bg-dark" style="color:white;">Reset</a>
                <a type="button" id="save-filter-btn" onClick="saveFilter('trainer',<?= isset($trainers) && is_countable($trainers) ? sizeof($trainers) : 0 ?>)" class="btn form-btn me-2 bg-dark" style=" color:white;display:none;">Save Filter</a>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('filter-btn-show').addEventListener('click', function() {
        $('.select2').select2();
        var filterDiv = document.getElementById('filter-show');
        if (filterDiv.style.display === 'none' || filterDiv.style.display === '') {
            filterDiv.style.display = 'block';
        } else {
            filterDiv.style.display = 'none';
        }
    });
</script>

