<style>
    table tr td {
        font-size: 14px
    }

    .form-select {
        height: 30px;
        padding: 2px 10px;
        margin: 4px;
    }

    .accordion-button {
        font-size: 12px !important;
    }

    .accordion-item {
        border-radius: 0px;
    }

    .accordion-item:first-of-type .accordion-button {
        border-radius: 0px;
    }

    .accordion-button:focus {
        border: 0px;
        box-shadow: none;
    }

    input {
        margin: 4px;
    }

    .col-form {
        padding: 3px;
    }

    .row {
        padding: 6px
    }
</style>

{{ Form::open(['url' => 'email/marketing/form', 'method' => 'POST', 'id' => 'lead-creating-form']) }}

<div class="modal-body pt-0" style="height: 80vh;">
    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
        <div class="card-body px-4 py-3">
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter your name') }}">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Users') }}</label>
                <select class="form-control select2" id="choice-222" name="type">
                    @foreach ($users_with_roles as $key => $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Tags') }}</label>
                <textarea name="tag" class="form-control" id="" cols="40" rows="5"></textarea>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">{{ __('Template') }}</label>
                <textarea class="form-control" style="height: 120px;" name="email_content" id="description" placeholder="Click here add your Notes Comments..."></textarea>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <button type="submit" class="btn btn-dark">{{ __('Create') }}</button>
</div>

{{ Form::close() }}


<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            height: 150,
        });
    });
</script>
<script>
    $("#lead-creating-form").on("submit", function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $(".new-lead-btn").val('Processing...');
        $(".new-lead-btn").prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{ route('email.marketing.save') }}",
            data: formData,
            success: function(data) {
                if (data.status == 'success') {
                    show_toastr('Success', data.message, 'success');
                    $('#commonModal').modal('hide');
                    $('.leads-list-tbody').prepend(data.html);
                    openSidebar('/email/marketing/show?id=' + data.id);
                    return false;
                } else {
                    show_toastr('error', data.message, 'error');
                    $(".new-lead-btn").val('Create');
                    $('.new-lead-btn').removeAttr('disabled');
                }
            }
        });
    });
</script>
<script>
    window.intlTelInput(document.getElementById('phone'), {
        utilsScript: "{{ asset('js/intel_util.js') }}",
        initialCountry: "pk",
        separateDialCode: true,
        formatOnDisplay: true,
        hiddenInput: "full_number"
    });
</script>
