<div class="form-floating">
    <textarea class="form-control lead_street" placeholder="Leave a comment here" id="floatingTextarea" name="lead_street">{{$lead->street}}</textarea>
    <label for="floatingTextarea">Street</label>
</div>
<div class="row">
    <div class="col-6 col-form">
        <input type="text" class="form-control lead_city" id="formGroupExampleInput" placeholder="City" name="lead_city" value="{{$lead->city}}">
    </div>
    <div class="col-6 col-form">
        <input type="text" class="form-control lead_state" id="formGroupExampleInput" placeholder="State/Province" name="lead_state" value="{{$lead->state}}">
    </div>
    <div class="col-6 col-form">
        <input type="text" class="form-control lead_postal_code" id="formGroupExampleInput" placeholder="Postel Code" name="lead_postal_code" value="{{$lead->postal_code}}">
    </div>
    <div class="col-6 col-form">
        <select class="form-select lead_country" name="lead_country">
            <option>Country...</option>
            @foreach($countries as $con)
            <option value="{{$con}}" <?= $con == $lead->country ? 'selected' : '' ?>>{{$con}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="d-flex align-items-end">
    <button class="btn btn-sm btn-primary mx-2 edit-btn-save-address" data-name="name" style="padding: 10px;"><i class="ti ti-pencil"></i></button>
    <button class="btn btn-sm btn-secondary remove-btn-save-address" data-name="name" style="padding: 10px;"><i class="ti ti-minus"></i></button>
</div>