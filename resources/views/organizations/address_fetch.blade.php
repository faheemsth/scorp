@php
$address = [];
if(!empty($org->billing_street))
$address[] =  $org->billing_street;

if(!empty($org->billing_city))
$address[] =  $org->billing_city;

if(!empty($org->billing_state))
$address[] =  $org->billing_state;

if(!empty($org->billing_postal_code))
$address[] =  $org->billing_postal_code;

if(!empty($org->billing_country))
$address[] =  $org->billing_country;

@endphp



<div class="d-flex align-items-baseline edit-input-field-div">
    <div class="input-group border-0 d-flex align-items-baseline">
        <span class="lead-address-span">{{ implode(', ', $address) }}</span>
    </div>
    <div class="edit-btn-div">
        <button class="btn btn-sm btn-secondary edit-btn-address rounded-0 btn-effect-none "><i class="ti ti-pencil"></i></button>
    </div>
</div>