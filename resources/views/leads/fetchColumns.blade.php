<div class="row">
    <div class="col-6">
        @if (
            \Auth::user()->type == 'super admin' ||
                \Auth::user()->type == 'Project Director' ||
                \Auth::user()->type == 'Project Manager' ||
                \Auth::user()->can('level 1') ||
                \Auth::user()->can('level 2'))
            <label for="branches" class="col-form-label">Brands<span class="text-danger">*</span></label>
            <div class="form-group" id="brand_div">
                {!! Form::select('brand_id', $companies, 0, [
                    'class' => 'form-control select2 brand_id',
                    'id' => 'brands',
                ]) !!}
            </div>
        @elseif (Session::get('is_company_login') == true || \Auth::user()->type == 'company')
            <label for="branches" class="col-form-label">Brands<span class="text-danger">*</span></label>
            <div class="form-group" id="brand_div">
                <input type="hidden" name="brand_id" value="{{ \Auth::user()->id }}">
                <select class='form-control select2 brand_id' disabled ="brands" id="brand_id">
                    @foreach ($companies as $key => $comp)
                        <option value="{{ $key }}" {{ $key == \Auth::user()->id ? 'selected' : '' }}>
                            {{ $comp }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <label for="branches" class="col-form-label">Brands<span class="text-danger">*</span></label>
            <div class="form-group" id="brand_div">
                <input type="hidden" name="brand_id" value="{{ \Auth::user()->brand_id }}">
                <select class='form-control select2 brand_id' disabled ="brands" id="brand_id">
                    @foreach ($companies as $key => $comp)
                        <option value="{{ $key }}" {{ $key == \Auth::user()->brand_id ? 'selected' : '' }}>
                            {{ $comp }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
    <div class="col-6">
        @if (
            \Auth::user()->type == 'super admin' ||
                \Auth::user()->type == 'Project Director' ||
                \Auth::user()->type == 'Project Manager' ||
                \Auth::user()->type == 'company' ||
                \Auth::user()->type == 'Region Manager' ||
                \Auth::user()->can('level 1') ||
                \Auth::user()->can('level 2') ||
                \Auth::user()->can('level 3'))
            <label for="branches" class="col-form-label">Region<span class="text-danger">*</span></label>
            <div class="form-group" id="region_div">
                {!! Form::select('region_id', $regions, null, [
                    'class' => 'form-control select2',
                    'id' => 'region_id',
                ]) !!}
            </div>
        @else
            <label for="branches" class="col-form-label">Region<span class="text-danger">*</span></label>
            <div class="form-group" id="region_div">
                <input type="hidden" name="region_id" value="{{ \Auth::user()->region_id }}">
                {!! Form::select('region_id', $regions, \Auth::user()->region_id, [
                    'class' => 'form-control select2',
                    'disabled' => 'disabled',
                    'id' => 'region_id',
                ]) !!}
            </div>
        @endif
    </div>
    <div class="col-6">
        @if (
            \Auth::user()->type == 'super admin' ||
                \Auth::user()->type == 'Project Director' ||
                \Auth::user()->type == 'Project Manager' ||
                \Auth::user()->type == 'company' ||
                \Auth::user()->type == 'Region Manager' ||
                \Auth::user()->type == 'Branch Manager' ||
                \Auth::user()->can('level 1') ||
                \Auth::user()->can('level 2') ||
                \Auth::user()->can('level 3') ||
                \Auth::user()->can('level 4'))

            <label for="branches" class="col-form-label">Branch<span class="text-danger">*</span></label>
            <div class="form-group" id="branch_div">
                <select name="lead_branch" id="lead_branch" class="form-control select2 branch_id" onchange="Change(this)">
                    @foreach ($branches as $key => $branch)
                        <option value="{{ $key }}">{{ $branch }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <label for="branches" class="col-form-label">Branch<span class="text-danger">*</span></label>
            <div class="form-group" id="branch_div">
                <input type="hidden" name="lead_branch" value="{{ \Auth::user()->branch_id }}">
                <select name="lead_branch" id="branch_id" class="form-control select2 branch_id" onchange="Change(this)">
                    @foreach ($branches as $key => $branch)
                        <option value="{{ $key }}" {{ \Auth::user()->branch_id == $key ? 'selected' : '' }}>
                            {{ $branch }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>


    <div class="col-6">
        <label for="">Lead Assigned to <span class="text-danger">*</span></label>
        <div id="assign_to_div">
            <select name="lead_assgigned_user" id="assigned_to" class="form form-control">
                @foreach ($employees as $key => $employee)
                    <option value="{{ $key }}">{{ $employee }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>


<div class="row mt-3">
    <div class="col-md-3">
        <p><b>FILE Column</b></p>
    </div>
    <div class="col-md-3">
        <p><b>Leads Columns</b></p>
    </div>
    <div class="col-md-3">
        <p><b>FILE Column</b></p>
    </div>
    <div class="col-md-3">
        <p><b>Leads Columns</b></p>
    </div>


    <div class="row">
        <?php foreach($first_row as $key => $row){ ?>
        <div class="col-md-3 mt-3"><label for=""><?= $row ?></label></div>
        <div class="col-md-3 mt-3">
            <select name="columns[<?= $row ?>]" id="" data-id="<?= $key ?>"
                class="form form-control lead-columns">
                <option value="">Select Column</option>
                <option value="name">Name</option>
                <option value="email">Email</option>
                <option value="phone">Phone</option>
                <option value="subject">Subject</option>
                <option value="products">Products</option>
                <option value="sources">Sources</option>
                <option value="notes">Notes</option>
                <option value="labels">Label</option>
                <option value="street">Address</option>
            </select>
        </div>
        <?php } ?>
    </div>
</div>

<script>
    $(".brand_id").on("change", function() {

        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-regions') }}',
            data: {
                id: id
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#region_div').html('');
                    $("#region_div").html(data.html);
                    select2();
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });


    $(document).on("change", ".region_id", function() {
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branches') }}',
            data: {
                id: id
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#branch_div').html('');
                    $("#branch_div").html(data.html);
                    select2();
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });

    $(document).on("change", ".branch_id", function() {
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branch-users') }}',
            data: {
                id: id
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#assign_to_div').html('');
                    $("#assign_to_div").html(data.html);
                    select2();
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });
</script>
