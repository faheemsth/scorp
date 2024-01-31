@extends('layouts.admin')

@section('page-title')
    {{ __('Company Permission') }}
@endsection
@push('script-page')
    <script>
        $(".company-permission-checkbox").on("change", function() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var company_for = $(this).attr('data-for-company');
            var company_permission = $(this).attr('data-permission-company');
            var active = $(this).prop('checked');


            if (company_for != company_permission) {
                $.ajax({
                    url: '/company-permission-updated',
                    type: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                        company_for: company_for,
                        company_permission: company_permission,
                        active: active
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        show_toastr('success', 'Permission updated successfully.');
                    }
                });
            }
        })
    </script>
@endpush

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('crm.dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Company Permission')}}</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-1" style=" overflow-x: scroll;">
                    <form action="/company-permission" method="GET" class="" id="role_form">
                        <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                            <div class="col-3">
                                <p class="mb-0 pb-0 ps-1">Company Permissions</p>
                                <div class="dropdown">
                                    <button class="All-leads" type="button">
                                        ALL Permissions
                                    </button>
                                </div>
                            </div>
                            <div class="col-9 d-flex justify-content-end gap-2">

                                {{-- <button class="btn filter-btn-show px-2 btn-dark" style="color:white;" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-filter" style="font-size:18px"></i>
                        </button> --}}
                                <select name="role" class="form form-control select2 role_type" style="width: 23%;">
                                 
                                    <option value="Project Director"
                                        <?=!isset($_GET['role']) ||  isset($_GET['role']) && $_GET['role'] == 'Project Director' ? 'selected' : '' ?>>
                                        Project Director</option>
                                    <option value="Project Manager"
                                        <?= isset($_GET['role']) && $_GET['role'] == 'Project Manager' ? 'selected' : '' ?>>
                                        Project Manager</option>
                                </select>
                                <!-- <button class="btn btn-dark" type="submit" data-bs-toggle="tooltip" title="{{__('Submit')}}">Submit</button> -->
                            </div>
                        </div>
                    </form>


                    <div style="">
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
                                                <input type="checkbox" class="company-permission-checkbox"
                                                    <?= $emp->brand_id == $comp->id ? 'checked' : (isset($permission_arr[$emp->id][$comp->id]) && $permission_arr[$emp->id][$comp->id] == 'true' ? 'checked' : '') ?>
                                                    id="company-permission-checkbox" data-for-company="{{ $emp->id }}"
                                                    data-permission-company="{{ $comp->id }}">
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
    $('.role_type').on('change',function(){
        $("#role_form").submit();
    })
</script>
@endpush
