{{Form::model($announcement,array('route' => array('announcement.update', $announcement->id), 'method' => 'PUT')) }}
<div class="modal-body py-0" style="height: 75vh;">
    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('title',__('Announcement Title'),['class'=>'form-label'])}}
                {{Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Announcement Title')))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('branch_id',__('Branch'),['class'=>'form-label'])}}
                <select class="form-control select2 brand_id" id="choices-1011" name="brand_id">
                    <option value="" >Select Brand</option>
                    @foreach($companies as $key => $company)
                        <option value="{{$key}}" {{ $key == $announcement->brand_id ? 'selected' : ''}}>{{$company}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('region_id',__('Region'),['class'=>'form-label'])}}
                <div id="region_div">
                    <select class="form-control select" name="region_id" id="region_id" placeholder="Select Region" >
                        <option value="">{{__('Select Region')}}</option>
                        @foreach ($regions as $region)
                        <option value="{{$region->id}}" {{ $region->id == $announcement->region_id ? 'selected' : ''}}>{{$region->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('branch_id',__('Branch'),['class'=>'form-label'])}}
                <div id="branch_div">
                    <select class="form-control select" name="lead_branch" id="branch_id" placeholder="Select Branch">
                        <option value="">{{__('Select Branch')}}</option>
                        @foreach ($branchs as $branch)
                        <option value="{{$branch->id}}" {{ $branch->id == $announcement->branch_id ? 'selected' : ''}}>{{$branch->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('employee_id',__('Employee'),['class'=>'form-label'])}}
                <div id="employee_div">
                    <select class="form-control select" name="lead_assgigned_user[]" id="employee_id" placeholder="Select Employee" >
                        <option value="">{{__('Select Employee')}}</option>
                        @foreach ($employees as $employee)
                        <option value="{{$employee->id}}" {{ $employee->id == $announcement->employee_id ? 'selected' : ''}}>{{$employee->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('start_date',__('Announcement start Date'),['class'=>'form-label'])}}
                {{Form::date('start_date',null,array('class'=>'form-control '))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('end_date',__('Announcement End Date'),['class'=>'form-label'])}}
                {{Form::date('end_date',null,array('class'=>'form-control '))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('description',__('Announcement Description'),['class'=>'form-label'])}}
                {{Form::textarea('description',null,array('class'=>'form-control','placeholder'=>__('Enter Announcement Title')))}}
            </div>
        </div>

    </div>
</div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-dark px-2">
</div>
{{Form::close()}}

<script>
     $(".brand_id").on("change", function(){
        // var id = $(this).val();

        // $.ajax({
        //     type: 'GET',
        //     url: '{{ route('lead_companyemployees') }}',
        //     data: {
        //         id: id  // Add a key for the id parameter
        //     },
        //     success: function(data){
        //         data = JSON.parse(data);

        //         if (data.status === 'success') {
        //             $("#assign_to_div").html(data.employees);
        //             select2();
        //             $("#branch_div").html(data.branches);
        //             select2(); // Assuming this is a function to initialize or update a select2 dropdown
        //         } else {
        //             console.error('Server returned an error:', data.message);
        //         }
        //     },
        //     error: function(xhr, status, error) {
        //         console.error('AJAX request failed:', status, error);
        //     }
        // });


        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-regions') }}',
            data: {
                id: id
            },
            success: function(data){
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


    $(document).on("change", ".region_id", function(){
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branches') }}',
            data: {
                id: id
            },
            success: function(data){
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

    $(document).on("change", ".branch_id", function(){
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branch-users') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#employee_div').html('');
                    $("#employee_div").html(data.html);
                    $('#user_id').attr('name', 'employee_id');
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
