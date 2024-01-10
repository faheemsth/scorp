<div class="row">
    <div class="col-6">
        <label for="">Brand</label>
        <select name="brand_id" id="brand_id" class="form form-control brand_id">
            <option value="">Select Brand</option>
            <?php foreach($companies as $key => $company) { ?>
                <option value="<?= $key?>"> <?= $company?> </option>
            <?php } ?>
        </select>
    </div>
    <div class="col-6">
        <label for="">Region</label>
        <div id="region_div">
            <select name="region_id" id="region_id" class="form form-control region_id">
                <option value="">Select Region</option>
               
            </select>
        </div>
    </div>
    <div class="col-6">
        <label for="">Branch</label>
        <div id="branch_div">
            <select name="branch_id" id="branch_id" class="form form-control">
                <option value="">Select Branch</option>
               
            </select>
        </div>
    </div>
    <div class="col-6">
        <label for="">Lead Assigned to</label>
        <div id="assign_to_div">
            <select name="assigned_to" id="assigned_to" class="form form-control">
                <option value="">Select user</option>
                <?php foreach($users as $key => $user) { ?>
                    <option value="<?= $key?>"> <?= $user?> </option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>


<div class="row mt-3">
    <div class="col-md-3"><h4>FILE Column</h4></div>
    <div class="col-md-3"><h4>Leads Columns</h4></div>
    <div class="col-md-3"><h4>FILE Column</h4></div>
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

<script>

    $(".brand_id").on("change", function(){

        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-regions') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#region_div').html('');
                    $("#region_div").html(data.html);
                    select2();                       
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });


    $(document).on("change", ".region_id", function(){
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branches') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#branch_div').html('');
                    $("#branch_div").html(data.html);
                    select2();                       
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });

    $(document).on("change", ".branch_id", function(){
        var id = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route('filter-branch-users') }}',
            data: {
                id: id
            },
            success: function(data){
                data = JSON.parse(data);

                if (data.status === 'success') {
                    $('#assign_to_div').html('');
                    $("#assign_to_div").html(data.html);
                    select2();                       
                } else {
                    console.error('Server returned an error:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });
</script>



