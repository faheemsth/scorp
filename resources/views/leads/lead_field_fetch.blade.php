<div class="d-flex align-items-baseline edit-input-field-div">
    <div class="input-group border-0 {{$name}}">

        @if($name == 'organization_id')
       <a href=" {{$organizations[$lead->organization_id]}}" style="color: blue;font-size:12px;text-decoration: none;" target="_blank> {{$organizations[$lead->organization_id]}}</a>

        @elseif($name == 'sources')
        <a href="{{$sources[$lead->sources]}}" style="color: blue;font-size:12px;text-decoration: none;"  target="_blank>
        {{$sources[$lead->sources]}}
        </a>
        @else
        <a href="{{ $lead->$name }}" style="color: blue;font-size:12px;text-decoration: none;" target="_blank">{{ $lead->$name }}</a>
        @endif
    </div>
    <div class="edit-btn-div">
        <button class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input" name="{{$name}}"><i class="ti ti-pencil"></i></button>
    </div>
</div>
