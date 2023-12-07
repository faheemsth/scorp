<div class="d-flex align-items-baseline p-1" style="background-color: #f6f6f6;border:1px solid rgb(199, 199, 199)">
    <div class="input-group" style="border-color: rgb(44, 128, 255)">
        @if($name == 'organization_id')
        <select name="{{$name}}" id=""  class="form form-control {{$name}}" style="border: 0px;box-shadow:none;border-raduis:0px;padding:4px !important;">
            @foreach($organizations as $key => $org)
            <option value="{{$key}}" {{ $deal->organization_id == $key ? 'selected' : '' }}>{{$org}}</option>
            @endforeach
        </select>
        @elseif($name == 'intake_month')
        <select name="{{$name}}" id="" class="form form-control {{$name}}" style="border: 0px;box-shadow:none;border-raduis:0px;padding:4px !important;">
            @foreach($months as $key => $month)
            <option value="{{$key}}" {{ $deal->intake_month == $key ? 'selected' : '' }}>{{$month}}</option>
            @endforeach
        </select>
        @elseif($name == 'intake_year')
        <select name="{{$name}}" id="" class="form form-control {{$name}}" style="border: 0px;box-shadow:none;border-raduis:0px;padding:4px !important;">
            @foreach($years as $key => $year)
            <option value="{{$key}}" {{ $deal->intake_year == $key ? 'selected' : '' }}>{{$year}}</option>
            @endforeach
        </select>
        @elseif($name == 'stage_id')
        <select name="{{$name}}" id="" class="form form-control {{$name}}" style="border: 0px;box-shadow:none;border-raduis:0px;padding:4px !important;">
            @foreach($stages as $key => $stage)
            <option value="{{$key}}" {{ $deal->stage_id == $key ? 'selected' : '' }}>{{$stage}}</option>
            @endforeach
        </select>
        @elseif($name == 'assigned_to')
        <select name="{{$name}}" id="employeee2" class="form form-control {{$name}} select2" style="border: 0px;box-shadow:none;border-raduis:0px;padding:4px !important;">
            @foreach($employees as $key => $employee)
            <option value="{{$key}}">{{$employee}}</option>
            @endforeach
        </select>
        @elseif($name == 'branch_id')
        <select name="{{$name}}" id="branches3" class="form form-control {{$name}} select2" style="border: 0px;box-shadow:none;border-raduis:0px;padding:4px !important;">
            @foreach($branches as $key => $branch)
            <option value="{{$key}}">{{$branch}}</option>
            @endforeach
        </select> 
        @else
        <input type="text" name="{{$name}}" class="form form-control {{$name}} bg-transparent" value="{{$deal->$name}}" style="border: 0px;box-shadow:none;border-raduis:0px;padding:4px !important;">
        @endif

        <span class="input-group-text border-0 bg-transparent">
            <i class="ti ti-wand"></i>
        </span>
    </div>
    <div class="d-flex align-items-end">
        <button class="btn btn-sm btn-primary mx-2 edit-btn-data" data-name="{{$name}}" style="padding: 10px;"><i class="ti ti-pencil"></i></button>
        <button class="btn btn-sm btn-secondary edit-lead-remove" data-name="{{$name}}" style="padding: 10px;"><i class="ti ti-minus"></i></button>
    </div>
</div> 