<a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
<div class="container-fluid px-1 mx-0">
    <div class="row">
        <div class="col-sm-12">

            <!-- Topbar Start -->
            <div class="lead-topbar d-flex justify-content-between align-items-center p-2">
                <div class="d-flex align-items-center">
                    <div class="lead-avatar">
                        <img src="{{ asset('assets/images/placeholder-lead.png') }}" style="width:50px; height:50px;" alt="Employee Avatar">
                    </div>
                    <div class="lead-basic-info">
                        <p class="pb-0 mb-0 fw-normal">{{ __('Employee') }}</p>
                        <h5 class="fw-bold">{{ $employee->name }}</h5>
                    </div>
                </div>
                
                <div class="d-flex gap-1 me-3 align-items-center">
                    @can('edit employee')
                    <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-dark text-white d-flex justify-content-center align-items-center" style="width: 36px; height: 36px; margin-top: 10px;" data-bs-original-title="{{__('Edit Employee')}}" data-bs-toggle="tooltip" title="{{ __('Edit Employee') }}">
                        <i class="ti ti-pencil" style="font-size: 18px;"></i>
                    </a>
                    @endcan
                
                    @can('delete employee')
                    {!! Form::open(['method' => 'DELETE', 'class' => 'mb-0', 'route' => ['employee.destroy', $employee->id], 'id' => 'delete-form-'.$employee->id]) !!}
                    <button type="submit" class="btn btn-danger text-white bs-pass-para d-flex justify-content-center align-items-center" style="width: 36px; height: 36px; margin-top: 10px;" data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                        <i class="ti ti-archive" style="font-size: 18px;"></i>
                    </button>
                    {!! Form::close() !!}
                    @endcan
            
                </div>
                
            </div>
            <!-- Topbar End -->


            <div class="card me-3">
                <div class="card-header bg-white">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-details" role="tab" aria-controls="pills-details" aria-selected="true">{{ __('Personal Details') }}</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-company" role="tab" aria-controls="pills-details" aria-selected="true">{{ __('Company Detail') }}</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-documents" role="tab" aria-controls="pills-details" aria-selected="true">{{ __('Document Detail') }}</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-bank" role="tab" aria-controls="pills-details" aria-selected="true">{{ __('Bank Account Detail') }}</button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="pills-tabContent">
                        <!-- Personal Detail Tab Start -->
                        <div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                                            {{ __('PERSONAL INFORMATION') }}
                                        </button>
                                    </h2>

                                    <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headinginfo">
                                        <div class="accordion-body">
                                            <div class="table-responsive mt-1">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Record ID') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $employee->id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Name') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $employee->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Email') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;"><a href="mailto:{{ $employee->email }}" target="_blank">{{ $employee->email }}</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Phone') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $employee->phone }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Created at') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $employee->created_at }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Update at') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $employee->updated_at }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Personal Detail Tab End -->


                        <!-- Personal Company Information Tab Start -->
                        <div class="tab-pane fade" id="pills-company" role="tabpanel" aria-labelledby="pills-details-tab">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                                            {{ __('COMPANY INFORMATION') }}
                                        </button>
                                    </h2>

                                    <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headinginfo">
                                        <div class="accordion-body">
                                            <div class="table-responsive mt-1">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Brand') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $userRegionBranch['users'][$employee->brand_id] ?? '' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Region') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $userRegionBranch['regions'][$employee->region_id] ?? '' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Branch') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $userRegionBranch['branches'][$employee->branch_id] ?? '' }}</td>
                                                        </tr>

                                                        <td style="font-size: 14px;">{{ __('Designation') }}</td>
                                                        <td style="padding-left: 10px; font-size: 14px;">{{ ucfirst($employee->type) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Personal Company Information Tab End -->


                        <!-- Personal Company Information Tab Start -->
                        <div class="tab-pane fade" id="pills-documents" role="tabpanel" aria-labelledby="pills-details-tab">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                                            {{ __('EMPLOYEE DOCUMENTS') }}
                                        </button>
                                    </h2>

                                    <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headinginfo">
                                        <div class="accordion-body">
                                            <div class="table-responsive mt-1">
                                                <table>
                                                    <tbody>
                                                        @php
                                                            $employeedoc = !empty($employee)?$employee->documents()->pluck('document_value',__('document_id')):[];
                                                        @endphp

                                                        @if(!$documents->isEmpty())
                                                        @foreach($documents as $key=>$document)
                                                            
                                                        <tr>
                                                            <td style="font-size: 14px;">{{$document->name }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">
                                                                <a href="{{ (!empty($employeedoc[$document->id])?asset(Storage::url('uploads/document')).'/'.$employeedoc[$document->id]:'') }}" target="_blank">{{ (!empty($employeedoc[$document->id])?$employeedoc[$document->id]:'') }}</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        @else
                                                        <tr>
                                                            <td colspan="2"> No Document Found !!!</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Personal Company Information Tab End -->


                        <!-- Personal Company Information Tab Start -->
                        <div class="tab-pane fade" id="pills-bank" role="tabpanel" aria-labelledby="pills-details-tab">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headinginfo">
                                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseinfo">
                                            {{ __('BANK DETAIL') }}
                                        </button>
                                    </h2>

                                    <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headinginfo">
                                        <div class="accordion-body">
                                            <div class="table-responsive mt-1">
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Account Holder') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $employee->account_holder_name ?? '' }}</td>
                                                        </tr>


                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Account Number') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $employee->account_number ?? '' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Bank Name') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $employee->bank_name ?? '' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Bank Identifier Code') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $employee->bank_identifier_code ?? '' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Branch Location') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $employee->branch_location ?? '' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td style="font-size: 14px;">{{ __('Tax Payer Id') }}</td>
                                                            <td style="padding-left: 10px; font-size: 14px;">{{ $employee->tax_payer_id ?? '' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Personal Company Information Tab End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>