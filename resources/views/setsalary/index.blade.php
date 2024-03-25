@extends('layouts.admin')
@section('page-title')
{{__('Manage Employee Salary')}}
@endsection



@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Employee Salary')}}</li>
@endsection

<style>
    .action-btn {
        display: inline-grid !important;
    }

    .dataTable-bottom,
    .dataTable-top {
        display: none;
    }

    .accordion-button {
        border-bottom-left-radius: 0px !important;
        border-bottoms-right-radius: 0px !important;
    }

    .card {
        box-shadow: none !important;
    }

    .hover-text-color {
        color: #1F2735 !important;
    }
</style>


<style>
    .form-controls,
    .form-btn {
        padding: 4px 1rem !important;
    }

    /* Set custom width for specific table cells */
    .action-btn {
        display: inline-grid !important;
    }

    .dataTable-bottom,
    .dataTable-top {
        display: none;
    }
</style>

<style>
    /* .red-cross {
                                        position: absolute;
                                        top: 5px;
                                        right: 5px;
                                        color: red;
                                    } */
    .boximg {
        margin: auto;
    }

    .dropdown-togglefilter:hover .dropdown-menufil {
        display: block;
    }

    .choices__inner {
        border: 1px solid #ccc !important;
        min-height: auto;
        padding: 4px !important;
    }

    .fil:hover .submenu {
        display: block;
    }

    .fil .submenu {
        display: none;
        position: absolute;
        top: 3%;
        left: 154px;
        width: 100%;
        background-color: #fafafa;
        font-weight: 600;
        list-style-type: none;

    }

    .dropdown-item:hover {
        background-color: white !important;
    }

    .form-control:focus {
        border: none !important;
        outline: none !important;
    }

    .filbar .form-control:focus {
        border: 1px solid rgb(209, 209, 209) !important;
    }
</style>


@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card my-card" style="max-width: 98%;border-radius:0px; min-height: 250px !important;">
            <div class="card-body table-border-style" style="padding: 25px 3px;">


                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                    <div class="col-4">
                        <p class="mb-0 pb-0 ps-1">Set Salaries</p>
                        <div class="dropdown">
                            <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                ALL Salaries
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                            </ul>
                        </div>
                    </div>


                    <div class="col-8 d-flex justify-content-end gap-2 pe-0">
                        <div class="input-group w-25 rounded" style="width:36px; height: 36px; margin-top:10px;">
                            <button class="btn  list-global-search-btn  p-0 pb-2 ">
                                <span class="input-group-text bg-transparent border-0 px-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent p-0 pb-2 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>

                        <button class="btn filter-btn-show p-2 btn-dark" type="button" data-bs-toggle="tooltip" title="{{__('Filter')}}" aria-expanded="false" style="color:white; width:36px; height: 36px; margin-top:10px;">
                            <i class="ti ti-filter" style="font-size:18px"></i>
                        </button>

                    </div>


                    <div class="card-body table-responsive" style="padding: 25px 3px; width:auto;">
                        <table class="table " data-resizable-columns-id="lead-table" id="tfont">
                            <thead>
                                <tr>
                                    <th data-resizable-columns-id="employeeID">{{ __('EMPLOYEE ID') }}</th>
                                    <th data-resizable-columns-id="name">{{ __('Name') }}</th>
                                    <th data-resizable-columns-id="payrolltype">{{ __('PAYROLL TYPE') }}</th>
                                    <th data-resizable-columns-id="salary">{{ __('SALARY') }}</th>
                                    <th data-resizable-columns-id="netsalary">{{ __('NET SALARY') }}</th>
                                    <th data-resizable-columns-id="action">{{ __('ACTION') }}</th>
                                </tr>
                            </thead>


                            <tbody class="leads-list-tbody leads-list-div">

                                @foreach ($employees as $employee)
                                <tr>
                                    <td class="Id">
                                        <a href="{{route('setsalary.show',$employee->id)}}" class="btn btn-outline-dark" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                            {{ \Auth::user()->employeeIdFormat($employee->employee_id) }}
                                        </a>
                                    </td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->salary_type() }}</td>
                                    <td>{{ \Auth::user()->priceFormat($employee->salary) }}</td>
                                    <td>{{ !empty($employee->get_net_salary()) ?\Auth::user()->priceFormat($employee->get_net_salary()):'' }}</td>
                                    <td>
   <div class="action-btn bg-dark ms-2" style="color:white; width:36px; height: 36px; margin-top:10px;">
    <a href="{{ route('setsalary.show', $employee->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{ __('Set Salary') }}" data-original-title="{{ __('View') }}">
        <i class="ti ti-eye text-white" style="font-size: 18px;"></i>
    </a>
</div>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>


                        <div class="pagination_div">
                            @if ($total_records > 0)
                            @include('layouts.pagination', [
                            'total_pages' => $total_records,
                            ])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection