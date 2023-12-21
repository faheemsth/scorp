@extends('layouts.admin')

@section('page-title')
{{__('Company Permission')}}
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
                    company_permission : company_permission,
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
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Company Permission')}}</li>
@endsection


@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-1" style="overflow-x: auto;">
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table" width="100%">
                        <thead>
                            <tr>
                                <th>Project Manager/Branch</th>
                                @foreach($companies as $company)
                                    <th>{{$company->name}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $emp)
                                <tr scope="row">
                                    <td>{{$emp->name}}</td>
                                    @foreach($companies as $comp)
                                        <?php  $permitted_companies = $comp->companyPermissions->pluck('permitted_company_id'); ?>
                                        <td>
                                            <input type="checkbox" class="company-permission-checkbox"
                                                <?= $emp->id == $comp->id ? 'checked disabled' :  (isset($permission_arr[$emp->id][$comp->id]) && $permission_arr[$emp->id][$comp->id] == 'true' ? 'checked' : '') ?>
                                                id="company-permission-checkbox" data-for-company="{{$emp->id}}" data-permission-company="{{$comp->id}}">
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