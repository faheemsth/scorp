<form action="{{ url('branch') }}" id="create-branch" method="post" novalidate>
    @csrf
    <div class="modal-body" style="min-height: 35vh;">
        <div class="row align-items-baseline">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control"
                            placeholder="{{ __('Enter Branch Name') }}">
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
                        \Auth::user()->type == 'HR' ||
                            \Auth::user()->type == 'Admin Team' ||
                            \Auth::user()->type == 'Project Director' ||
                            \Auth::user()->type == 'Project Manager' ||
                            \Auth::user()->can('level 1') ||
                            \Auth::user()->can('level 2'))
                        <label for="branches" class="col-sm-3 col-form-label">Brands<span
                                class="text-danger">*</span></label>
                        {!! Form::select('brands', $brands, 0, [
                            'class' => 'form-control select2 brand_id',
                            'id' => 'brands',
                        ]) !!}

                    @elseif (Session::get('is_company_login') == true || \Auth::user()->type == 'company')
                        <label for="branches" class="col-sm-3 col-form-label">Brands<span
                                class="text-danger">*</span></label>
                        <input type="hidden" name="brands" value="{{ \Auth::user()->id }}">
                        <select class='form-control select2 brand_id' disabled ="brands" id="brands">
                            @foreach ($brands as $key => $comp)
                                <option value="{{ $key }}" {{ $key == \Auth::user()->id ? 'selected' : '' }}>
                                    {{ $comp }}</option>
                            @endforeach
                        </select>
                    @else
                        <label for="branches" class="col-sm-3 col-form-label">Brands<span
                                class="text-danger">*</span></label>
                        <input type="hidden" name="brands" value="{{ \Auth::user()->brand_id }}">
                        <select class='form-control select2 brand_id' disabled  id="brands">
                            @foreach ($brands as $key => $comp)
                                <option value="{{ $key }}"
                                    {{ $key == \Auth::user()->brand_id ? 'selected' : '' }}>{{ $comp }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group" id="region_divs">

                        @if (
                        \Auth::user()->type == 'super admin' ||
                         \Auth::user()->type == 'HR' ||
                        \Auth::user()->type == 'Admin Team' ||
                            \Auth::user()->type == 'Project Director' ||
                            \Auth::user()->type == 'Project Manager' ||
                            \Auth::user()->type == 'company' ||
                            \Auth::user()->type == 'Region Manager' ||
                            \Auth::user()->can('level 1') ||
                            \Auth::user()->can('level 2') )
                        <label for="branches" class="col-sm-3 col-form-label">Region<span
                                class="text-danger">*</span></label>
                        {!! Form::select('region_id', $regions, null, [
                            'class' => 'form-control select2',
                            'id' => 'region_id',
                        ]) !!}
                    @else
                        <label for="branches" class="col-sm-3 col-form-label">Region<span
                                class="text-danger">*</span></label>
                        <input type="hidden" name="region_id" value="{{ \Auth::user()->region_id }}">
                        {!! Form::select('region_id', $regions, \Auth::user()->region_id, [
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
                        <select name="branch_manager_id" id="branch-manager-1" class="form-control select2">
                            <option value="">Select Branch Manager</option>
                            @if (!empty($branchmanager))
                                @foreach ($branchmanager as $branchmanage)
                                    <option value="{{ $branchmanage->id }}">{{ $branchmanage->name }}</option>
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
                        <input type="text" name="google_link" class="form-control"
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
                            placeholder="{{ __('Enter Branch Social Media Link') }}">
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
                        <input type="text" name="phone" id="phone" class="form-control"
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
                        <input type="text" name="email" class="form-control"
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
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn btn-dark px-2 create-branch">
    </div>
</form>

<script>
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
                        $('#region_divs').html('');
                            $("#region_divs").html(data.regions);
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

       
    $(document).on("submit", "#create-branch", function(event) {
        event.preventDefault(); // Prevent the default form submission
        // Serialize form data
        var formData = $(this).serialize();

         // Change button text and disable it
        $(".create-branch").text('Creating...').prop("disabled", true);
        
        // AJAX request
        $.ajax({
            type: "POST",
            url: $(this).attr("action"), // Form action URL
            data: formData, // Serialized form data
            success: function(response) {
              data = JSON.parse(response);

              if(data.status == 'success'){
                show_toastr('success', data.msg, 'success');
                  $('#commonModal').modal('hide');
                  $(".modal-backdrop").removeClass("modal-backdrop");
                  $(".block-screen").css('display', 'none');
                   // Change button text and disable it
                  $(".create-branch").text('Create').prop("disabled", false);
                  openSidebar('/branch/'+data.id+'/show');
              }else{
                $(".create-branch").text('Create').prop("disabled", false);
                show_toastr('Error', data.msg, 'error');
              }

            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        });
    });

    })
</script>

<script>
    // Use the input variable in the rest of your code
    window.intlTelInput(document.getElementById('phone'), {
        utilsScript: "{{ asset('js/intel_util.js') }}",
        initialCountry: "pk",
        separateDialCode: true,
        formatOnDisplay: true,
        hiddenInput: "full_number",
        placeholderNumberType: "FIXED_LINE",
        preferredCountries: ["us", "gb"]
    });
</script>