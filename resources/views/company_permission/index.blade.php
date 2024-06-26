@extends('layouts.admin')

@section('page-title')
{{ __('Company Permission') }}
@endsection

<style>
     .sticktopser {
    position: sticky;
    top: 0;
    z-index: 2;
}

table thead tr th {
    position: sticky;
    top: 0;
    background: #F8F9FD;
    z-index: 1;
}

table tr th:first-child {
    position: sticky;
    left: 0;
    background: #F8F9FD;
    z-index: 3;
}

table tr td:first-child {
    position: sticky;
    left: 0;
    background: white;
    z-index: 2;
}
/*
.table-wrapper {
    overflow-x: auto;
}

::-webkit-scrollbar {
    width: 10px;
} */

</style>




@push('script-page')
<script>
    var checkboxData = [];

    $(".company-permission-checkbox").on("change", function() {
        var company_for = $(this).attr('data-for-company');
        var company_permission = $(this).attr('data-permission-company');
        var active = $(this).prop('checked');

        if (company_for != company_permission) {
            var checkboxObj = {
                company_for: company_for,
                company_permission: company_permission,
                active: active
            };
            checkboxData.push(checkboxObj);

        }
    });





    $("#actions_div").on("click", function() {
        // Iterate over each record in checkboxData
        checkboxData.forEach(function(record, index) {
            // Extract CSRF token for each record
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            // Construct AJAX request for each record
            $.ajax({
                url: '/company-permission-updated',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                data: {
                    company_for: record.company_for,
                    company_permission: record.company_permission,
                    active: record.active,
                    csrf_token: CSRF_TOKEN
                },
                dataType: 'JSON',
                success: function(data) {
                    show_toastr('success', 'Permission updated successfully.');
                    setTimeout(function() {
                        location.reload();
                    }, 1000); // 1000 milliseconds = 1 seconds
                },
            });
        });
    });
</script>

@endpush

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Company Permission') }}</li>
@endsection


@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0" style=" overflow-x: scroll;">
                <form action="/company-permission" method="GET" class="sticktopser" id="role_form">
                    <div class="row align-items-center ps-0 ms-0 pe-4 my-2 ">
                        <div class="col-3">
                            <p class="mb-0 pb-0 ps-1">Company Permissions</p>
                            <div class="dropdown">
                                <button class="All-leads" type="button">
                                    ALL Permissions
                                </button>
                            </div>
                        </div>
                        <div class="col-9 d-flex justify-content-end gap-2">

                            <select name="role" class="form form-control select2 role_type" style="width: 23%;">

                                <option value="Project Director" <?= !isset($_GET['role']) || (isset($_GET['role']) && $_GET['role'] == 'Project Director') ? 'selected' : '' ?>>
                                    Project Director</option>
                                <option value="Project Manager" <?= isset($_GET['role']) && $_GET['role'] == 'Project Manager' ? 'selected' : '' ?>>
                                    Project Manager</option>
                            </select>
                            <a class="btn d-none papu" style="background-color: #313949; color:white;" title="{{ __('Submit') }}" id="actions_div">Submit</a>
                        </div>
                    </div>
                </form>

                <script>
                    $(document).on('change', '.sub-check', function() {
                        var selectedIds = $('.sub-check:checked').map(function() {
                            return this.value;
                        }).get();

                        if (selectedIds.length > 0) {
                            $("#actions_div").removeClass('d-none');
                        } else {
                            $("#actions_div").addClass('d-none');
                        }

                        let commaSeperated = selectedIds.join(",");
                        console.log(commaSeperated)
                        $("#company-permission-checkbox").val(commaSeperated);
                    });
                </script>


                <div style="overflow-x: scroll; overflow-y: scroll; height: 50vh;">
                    <table class="table" width="100%">
                        <thead>
                            <tr>
                                <th>Project Manager/Branch</th>
                                @foreach ($companies as $company)
                                <th>{{ $company->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $emp)
                            <tr scope="row">
                                <td>{{ $emp->name }}</td>
                                @foreach ($companies as $comp)
                                <?php $permitted_companies = $comp->companyPermissions->pluck('permitted_company_id'); ?>
                                <td>
                                    <input type="checkbox" class="company-permission-checkbox sub-check" <?= $emp->brand_id == $comp->id ? 'checked' : (isset($permission_arr[$emp->id][$comp->id]) && $permission_arr[$emp->id][$comp->id] == 'true' ? 'checked' : '') ?> id="company-permission-checkbox" data-for-company="{{ $emp->id }}" data-permission-company="{{ $comp->id }}">
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    $('.role_type').on('change', function() {
        $("#role_form").submit();
    })
</script>
@endpush