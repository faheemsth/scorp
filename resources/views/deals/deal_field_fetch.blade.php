<div class="d-flex align-items-baseline edit-input-field-div">
    <div class="input-group border-0 {{$name}}">

        @if($name == 'organization_id')
        {{$organizations[$lead->organization_id]}}

        @elseif($name == 'sources')
        {{$sources[$lead->organization_id]}}
        @else
        {{ $lead->$name }}
        @endif



        @if($name == 'organization_id')
        {{$organizations[$deal->organization_id]}}
        @elseif($name == 'intake_month')
        {{$months[$deal->intake_month]}}
        @elseif($name == 'intake_year')
        {{$years[$deal->intake_year]}}
        @elseif($name == 'stage_id')
        {{$stages[$deal->stage_id]}}
        @elseif($name == 'assigned_to')
        
        @elseif($name == 'branch_id')
        {{ $branches[$deal->branch_id] }}
        @else
        {{ $deal->$name }}
        @endif


    </div>
    <div class="edit-btn-div">
        <button class="btn btn-sm btn-secondary rounded-0 btn-effect-none edit-input" name="{{$name}}"><i class="ti ti-pencil"></i></button>
    </div>
</div>