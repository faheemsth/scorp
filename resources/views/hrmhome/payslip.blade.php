@extends('layouts.admin')
@section('page-title')
    {{ __('Payslip') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Payslip') }}</li>
@endsection
@section('content')
    <style>
        .table td,
        .table th {
            font-size: 14px;
        }
    </style>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .list-group-item.active {
            background-color: #007bff;
            border-color: #007bff;
        }

        .list-group-item:hover {
            background-color: #f1f1f1;
        }

        .sticky-top {
            top: 30px;
        }
    </style>
    <div class="row">
        <div class="col-3">
            @include('hrmhome.hrm_setup_routes')
        </div>
        <div class="col-6">


            <div class="card me-3">
                <div class="card-header d-flex justify-content-between align-items-baseline">
                    <h4>Payslip Information</h4>
                </div>
                <div class="card-body px-2">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                            aria-labelledby="pills-details-tab">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <!-- Open Accordion Item -->

                                @foreach ($payslips as $payslip)
                                    @php
                                        $employee = App\Models\Employee::find($payslip->employee_id);
                                    @endphp
                                    <div class="card content my-2 bg-white">
                                        <div class="card-header">
                                            {{__('Salary Slip')}} :</strong> {{ $payslip->salary_month}}
                                        </div>


                                        <div class="card-body px-2">
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade active show" id="pills-details" role="tabpanel"
                                                    aria-labelledby="pills-details-tab">
                                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header"
                                                                id="panelsStayOpen-headingkeyone{{ $payslip->id }}">
                                                                <button class="accordion-button p-2" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#panelsStayOpen-collapsekeyone{{ $payslip->id }}">
                                                                    Details
                                                                </button>
                                                            </h2>

                                                            <div id="panelsStayOpen-collapsekeyone{{ $payslip->id }}"
                                                                class="accordion-collapse collapse"
                                                                aria-labelledby="panelsStayOpen-headingkeyone{{ $payslip->id }}">
                                                                <div class="accordion-body">

                                                                    <div class="table-responsive mt-1"
                                                                        style="margin-left: 10px;">










                                                                        @php
                                                                            $logo = \App\Models\Utility::get_file(
                                                                                'uploads/logo',
                                                                            );
                                                                            $company_logo = Utility::getValByName(
                                                                                'company_logo',
                                                                            );
                                                                        @endphp



                                                                        <div class="card bg-none card-box">
                                                                            <div class="card-body">
                                                                                <div class="invoice-number">
                                                                                    <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
                                                                                        width="120px;">
                                                                                </div>
                                                                                <div class="text-end">
                                                                                    <a href="#"
                                                                                        class="btn btn-sm btn-primary"
                                                                                        onclick="saveAsPDF()"><span
                                                                                            class="ti ti-download"></span></a>
                                                                                    <a title="Mail Send"
                                                                                        href="{{ route('payslip.send', [$employee->id, $payslip->salary_month]) }}"
                                                                                        class="btn btn-sm btn-warning"><span
                                                                                            class="ti ti-send"></span></a>
                                                                                </div>
                                                                                <div class="invoice" id="printableArea">
                                                                                    <div class="invoice-print">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-12">
                                                                                                <div class="invoice-title">
                                                                                                    {{--                        <h6 class="mb-3">{{__('Payslip')}}</h6> --}}

                                                                                                </div>
                                                                                                <hr>
                                                                                                <div class="row text-sm">
                                                                                                    <div class="col-md-6">
                                                                                                        <address>
                                                                                                            <strong>{{ __('Name') }}
                                                                                                                :</strong>
                                                                                                            {{ $employee->name }}<br>
                                                                                                            <strong>{{ __('Position') }}
                                                                                                                :</strong>
                                                                                                            {{ __('Employee') }}<br>
                                                                                                            <strong>{{ __('Salary Date') }}
                                                                                                                :</strong>
                                                                                                            {{ \Auth::user()->dateFormat($payslip->created_at) }}<br>
                                                                                                        </address>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="col-md-6 text-end">
                                                                                                        <address>
                                                                                                            <strong>{{ \Utility::getValByName('company_name') }}
                                                                                                            </strong><br>
                                                                                                            {{ \Utility::getValByName('company_address') }}
                                                                                                            ,
                                                                                                            {{ \Utility::getValByName('company_city') }},<br>
                                                                                                            {{ \Utility::getValByName('company_state') }}-{{ \Utility::getValByName('company_zipcode') }}<br>
                                                                                                            <strong>{{ __('Salary Slip') }}
                                                                                                                :</strong>
                                                                                                            {{ $payslip->salary_month }}<br>
                                                                                                        </address>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row mt-2">
                                                                                            <div class="col-md-12">
                                                                                                <div
                                                                                                    class="card-body table-border-style">

                                                                                                    <div
                                                                                                        class="table-responsive">
                                                                                                        <table
                                                                                                            class="table table-md">
                                                                                                            <tbody>
                                                                                                                <tr
                                                                                                                    class="font-weight-bold">
                                                                                                                    <th>{{ __('Earning') }}
                                                                                                                    </th>
                                                                                                                    <th>{{ __('Title') }}
                                                                                                                    </th>
                                                                                                                    <th>{{ __('Type') }}
                                                                                                                    </th>
                                                                                                                    <th
                                                                                                                        class="text-end">
                                                                                                                        {{ __('Amount') }}
                                                                                                                    </th>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>{{ __('Basic Salary') }}
                                                                                                                    </td>
                                                                                                                    <td>-
                                                                                                                    </td>
                                                                                                                    <td>-
                                                                                                                    </td>
                                                                                                                    <td
                                                                                                                        class="text-end">
                                                                                                                        {{ \Auth::user()->priceFormat($payslip->basic_salary) }}
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                @foreach ($payslipDetail['earning']['allowance'] as $allowance)
                                                                                                                    @php
                                                                                                                        $employess = \App\Models\Employee::find(
                                                                                                                            $allowance->employee_id,
                                                                                                                        );
                                                                                                                        $empdallow =
                                                                                                                            ($allowance->amount *
                                                                                                                                $employess->salary) /
                                                                                                                            100;
                                                                                                                    @endphp
                                                                                                                    <tr>
                                                                                                                        <td>{{ __('Allowance') }}
                                                                                                                        </td>
                                                                                                                        <td>{{ $allowance->title }}
                                                                                                                        </td>
                                                                                                                        <td>{{ ucfirst($allowance->type) }}
                                                                                                                        </td>
                                                                                                                        @if ($allowance->type != 'percentage')
                                                                                                                            <td
                                                                                                                                class="text-end">
                                                                                                                                {{ \Auth::user()->priceFormat($allowance->amount) }}
                                                                                                                            </td>
                                                                                                                        @else
                                                                                                                            <td
                                                                                                                                class="text-end">
                                                                                                                                {{ $allowance->amount }}%
                                                                                                                                (${{ $empdallow }})
                                                                                                                            </td>
                                                                                                                        @endif
                                                                                                                    </tr>
                                                                                                                @endforeach
                                                                                                                @foreach ($payslipDetail['earning']['commission'] as $commission)
                                                                                                                    @php
                                                                                                                        $employess = \App\Models\Employee::find(
                                                                                                                            $commission->employee_id,
                                                                                                                        );
                                                                                                                        $empcomm =
                                                                                                                            ($commission->amount *
                                                                                                                                $employess->salary) /
                                                                                                                            100;
                                                                                                                    @endphp
                                                                                                                    <tr>
                                                                                                                        <td>{{ __('Commission') }}
                                                                                                                        </td>
                                                                                                                        <td>{{ $commission->title }}
                                                                                                                        </td>
                                                                                                                        <td>{{ ucfirst($commission->type) }}
                                                                                                                        </td>
                                                                                                                        @if ($commission->type != 'percentage')
                                                                                                                            <td
                                                                                                                                class="text-end">
                                                                                                                                {{ \Auth::user()->priceFormat($commission->amount) }}
                                                                                                                            </td>
                                                                                                                        @else
                                                                                                                            <td
                                                                                                                                class="text-end">
                                                                                                                                {{ $commission->amount }}%
                                                                                                                                (${{ $empcomm }})
                                                                                                                            </td>
                                                                                                                        @endif

                                                                                                                    </tr>
                                                                                                                @endforeach
                                                                                                                @foreach ($payslipDetail['earning']['otherPayment'] as $otherPayment)
                                                                                                                    @php
                                                                                                                        $employess = \App\Models\Employee::find(
                                                                                                                            $otherPayment->employee_id,
                                                                                                                        );
                                                                                                                        $emppayment =
                                                                                                                            ($otherPayment->amount *
                                                                                                                                $employess->salary) /
                                                                                                                            100;
                                                                                                                    @endphp
                                                                                                                    <tr>
                                                                                                                        <td>{{ __('Other Payment') }}
                                                                                                                        </td>
                                                                                                                        <td>{{ $otherPayment->title }}
                                                                                                                        </td>
                                                                                                                        <td>{{ ucfirst($otherPayment->type) }}
                                                                                                                        </td>
                                                                                                                        @if ($otherPayment->type != 'percentage')
                                                                                                                            <td
                                                                                                                                class="text-end">
                                                                                                                                {{ \Auth::user()->priceFormat($otherPayment->amount) }}
                                                                                                                            </td>
                                                                                                                        @else
                                                                                                                            <td
                                                                                                                                class="text-end">
                                                                                                                                {{ $otherPayment->amount }}%
                                                                                                                                (${{ $emppayment }})
                                                                                                                            </td>
                                                                                                                        @endif

                                                                                                                    </tr>
                                                                                                                @endforeach
                                                                                                                @foreach ($payslipDetail['earning']['overTime'] as $overTime)
                                                                                                                    <tr>
                                                                                                                        <td>{{ __('OverTime') }}
                                                                                                                        </td>
                                                                                                                        <td>{{ $overTime->title }}
                                                                                                                        </td>
                                                                                                                        <td>-
                                                                                                                        </td>
                                                                                                                        <td
                                                                                                                            class="text-end">
                                                                                                                            {{ \Auth::user()->priceFormat($overTime->amount) }}
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                @endforeach

                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="card-body table-border-style">

                                                                                                    <div
                                                                                                        class="table-responsive">
                                                                                                        <table
                                                                                                            class="table table-striped table-hover table-md">
                                                                                                            <tbody>
                                                                                                                <tr
                                                                                                                    class="font-weight-bold">
                                                                                                                    <th>{{ __('Deduction') }}
                                                                                                                    </th>
                                                                                                                    <th>{{ __('Title') }}
                                                                                                                    </th>
                                                                                                                    <th>{{ __('type') }}
                                                                                                                    </th>
                                                                                                                    <th
                                                                                                                        class="text-end">
                                                                                                                        {{ __('Amount') }}
                                                                                                                    </th>
                                                                                                                </tr>


                                                                                                                @foreach ($payslipDetail['deduction']['loan'] as $loan)
                                                                                                                    @php
                                                                                                                        $employess = \App\Models\Employee::find(
                                                                                                                            $loan->employee_id,
                                                                                                                        );
                                                                                                                        $emploan =
                                                                                                                            ($loan->amount *
                                                                                                                                $employess->salary) /
                                                                                                                            100;
                                                                                                                    @endphp
                                                                                                                    <tr>
                                                                                                                        <td>{{ __('Loan') }}
                                                                                                                        </td>
                                                                                                                        <td>{{ $loan->title }}
                                                                                                                        </td>
                                                                                                                        <td>{{ ucfirst($loan->type) }}
                                                                                                                        </td>
                                                                                                                        @if ($loan->type != 'percentage')
                                                                                                                            <td
                                                                                                                                class="text-end">
                                                                                                                                {{ \Auth::user()->priceFormat($loan->amount) }}
                                                                                                                            </td>
                                                                                                                        @else
                                                                                                                            <td
                                                                                                                                class="text-end">
                                                                                                                                {{ $loan->amount }}%
                                                                                                                                (${{ $emploan }})
                                                                                                                            </td>
                                                                                                                        @endif

                                                                                                                    </tr>
                                                                                                                @endforeach
                                                                                                                @foreach ($payslipDetail['deduction']['deduction'] as $deduction)
                                                                                                                    @php
                                                                                                                        $employess = \App\Models\Employee::find(
                                                                                                                            $deduction->employee_id,
                                                                                                                        );
                                                                                                                        $empdeduction =
                                                                                                                            ($deduction->amount *
                                                                                                                                $employess->salary) /
                                                                                                                            100;
                                                                                                                    @endphp
                                                                                                                    <tr>
                                                                                                                        <td>{{ __('Saturation Deduction') }}
                                                                                                                        </td>
                                                                                                                        <td>{{ $deduction->title }}
                                                                                                                        </td>
                                                                                                                        <td>{{ ucfirst($deduction->type) }}
                                                                                                                        </td>
                                                                                                                        @if ($deduction->type != 'percentage')
                                                                                                                            <td
                                                                                                                                class="text-end">
                                                                                                                                {{ \Auth::user()->priceFormat($deduction->amount) }}
                                                                                                                            </td>
                                                                                                                        @else
                                                                                                                            <td
                                                                                                                                class="text-end">
                                                                                                                                {{ $deduction->amount }}%
                                                                                                                                (${{ $empdeduction }})
                                                                                                                            </td>
                                                                                                                        @endif

                                                                                                                    </tr>
                                                                                                                @endforeach
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="row mt-4">
                                                                                                    <div class="col-lg-8">

                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="col-lg-4 text-end text-sm">
                                                                                                        <div
                                                                                                            class="invoice-detail-item pb-2">
                                                                                                            <div
                                                                                                                class="invoice-detail-name font-bold">
                                                                                                                {{ __('Total Earning') }}
                                                                                                            </div>
                                                                                                            <div
                                                                                                                class="invoice-detail-value">
                                                                                                                {{ \Auth::user()->priceFormat($payslipDetail['totalEarning']) }}
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="invoice-detail-item">
                                                                                                            <div
                                                                                                                class="invoice-detail-name font-bold">
                                                                                                                {{ __('Total Deduction') }}
                                                                                                            </div>
                                                                                                            <div
                                                                                                                class="invoice-detail-value">
                                                                                                                {{ \Auth::user()->priceFormat($payslipDetail['totalDeduction']) }}
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <hr
                                                                                                            class="mt-2 mb-2">
                                                                                                        <div
                                                                                                            class="invoice-detail-item">
                                                                                                            <div
                                                                                                                class="invoice-detail-name font-bold">
                                                                                                                {{ __('Net Salary') }}
                                                                                                            </div>
                                                                                                            <div
                                                                                                                class="invoice-detail-value invoice-detail-value-lg">
                                                                                                                {{ \Auth::user()->priceFormat($payslip->net_payble) }}
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <hr>
                                                                                    <div
                                                                                        class="text-md-right pb-2 text-sm">
                                                                                        <div
                                                                                            class="float-lg-left mb-lg-0 mb-3 ">
                                                                                            <p class="mt-2">
                                                                                                {{ __('Employee Signature') }}
                                                                                            </p>
                                                                                        </div>
                                                                                        <p class="mt-2 ">
                                                                                            {{ __('Paid By') }}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
                                                                        <script>
                                                                            var filename = $('#filename').val()

                                                                            function saveAsPDF() {
                                                                                var element = document.getElementById('printableArea');
                                                                                var opt = {
                                                                                    margin: 0.3,
                                                                                    filename: filename,
                                                                                    image: {
                                                                                        type: 'jpeg',
                                                                                        quality: 1
                                                                                    },
                                                                                    html2canvas: {
                                                                                        scale: 4,
                                                                                        dpi: 72,
                                                                                        letterRendering: true
                                                                                    },
                                                                                    jsPDF: {
                                                                                        unit: 'in',
                                                                                        format: 'A2'
                                                                                    }
                                                                                };
                                                                                html2pdf().set(opt).from(element).save();
                                                                            }
                                                                        </script>






                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>





                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            @include('hrmhome.hrm_setup_activity')
        </div>
    </div>
@endsection
