@extends('layouts.admin')
@section('page-title')
    {{__('Manage Invoices')}}
@endsection
@push('script-page')
    <script>
        function copyToClipboard(element) {

            var copyText = element.id;
            document.addEventListener('copy', function (e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('success', 'Url copied to clipboard', 'success');
        }
    </script>
@endpush


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Invoice')}}</li>
@endsection





@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                    <div class="row d-flex justify-content-center align-items-center ps-0 ms-0 mb-4 my-2">
                        <div class="col-4">
                            <p class="mb-0 pb-0 ps-1">INVOICE</p>
                            <div class="dropdown">
                                <button class="dropdown-toggle all-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="    border: 1px solid lightgrey;
    background-color: transparent;">
                                    ALL INVOICE
                                </button>
                                <ul class="" aria-labelledby="dropdownMenuButton1">
                              </ul>
                            </div>
                        </div>


                        <div class="col-8 d-flex justify-content-end gap-2">
                            <div class="input-group w-25">
                                <button class="btn btn-sm list-global-search-btn">
                                    <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                        <i class="ti ti-search" style="font-size: 18px"></i>
                                    </span>
                                </button>
                                <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search" placeholder="Search this list...">
                            </div>





                                <button class="btn px-2 pb-2 pt-2 refresh-list btn-dark" ><i class="ti ti-refresh" style="font-size: 18px"></i></button>

                            <button class="btn filter-btn-show p-2 btn-dark" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-filter" style="font-size:18px"></i>
                            </button>
                            <a href="{{ route('invoice.export') }}" class="btn p-2 btn-dark" data-bs-toggle="tooltip" title="{{__('Export')}}">
                                <i class="ti ti-file-export"></i>
                            </a>

                            @can('create invoice')
                <a href="{{ route('invoice.create', 0) }}" class="btn p-2 btn-dark" data-bs-toggle="tooltip" title="{{__('Create')}}">
                    <i class="ti ti-plus"></i>
                </a>
            @endcan

                        </div>
                    </div>
                    <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                            <tr>
                                <th> {{ __('Invoice') }}</th>
{{--                                @if (!\Auth::guard('customer')->check())--}}
{{--                                    <th>{{ __('Customer') }}</th>--}}
{{--                                @endif--}}
                                <th>{{ __('Issue Date') }}</th>
                                <th>{{ __('Due Date') }}</th>
                                <th>{{ __('Due Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if (Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                    <th>{{ __('Action') }}</th>
                                @endif
                                {{-- <th>
                                <td class="barcode">
                                    {!! DNS1D::getBarcodeHTML($invoice->sku, "C128",1.4,22) !!}
                                    <p class="pid">{{$invoice->sku}}</p>
                                </td>
                            </th> --}}
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td class="Id">
{{--                                        @if (\Auth::guard('customer')->check())--}}
{{--                                            <a href="{{ route('customer.invoice.show', \Crypt::encrypt($invoice->id)) }}" class="btn btn-outline-primary">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</a>--}}
{{--                                        @else--}}
                                            <a href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}" class="btn btn-outline-dark" style="font-size: 14px;">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</a>
{{--                                        @endif--}}
                                    </td>
{{--                                    @if (!\Auth::guard('customer')->check())--}}
{{--                                        <td> {{ !empty($invoice->customer) ? $invoice->customer->name : '' }} </td>--}}
{{--                                    @endif--}}
                                    <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                    <td>
                                        @if ($invoice->due_date < date('Y-m-d'))
                                            <p class="text-danger">
                                                {{ \Auth::user()->dateFormat($invoice->due_date) }}</p>
                                        @else
                                            {{ \Auth::user()->dateFormat($invoice->due_date) }}
                                        @endif
                                    </td>
                                    <td>{{ \Auth::user()->priceFormat($invoice->getDue()) }}</td>
                                    <td>
                                        @if ($invoice->status == 0)
                                            <span
                                                class="status_badge badge bg-secondary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span
                                                class="status_badge badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span
                                                class="status_badge badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span
                                                class="status_badge badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span
                                                class="status_badge badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                    </td>
                                    @if (Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                        <td class="Action">
                                                <span>
                                                @php $invoiceID= Crypt::encrypt($invoice->id); @endphp

                                                    @can('copy invoice')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="#" id="{{ route('invoice.link.copy',[$invoiceID]) }}" class="mx-3 btn btn-dark p-2"   onclick="copyToClipboard(this)" data-bs-toggle="tooltip" data-original-title="{{__('Click to copy')}}"><i class="ti ti-link text-white"></i></a>
                                                        </div>
                                                    @endcan
                                                    @can('duplicate invoice')
                                                        <div class="action-btn bg-success ms-2">
                                                           {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id], 'id' => 'duplicate-form-' . $invoice->id]) !!}

                                                            <a href="#" class="mx-3 btn p-2 btn-dark" data-toggle="tooltip"
                                                               data-original-title="{{ __('Duplicate') }}" data-bs-toggle="tooltip" title="Duplicate Invoice"
                                                               data-original-title="{{ __('Delete') }}"
                                                               data-confirm="You want to confirm this action. Press Yes to continue or Cancel to go back"
                                                               data-confirm-yes="document.getElementById('duplicate-form-{{ $invoice->id }}').submit();">
                                                                <i class="ti ti-copy text-white"></i>
                                                                {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id], 'id' => 'duplicate-form-' . $invoice->id]) !!}
                                                                {!! Form::close() !!}
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('show invoice')
{{--                                                        @if (\Auth::guard('customer')->check())--}}
{{--                                                            <div class="action-btn bg-info ms-2">--}}
{{--                                                                    <a href="{{ route('customer.invoice.show', \Crypt::encrypt($invoice->id)) }}"--}}
{{--                                                                       class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Show "--}}
{{--                                                                       data-original-title="{{ __('Detail') }}">--}}
{{--                                                                        <i class="ti ti-eye text-white"></i>--}}
{{--                                                                    </a>--}}
{{--                                                                </div>--}}
{{--                                                        @else--}}
                                                            <div class="action-btn bg-info ms-2">
                                                                    <a href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}"
                                                                       class="mx-3 btn p-2 btn-dark" data-bs-toggle="tooltip" title="Show "
                                                                       data-original-title="{{ __('Detail') }}">
                                                                        <i class="ti ti-eye text-white"></i>
                                                                    </a>
                                                                </div>
{{--                                                        @endif--}}
                                                    @endcan
                                                    @can('edit invoice')
                                                        <div class="action-btn bg-primary ms-2">
                                                                <a href="{{ route('invoice.edit', \Crypt::encrypt($invoice->id)) }}"
                                                                   class="mx-3 btn p-2 btn-dark" data-bs-toggle="tooltip" title="Edit "
                                                                   data-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                    @endcan
                                                    @can('delete invoice')
                                                        <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id], 'id' => 'delete-form-' . $invoice->id]) !!}
                                                                    <a href="#" class="mx-3 btn p-2 btn-danger " data-bs-toggle="tooltip" title="{{__('Delete')}}"
                                                                       data-original-title="{{ __('Delete') }}"
                                                                       data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                                       data-confirm-yes="document.getElementById('delete-form-{{ $invoice->id }}').submit();">
                                                                        <i class="ti ti-trash text-white"></i>
                                                                    </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                    @endcan
                                                </span>
                                        </td>
                                    @endif
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
