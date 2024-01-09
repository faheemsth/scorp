<form action="{{ url('branch') }}" method="post" novalidate>
    @csrf
    <div class="modal-body" style="min-height: 35vh;">
        <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
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
                        <label for="region_id">{{ __('Brands') }}</label>
                        <select name="brands[]" id="brands" id="brands-1" class="form-control select2">
                            <option value="">Select Brands</option>
                            @foreach($brands as $key => $brand)
                            <option value="{{$key}}"> {{$brand}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group" id="region_div">
                        <label for="region_id">{{ __('Region') }}</label>
                        <select name="region_id" id="region_id" class="form-control select2">
                            <option value="">Select Region</option>
                            @if (!empty($regions))
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            @endif
                        </select>
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
                        <input type="text" name="phone" class="form-control"
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
        <input type="submit" value="{{ __('Create') }}" class="btn btn-dark px-2">
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
