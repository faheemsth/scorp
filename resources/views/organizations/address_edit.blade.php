<div class="form-floating">
    <textarea class="form-control billing_street" placeholder="Leave a comment here" id="floatingTextarea" name="billing_street">{{$org->billing_street}}</textarea>
</div>
<div class="row">
    <div class="col-6 col-form mt-3">
        <input type="text" class="form-control billing_city" id="formGroupExampleInput" placeholder="City" name="billing_city" value="{{$org->billing_city}}">
    </div>
    <div class="col-6 col-form mt-3">
        <input type="text" class="form-control billing_state" id="formGroupExampleInput" placeholder="State/Province" name="billing_city" value="{{$org->billing_city}}">
    </div>
    <div class="col-6 col-form mt-3">
        <input type="text" class="form-control billing_postal_code" id="formGroupExampleInput" placeholder="Postel Code" name="billing_postal_code" value="{{$org->billing_postal_code}}">
    </div>
    <div class="col-6 col-form mt-3">
        <select class="form-select org_country" name="org_country">
            <option>Country...</option>
            @foreach($countries as $con)
            <option value="{{$con}}" <?= $con == $org->billing_country ? 'selected' : '' ?>>{{$con}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="d-flex align-items-end">
    <button class="btn btn-sm btn-primary mx-2 edit-btn-save-address" data-name="name" style="padding: 10px;"><i class="ti ti-pencil"></i></button>
    <button class="btn btn-sm btn-secondary remove-btn-save-address" data-name="name" style="padding: 10px;"><i class="ti ti-minus"></i></button>
</div>