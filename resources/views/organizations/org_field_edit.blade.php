<div class="d-flex align-items-baseline p-1" style="background-color: #f6f6f6;border:1px solid rgb(199, 199, 199)">
    <div class="input-group" style="border-color: rgb(44, 128, 255)">
        @if($name == 'type')
        <select name="{{$name}}" id=""  class="form form-control {{$name}}" style="border: 0px;box-shadow:none;border-raduis:0px;padding:4px !important;">
            @foreach($types as $key => $type)
            <option value="{{$type}}" {{ $org->type == $type ? 'selected' : '' }}>{{$type}}</option>
            @endforeach
        </select>
        <span class="input-group-text border-0 bg-transparent">
            <i class="ti ti-wand"></i>
        </span>
        @elseif($name == 'description')
          <textarea name="{{$name}}" id="" cols="30" rows="10" class="form form-control {{$name}} bg-transparent" style="border: 0px;box-shadow:none;border-raduis:0px;padding:4px !important;">{{$org->description}}</textarea>
        @else
        <input type="text" name="{{$name}}" class="form form-control {{$name}} bg-transparent" value="{{$org->$name}}" style="border: 0px;box-shadow:none;border-raduis:0px;padding:4px !important;">
        <span class="input-group-text border-0 bg-transparent">
            <i class="ti ti-wand"></i>
        </span>
        @endif
    </div>
    <div class="d-flex align-items-end">
        <button class="btn btn-sm btn-primary mx-2 edit-btn-data" data-name="{{$name}}" style="padding: 10px;"><i class="ti ti-pencil"></i></button>
        <button class="btn btn-sm btn-secondary edit-lead-remove" data-name="{{$name}}" style="padding: 10px;"><i class="ti ti-minus"></i></button>
    </div>
</div> 