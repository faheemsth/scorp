@php
$address = [];
if(!empty($lead->street))
$address[] =  $lead->street;

if(!empty($lead->city))
$address[] =  $lead->city;

if(!empty($lead->state))
$address[] =  $lead->state;

if(!empty($lead->postal_code))
$address[] =  $lead->postal_code;

if(!empty($lead->country))
$address[] =  $lead->country;

@endphp



<div class="d-flex align-items-baseline edit-input-field-div">
    <div class="input-group border-0 d-flex align-items-baseline">
        <span class="lead-address-span">{{ implode(', ', $address) }}</span>
    </div>
    <div class="edit-btn-div">
        <button class="btn btn-sm btn-secondary edit-btn-address rounded-0 btn-effect-none "><i class="ti ti-pencil"></i></button>
    </div>
</div>