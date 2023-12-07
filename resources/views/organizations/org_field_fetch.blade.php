<div class="d-flex align-items-baseline edit-input-field-div">
    <div class="input-group border-0 {{$name}}">

        @if($name == 'type')
        {{ $org->type }}
         @else
        {{ $org->$name }}
        @endif
    </div>
    <div class="edit-btn-div">
        <button class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input" name="{{$name}}"><i class="ti ti-pencil"></i></button>
    </div>
</div>