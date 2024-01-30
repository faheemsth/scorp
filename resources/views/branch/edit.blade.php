{{ Form::model($branch, ['route' => ['branch.update', $branch->id], 'method' => 'PUT', 'novalidate' => 'novalidate']) }}

<div class="modal-body" style="min-height: 35vh;">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Branch Name')]) }}
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        
        <div class="col-md-6">
            <div class="form-group" id="brands_div">
                @if (
                    \Auth::user()->type == 'super admin' ||
                        \Auth::user()->type == 'Project Director' ||
                        \Auth::user()->type == 'Project Manager')
                    <label for="branches" class="col-sm-3 col-form-label">Brands<span
                            class="text-danger">*</span></label>
                    {!! Form::select('brands', $brands, $branch->brands, [
                        'class' => 'form-control select2 brand_id',
                        'id' => 'brands',
                    ]) !!}

                @elseif (Session::get('is_company_login') == true || \Auth::user()->type == 'company')
                    <label for="branches" class="col-sm-3 col-form-label">Brands<span
                            class="text-danger">*</span></label>
                    <input type="hidden" name="brands" value="{{ $branch->brands }}">
                    <select class='form-control select2 brand_id' disabled ="brands" id="brands">
                        @foreach ($brands as $key => $comp)
                            <option value="{{ $key }}" {{ $key == $branch->brands ? 'selected' : '' }}>
                                {{ $comp }}</option>
                        @endforeach
                    </select>
                @else
                    <label for="branches" class="col-sm-3 col-form-label">Brands<span
                            class="text-danger">*</span></label>
                    <input type="hidden" name="brands" value="{{ $branch->brands }}">
                    <select class='form-control select2 brand_id' disabled  id="brands">
                        @foreach ($brands as $key => $comp)
                            <option value="{{ $key }}"
                                {{ $key == $branch->brands ? 'selected' : '' }}>{{ $comp }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>



        <div class="col-md-6">
            <div class="form-group" id="region_div">
                @if (
                        \Auth::user()->type == 'super admin' ||
                            \Auth::user()->type == 'Project Director' ||
                            \Auth::user()->type == 'Project Manager' ||
                            \Auth::user()->type == 'company' ||
                            \Auth::user()->type == 'Regional Manager')
                        <label for="branches" class="col-sm-3 col-form-label">Region<span
                                class="text-danger">*</span></label>
                        {!! Form::select('region_id', $regions, $branch->region_id, [
                            'class' => 'form-control select2',
                            'id' => 'region_id',
                        ]) !!}
                    @else
                        <label for="branches" class="col-sm-3 col-form-label">Region<span
                                class="text-danger">*</span></label>
                        <input type="hidden" name="region_id" value="{{ $branch->region_id }}">
                        {!! Form::select('region_id', $regions, $branch->region_id, [
                            'class' => 'form-control select2',
                            'disabled' => 'disabled',
                            'id' => 'region_id',
                        ]) !!}
                    @endif

                @error('region_id')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>  

        <div class="col-md-6 d-none">
            <div class="form-group">
                <label for="branch_manager_id">{{ __('Branch Manager') }}</label>
                <select name="branch_manager_id" id="" class="form-control">
                    <option value="">Select Branch</option>
                    @if (!empty($branchmanager))
                        @foreach ($branchmanager as $branchmanage)
                            <option value="{{ $branchmanage->id }}"
                                {{ $branch->branch_manager_id == $branchmanage->id ? 'selected' : '' }}>
                                {{ $branchmanage->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('branch_manager_id')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="google_link">{{ __('Google Link') }}</label>
                <input type="text" name="google_link" class="form-control" value="{{ $branch->google_link }}"
                    placeholder="{{ __('Enter Branch Google Link') }}">
                @error('google_link')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="social_media_link">{{ __('Social Media Link') }}</label>
                <input type="text" name="social_media_link" class="form-control"
                    value="{{ $branch->social_media_link }}" placeholder="{{ __('Enter Branch Social Media Link') }}">
                @error('social_media_link')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="phone">{{ __('Phone') }}</label>
                <input type="text" name="phone" class="form-control" value="{{ $branch->phone }}"
                    placeholder="{{ __('Enter Branch Phone') }}">
                @error('phone')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input type="text" name="email" class="form-control" value="{{ $branch->email }}"
                    placeholder="{{ __('Enter Branch Email') }}">
                @error('email')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-dark px-2">
</div>

{{ Form::close() }}
<script>
    $(document).ready(function() {
        select2();
        // $("#region_id").on("change", function(){
        //     var id = $(this).val();

        //     $.ajax({
        //         type: 'GET',
        //         url: '{{ route('region_brands') }}',
        //         data: {
        //             id: id  // Add a key for the id parameter
        //         },
        //         success: function(data){
        //             data = JSON.parse(data);

        //             if (data.status === 'success') {
        //                 $('#brands').remove();
        //                 $("#brands_div").html(data.brands);
        //                 select2();
        //             } else {
        //                 console.error('Server returned an error:', data.message);
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('AJAX request failed:', status, error);
        //         }
        //     });
        // });        
    })

    $(document).ready(function() {

    $("#brands").on("change", function(){
        var id = $(this).val();
        var type = 'brand';

        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands') }}',
            data: {
                id: id,  // Add a key for the id parameter
                type: type
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    if(type == 'brand'){
                        $('#region_div').html('');
                        $("#region_div").html(data.regions);
                        select2();
                    }else{
                        $('#brands').remove();
                        $("#brands_div").html(data.brands);
                        select2();
                    }

                
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });

    $("#region_id").on("change", function(){
        var id = $(this).val();
        var type = 'region';


        $.ajax({
            type: 'GET',
            url: '{{ route('region_brands') }}',
            data: {
                id: id,  // Add a key for the id parameter
                type: type
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    if(type == 'region'){
                        $('#branch_div').html('');
                        $("#branch_div").html(data.branch);
                        select2();
                    }
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });

})
</script>
