@extends('layouts.admin')

@section('page-title')
{{ __('Payslip') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('payslip') }}</li>
@endsection
<style>
.Generatebutton {
    display: inline-block;
    padding: 0.5em 1em;
    border: none;
    border-radius: 0.3em;
    text-decoration: none;
    font-size: 1em;
    background-color: #313949; /* blue color */
    color: #ffffff; /* white color */
}

.Generatebutton {
    background-color: #313949;
    /* blue color */
    color: #ffffff;
    /* white color */
}


.bulkbutton {
    display: inline-block;
    padding: 0.5em 1em;
    border: none;
    border-radius: 0.3em;
    text-decoration: none;
    font-size: 1em;
    cursor: pointer;
}

.bulkbutton {
    background-color: #313949;
    /* blue color */
    color: #ffffff;
    /* white color */
}
.hovercol:hover{
    background-color:#313949 !important;
    color:white !important;
}
.hovercoli{
    background-color:#313949 !important;
    color:white !important;
}
</style>

@section('content')
<div class="row">
    <div class="col-xl-12" style="margin-left: 10px;">
        <div class="card my-card" style="max-width: 100%;border-radius:0px; ">
            <div class="card-body table-border-style" style="padding: 25px 3px;">


                {{ Form::open(['route' => ['payslip.store'], 'method' => 'POST', 'id' => 'payslip_form']) }}
                <div class="d-flex align-items-center justify-content-end">

                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                        <div class="btn-box">
                            {{ Form::label('month', __('Select Month'), ['class' => 'form-label']) }}
                            {{ Form::select('month', $month, null, ['class' => 'form-control select', 'id' => 'month']) }}

                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                        <div class="btn-box">
                            {{ Form::label('year', __('Select Year'), ['class' => 'form-label']) }}
                            {{ Form::select('year', $year, null, ['class' => 'form-control select']) }}

                        </div>
                    </div>
                    <div class="col-auto float-end ms-2 mt-4">
                        <a href="javascript:void(0)" class="btn hovercoli"
                            onclick="document.getElementById('payslip_form').submit(); return false;"
                             title="{{ __('payslip') }}"
                            data-original-title="{{ __('payslip') }}">
                            {{ __('Generate Payslip') }}
                        </a>

                    
                        <!-- style="background-color: #313949; color: white;"; -->

                    </div>
                    
                </div>

                {{ Form::close() }}


            </div>
        </div>
    </div>
</div>





<!---------------------------------- My Code Start ---------------------------------->
<div class="row">
    <div class="col-xl-12" style="margin-left: 10px;">
        <div class="card my-card" style="max-width: 100%;border-radius:0px; min-height: 250px !important; ">
            <div class="card-body table-border-style" style="padding: 25px 3px;">
                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">


                    <div class="card-header" style="margin-left: 17px; width: 97%;">
                        <form>
                            {{-- <div class="d-flex justify-content-between w-100"> --}}
                            <div class="d-flex align-items-center justify-content-start">
                                <h5>{{ __('Find Employee Payslip') }}</h5>
                            </div>


                            <div class="d-flex align-items-center justify-content-end">

                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                    <div class="btn-box">

                                        <select class="form-control month_date " name="year" tabindex="-1"
                                            aria-hidden="true">
                                            <option value="--">--</option>
                                            @foreach($month as $k=>$mon)

                                            @php
                                            $selected = ((date('m')-1) == $k) ? 'selected' :'';
                                            @endphp
                                            <option value="{{$k}}" {{ $selected }}>{{$mon}}</option>
                                            @endforeach


                                        </select>


                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                    <div class="btn-box">

                                        {{ Form::select('year', $year, null, ['class' => 'form-control year_date ']) }}

                                    </div>
                                </div>
                                <div class="col-auto float-end ">
                                    @can('Create Pay Slip')
                                    <input type="button" value="{{ __('Bulk Payment') }}" class="bulkbutton"
                                        id="bulk_payment">
                                    @endcan


                                </div>
                            </div>
                        </form>

                    </div>

                    <div class="row">
                        <div class="col-4 py-3" style="margin-left: 7px;">
                            <p class="mb-0 pb-0 ps-1">Set Payslip</p>
                            <div class="dropdown">
                                <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    ALL Payslip
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                </ul>
                            </div>
                        </div>


                        <div class="col-7 d-flex justify-content-end gap-2 pe-0 py-3" style="margin-left: 99px;">
                            <div class="input-group w-25 rounded" style="width:36px; height: 36px; margin-top:10px;">
                                <button class="btn  list-global-search-btn  p-0 pb-2 ">
                                    <span class="input-group-text bg-transparent border-0 px-1" id="basic-addon1">
                                        <i class="ti ti-search" style="font-size: 18px"></i>
                                    </span>
                                </button>
                                <input type="Search"
                                    class="form-control border-0 bg-transparent p-0 pb-2 list-global-search"
                                    placeholder="Search this list..." aria-label="Username"
                                    aria-describedby="basic-addon1">
                            </div>

                            <button class="btn filter-btn-show p-2 btn-dark" type="button" data-bs-toggle="tooltip"
                                title="{{__('Filter')}}" aria-expanded="false"
                                style="color:white; width:36px; height: 36px; margin-top:10px;">
                                <i class="ti ti-filter" style="font-size:18px"></i>
                            </button>

                        </div>
                    </div>


                    <div class="card-body table-responsive" style="width:auto;">
                        <table class="table " data-resizable-columns-id="lead-table" id="tfont">

                            <thead>
                                <tr>
                                    <th data-resizable-columns-id="employeeID">{{ __('EMPLOYEE ID') }}</th>
                                    <th data-resizable-columns-id="name">{{ __('NAME') }}</th>
                                    <th data-resizable-columns-id="payrolltype">{{ __('PAYROLL TYPE') }}</th>
                                    <th data-resizable-columns-id="salary">{{ __('SALARY') }}</th>
                                    <th data-resizable-columns-id="netsalary">{{ __('NET SALARY') }}</th>
                                    <th data-resizable-columns-id="action">{{ __('ACTION') }}</th>
                                </tr>
                            </thead>

                            <tbody class="">
                                @foreach ($employees as $employee)
                                <tr>
                                <td class="Id">
                                        <a href="{{route('setsalary.show',$employee->id)}}" class="btn hovercol"
                                            data-toggle="tooltip" data-original-title="{{__('View')}}">
                                            {{ \Auth::user()->employeeIdFormat($employee->employee_id) }}
                                        </a>
                                    </td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->salary_type() }}</td>
                                    <td>{{ \Auth::user()->priceFormat($employee->salary) }}</td>
                                    <td>{{ !empty($employee->get_net_salary()) ?\Auth::user()->priceFormat($employee->get_net_salary()):'' }}
                                    </td>
                                    <td>
                                        <div class="action-btn bg-dark ms-2"
                                            style="color:white; width:36px; height: 36px; margin-top:10px;">
                                            <a href="{{ route('setsalary.show', $employee->id) }}"
                                                class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                title="{{ __('Set Salary') }}" data-original-title="{{ __('View') }}">
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


<!----------------------------------- My Code End ---------------------------------->

<!-- [ basic-table ] end -->
@endsection

@push('script-page')
<script>
$(document).ready(function() {
    callback();

    function callback() {
        var month = $(".month_date").val();
        var year = $(".year_date").val();

        if (month == '') {
            month = '{{date('
            m ', strtotime('
            last month '))}}';
            year = '{{date('
            Y ')}}';
        }

        var datePicker = year + '-' + month;

        $.ajax({
            url: '{{ route('payslip.search_json')}}',
            type: 'POST',
            data: {
                "datePicker": datePicker,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {


                var datatable_data = {
                    data: data
                };

                function renderstatus(data, cell, row) {
                    if (data == 'Paid')
                        return '<div class="badge bg-success p-2 px-3 rounded"><a href="#" class="text-white">' +
                            data + '</a></div>';
                    else
                        return '<div class="badge bg-danger p-2 px-3 rounded"><a href="#" class="text-white">' +
                            data + '</a></div>';
                }

                function renderButton(data, cell, row) {

                    var $div = $(row);
                    employee_id = $div.find('td:eq(0)').text();
                    status = $div.find('td:eq(6)').text();

                    var month = $(".month_date").val();
                    var year = $(".year_date").val();
                    var id = employee_id;
                    var payslip_id = data;


                    var clickToPaid = '';
                    var payslip = '';
                    var view = '';
                    var edit = '';
                    var deleted = '';
                    var form = '';

                    if (data != 0) {
                        var payslip =
                            '<a href="#" data-url="{{ url('
                        payslip / pdf / ') }}/' + id +
                            '/' + datePicker +
                            '" data-size="md-pdf"  data-ajax-popup="true" class="btn btn-primary" data-title="{{ __('
                        Employee Payslip ') }}">' +
                            '{{ __('
                        Payslip ') }}' + '</a> ';
                    }

                    if (status == "UnPaid" && data != 0) {
                        clickToPaid = '<a href="{{ url('
                        payslip / paysalary / ') }}/' + id +
                            '/' + datePicker + '"  class="view-btn primary-bg btn-sm">' +
                            '{{ __('
                        Click To Paid ') }}' + '</a>  ';
                    }

                    if (data != 0) {
                        view =
                            '<a href="#" data-url="{{ url('
                        payslip / showemployee / ') }}/' +
                            payslip_id +
                            '"  data-ajax-popup="true" class="view-btn gray-bg" data-title="{{ __('
                        View Employee Detail ') }}">' +
                            '{{ __('
                        View ') }}' + '</a>';
                    }

                    if (data != 0 && status == "UnPaid") {
                        edit =
                            '<a href="#" data-url="{{ url('
                        payslip / editemployee / ') }}/' +
                            payslip_id +
                            '"  data-ajax-popup="true" class="view-btn blue-bg" data-title="{{ __('
                        Edit Employee salary ') }}">' +
                            '{{ __('
                        Edit ') }}' + '</a>';
                    }

                    var url = '{{ route('payslip.delete',': id ')}}';
                    url = url.replace(':id', payslip_id);

                    @if(\Auth::user()-> type != 'employee')
                    if (data != 0) {
                        deleted = '<a href="#"  data-url="' + url +
                            '" class="payslip_delete view-btn red-bg" >' +
                            '{{ __('
                        Delete ') }}' + '</a>';
                    }
                    @endif

                    return view + payslip + clickToPaid + edit + deleted + form;
                }

                console.clear();
                var tr = '';
                // <tr><td class="dataTables-empty" colspan="1">No entries found</td></tr>
                if (data.length > 0) {
                    console.log(data);
                    $.each(data, function(indexInArray, valueOfElement) {
                        var status =
                            '<div class="badge bg-danger p-2 px-3 rounded"><a href="#" class="text-white">' +
                            valueOfElement[6] + '</a></div>';
                        if (valueOfElement[6] == 'Paid') {
                            var status =
                                '<div class="badge bg-success p-2 px-3 rounded"><a href="#" class="text-white">' +
                                valueOfElement[6] + '</a></div>';
                        }

                        var id = valueOfElement[0];
                        var employee_id = valueOfElement[1];
                        var payslip_id = valueOfElement[7];

                        if (valueOfElement[7] != 0) {
                            var payslip =
                                '<a href="#" data-url="{{ url('
                            payslip / pdf / ') }}/' +
                                id +
                                '/' + datePicker +
                                '" data-size="lg"  data-ajax-popup="true" class=" btn-sm btn btn-warning" data-title="{{ __('
                            Employee Payslip ') }}">' +
                                '{{ __('
                            Payslip ') }}' + '</a> ';
                        }
                        if (valueOfElement[6] == "UnPaid" && valueOfElement[7] != 0) {
                            var clickToPaid =
                                '<a href="{{ url('
                            payslip / paysalary / ') }}/' + id +
                                '/' + datePicker + '"  class="btn-sm btn btn-primary">' +
                                '{{ __('
                            Click To Paid ') }}' + '</a>  ';
                        } else {
                            var clickToPaid = '';
                        }

                        if (valueOfElement[7] != 0 && valueOfElement[6] == "UnPaid") {
                            var edit =
                                '<a href="#" data-url="{{ url('
                            payslip / editemployee / ') }}/' +
                                payslip_id +
                                '"  data-ajax-popup="true" class="btn-sm btn btn-info" data-title="{{ __('
                            Edit Employee salary ') }}">' +
                                '{{ __('
                            Edit ') }}' + '</a>';
                        } else {
                            var edit = '';
                        }


                        var url = '{{ route('payslip.delete',': id')}}';
                        url = url.replace(':id', payslip_id);

                        @if(\Auth::user()-> type != 'employee')
                        if (valueOfElement[7] != 0) {
                            var deleted = '<a href="#"  data-url="' + url +
                                '" class="payslip_delete view-btn btn btn-danger ms-1 btn-sm"  >' +
                                '{{ __('
                            Delete ') }}' + '</a>';
                        } else {
                            var deleted = '';
                        }
                        @endif
                        var url_employee = valueOfElement['url'];

                        tr +=
                            '<tr> ' +
                            '<td> <a class="btn btn-outline-primary" href="' +
                            url_employee + '">' +
                            valueOfElement[1] + '</a></td> ' +
                            '<td>' + valueOfElement[2] + '</td> ' +
                            '<td>' + valueOfElement[3] + '</td>' +
                            '<td>' + valueOfElement[4] + '</td>' +
                            '<td>' + valueOfElement[5] + '</td>' +
                            '<td>' + status + '</td>' +
                            '<td>' + payslip + clickToPaid + edit + deleted + '</td>' +
                            '</tr>';
                    });
                } else {
                    var colspan = $('#pc-dt-render-column-cells thead tr th').length;
                    var tr = '<tr><td class="dataTables-empty" colspan="' + colspan +
                        '">{{ __('
                    No entries found ') }}</td></tr>';
                }

                $('#pc-dt-render-column-cells tbody').html(tr);
                var table = document.querySelector("#pc-dt-render-column-cells");
                var datatable = new simpleDatatables.DataTable(table);

                // if (data.length > 0) {
                //     var dataTable = new simpleDatatables.DataTable(
                //         "#pc-dt-render-column-cells", {
                //             data: datatable_data,
                //             perPage: 25,
                //             columns: [{
                //                     select: 0,
                //                     hidden: true
                //                 },
                //                 {
                //                     select: 6,
                //                     render: renderstatus
                //                 },
                //                 {
                //                     select: 7,
                //                     render: renderButton
                //                 }
                //             ]
                //         }
                //     );


                //     $('[data-toggle="tooltip"]').tooltip();

                //     if (!(data)) {
                //         show_toastr('error',
                //             'Employee payslip not found ! please generate first.',
                //             'error');
                //     }
                // } else {

                //     var dataTable = new simpleDatatables.DataTable(
                //         "#pc-dt-render-column-cells", {
                //             data: ''
                //         }
                //     );

                // }
                // dataTable.on("datatable.init");


            },
            error: function(data) {

            }

        });

    }

    $(document).on("change", ".month_date,.year_date", function() {
        callback();
    });

    //bulkpayment Click
    $(document).on("click", "#bulk_payment", function() {
        var month = $(".month_date").val();
        var year = $(".year_date").val();
        var datePicker = year + '_' + month;


    });
    $(document).on('click', '#bulk_payment',
        'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]',
        function() {
            var month = $(".month_date").val();
            var year = $(".year_date").val();
            var datePicker = year + '-' + month;

            var title = 'Bulk Payment';
            var size = 'md';
            var url = 'payslip/bulk_pay_create/' + datePicker;

            // return false;

            $("#commonModal .modal-title").html(title);
            $("#commonModal .modal-dialog").addClass('modal-' + size);
            $.ajax({
                url: url,
                success: function(data) {

                    // alert(data);
                    // return false;
                    if (data.length) {
                        $('#commonModal .modal-body').html(data);
                        $("#commonModal").modal('show');
                        // common_bind();
                    } else {
                        show_toastr('error', 'Permission denied.');
                        $("#commonModal").modal('hide');
                    }
                },
                error: function(data) {
                    data = data.responseJSON;
                    show_toastr('error', data.error);
                }
            });
        });

    $(document).on("click", ".payslip_delete", function() {
        var confirmation = confirm("are you sure you want to delete this payslip?");
        var url = $(this).data('url');


        if (confirmation) {
            $.ajax({
                type: "GET",
                url: url,
                dataType: "JSON",
                success: function(data) {
                    console.log(data);


                    // show_toastr(data.status, data.msg, 'data.status');
                    show_toastr('success', 'Payslip Deleted Successfully', 'success');


                    setTimeout(function() {
                        location.reload();
                    }, 800)


                },

            });

        }
    });
});
</script>
@endpush