<div class="">
    <label for="">Lead Assigned to</label>
    <select name="assigned_to" id="assigned_to" class="form form-control">
        <option value="">Select user</option>
        <?php foreach($users as $key => $user) { ?>
            <option value="<?= $key?>"> <?= $user?> </option>
        <?php } ?>
    </select>
</div>


<div class="row mt-3">
    <div class="col-md-3"><h4>EXCEL FILE Column</h4></div>
    <div class="col-md-3"><h4>Leads Columns</h4></div>
    <div class="col-md-3"><h4>EXCEL FILE Column</h4></div>
    <div class="col-md-3"><h4>Leads Columns</h4></div>


    <div class="row">
        <?php foreach($first_row as $key => $row){ ?>
                <div class="col-md-3 mt-3"><label for=""><?=  $row ?></label></div>
                 <div class="col-md-3 mt-3">
                 <select name="columns[<?=$row?>]" id="" data-id="<?= $key ?>" class="form form-control lead-columns">
                 <option value="">Select Column</option>
                 <option value="name">Name</option>
                 <option value="email">Email</option>
                 <option value="phone">Phone</option>
                 <option value="subject">Subject</option>
                 <option value="products">Products</option>
                 <option value="sources">Sources</option>
                 <option value="notes">Notes</option>
                 <option value="labels">Label</option>
                 <option value="street">Address</option>
                </select>
                 </div>
        <?php } ?>
    </div>
</div>



