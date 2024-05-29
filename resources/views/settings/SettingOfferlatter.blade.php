@extends('layouts.admin')
@section('page-title')
    {{ __('Settings') }}
@endsection
@php
    use App\Models\Utility;
    // $logo=asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo');
    $logo_light = \App\Models\Utility::getValByName('company_logo_light');
    $logo_dark = \App\Models\Utility::getValByName('company_logo_dark');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
    $setting = \App\Models\Utility::colorset();
    $mode_setting = \App\Models\Utility::mode_layout();
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
    $company_setting = \App\Models\Utility::settingsById('site_currency');
    $SITE_RTL = isset($setting['SITE_RTL']) ? $setting['SITE_RTL'] : 'off';
    $EmailTemplates = App\Models\EmailTemplate::all();
    $currantLang = Utility::languages();

@endphp

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection

@push('css-page')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush
@push('script-page')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script>
        summernote();
        $('.summernote-simple0').on('summernote.blur', function() {
            $.ajax({
                url: "{{ route('offerlatter.update', $offerlang) }}",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    content: $(this).val()
                },
                type: 'POST',
                success: function(response) {
                    console.log(response)
                    if (response.is_success) {
                        show_toastr('success', response.success, 'success');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                },
                error: function(response) {

                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('error', response.error, 'error');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                }
            })
        });
        $('.summernote-simple1').on('summernote.blur', function() {
            $.ajax({
                url: "{{ route('joiningletter.update', $joininglang) }}",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    content: $(this).val()
                },
                type: 'POST',
                success: function(response) {
                    console.log(response)
                    if (response.is_success) {
                        show_toastr('success', response.success, 'success');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                },
                error: function(response) {

                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('error', response.error, 'error');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                }
            })
        });
        $('.summernote-simple2').on('summernote.blur', function() {
            $.ajax({
                url: "{{ route('experiencecertificate.update', $explang) }}",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    content: $(this).val()
                },
                type: 'POST',
                success: function(response) {
                    console.log(response)
                    if (response.is_success) {
                        show_toastr('success', response.success, 'success');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                },
                error: function(response) {

                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('error', response.error, 'error');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                }
            })
        });
        $('.summernote-simple3').on('summernote.blur', function() {
            $.ajax({
                url: "{{ route('noc.update', $noclang) }}",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    content: $(this).val()
                },
                type: 'POST',
                success: function(response) {
                    console.log(response)
                    if (response.is_success) {
                        show_toastr('success', response.success, 'success');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                },
                error: function(response) {

                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('error', response.error, 'error');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                }
            })
        });

        $('.summernote-simple5').on('summernote.blur', function() {
            $.ajax({
                url: "{{ route('update.email', $EmailMarketing->id) }}",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    content: $(this).val()
                },
                type: 'POST',
                success: function(response) {
                    console.log(response)
                    if (response.is_success) {
                        show_toastr('success', response.success, 'success');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                },
                error: function(response) {

                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('error', response.error, 'error');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                }
            })
        });
    </script>
@endpush
@push('script-page')
    <script>
        $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function() {
            var template = $("select[name='invoice_template']").val();
            var color = $("input[name='invoice_color']:checked").val();
            $('#invoice_frame').attr('src', '{{ url('/invoices/preview') }}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='proposal_template'], input[name='proposal_color']", function() {
            var template = $("select[name='proposal_template']").val();
            var color = $("input[name='proposal_color']:checked").val();
            $('#proposal_frame').attr('src', '{{ url('/proposal/preview') }}/' + template + '/' + color);
        });

        $(document).on("chnge", "select[name='bill_template'], input[name='bill_color']", function() {
            var template = $("select[name='bill_template']").val();
            var color = $("input[name='bill_color']:checked").val();
            $('#bill_frame').attr('src', '{{ url('/bill/preview') }}/' + template + '/' + color);
        });
    </script>

    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })
        $(".list-group-item").click(function() {
            $('.list-group-item').filter(function() {
                return this.href == id;
            }).parent().removeClass('text-dark');
        });

        function check_theme(color_val) {
            $('#theme_color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
        }
    </script>


    <script>
        document.getElementById('company_logo_dark').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image').src = src
        }
        document.getElementById('company_logo_light').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image1').src = src
        }
        document.getElementById('company_favicon').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image2').src = src
        }
    </script>

    <script type="text/javascript">
        $(document).on("click", ".email-template-checkbox", function() {
            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: chbox.val()
                },
                type: 'PUT',
                success: function(response) {
                    if (response.is_success) {
                        show_toastr('success', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function(response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('Error', response.error, 'error');
                    } else {
                        show_toastr('Error', response, 'error');
                    }
                }
            })
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-12">












                    <!--Payment Settings-->
                    <div id="offer-letter-settings" class="card">
                        <div class="col-md-12">
                            <div class="card-header d-flex justify-content-between">
                                <h5>{{ __('Offer Letter Settings') }}</h5>
                                <div class="d-flex justify-content-end drp-languages">
                                    <ul class="list-unstyled mb-0 m-2">
                                        <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                                            <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                                data-bs-toggle="dropdown" href="#" role="button"
                                                aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                                                <span class="drp-text hide-mob text-dark">
                                                    {{ Str::upper($offerlang) }}
                                                </span>
                                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                            </a>
                                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                aria-labelledby="dropdownLanguage">
                                                @foreach ($currantLang as $offerlangs)
                                                    <a href="{{ route('get.offerlatter.language', ['noclangs' => $noclang, 'explangs' => $explang, 'offerlangs' => $offerlangs, 'joininglangs' => $joininglang]) }}"
                                                        class="dropdown-item ms-1 {{ $offerlangs == $offerlang ? 'text-dark' : '' }}">{{ Str::upper($offerlangs) }}</a>
                                                @endforeach
                                            </div>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                            <div class="card-body ">
                                <h5 class= "font-weight-bold pb-3">{{ __('Placeholders') }}</h5>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="card">
                                        <div class="card-header card-body">
                                            <div class="row ">
                                                <div class="row">
                                                    <p class="col-4">{{ __('Applicant Name') }} : <span
                                                            class="pull-end text-dark">{applicant_name}</span></p>
                                                    <p class="col-4">{{ __('Company Name') }} : <span
                                                            class="pull-right text-dark">{app_name}</span></p>
                                                    <p class="col-4">{{ __('Job title') }} : <span
                                                            class="pull-right text-dark">{job_title}</span></p>
                                                    <p class="col-4">{{ __('Job type') }} : <span
                                                            class="pull-right text-dark">{job_type}</span></p>
                                                    <p class="col-4">{{ __('Proposed Start Date') }} : <span
                                                            class="pull-right text-dark">{start_date}</span></p>
                                                    <p class="col-4">{{ __('Working Location') }} : <span
                                                            class="pull-right text-dark">{workplace_location}</span></p>
                                                    <p class="col-4">{{ __('Days Of Week') }} : <span
                                                            class="pull-right text-dark">{days_of_week}</span></p>
                                                    <p class="col-4">{{ __('Salary') }} : <span
                                                            class="pull-right text-dark">{salary}</span></p>
                                                    <p class="col-4">{{ __('Salary Type') }} : <span
                                                            class="pull-right text-dark">{salary_type}</span></p>
                                                    <p class="col-4">{{ __('Salary Duration') }} : <span
                                                            class="pull-end text-dark">{salary_duration}</span></p>
                                                    <p class="col-4">{{ __('Offer Expiration Date') }} : <span
                                                            class="pull-right text-dark">{offer_expiration_date}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-border-style ">

                                {{ Form::open(['route' => ['offerlatter.update', $offerlang], 'method' => 'post']) }}
                                <div class="form-group col-12">
                                    {{ Form::label('content', __(' Format'), ['class' => 'form-label text-dark']) }}
                                    <textarea name="content" class="summernote-simple0 summernote-simple">{!! isset($currOfferletterLang->content) ? $currOfferletterLang->content : '' !!}</textarea>
                                </div>
                                <div class="card-footer text-end">
                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-dark BulkSendButton']) }}
                                </div>

                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>

                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection
<script>
    $(document).ready(function() {
        $('form').submit(function(e) {
            alert(1);
            e.preventDefault(); // Prevent the default form submission

            var formData = new FormData($(this)[0]); // Create FormData object from the form
            $(".BulkSendButton").val('Processing...');
            $('.BulkSendButton').attr('disabled', 'disabled');
            $.ajax({
                url: $(this).attr('action'), // Get the form action URL
                type: $(this).attr('method'), // Get the form method (POST in this case)
                data: formData, // Set the form data
                contentType: false, // Don't set contentType, let jQuery handle it
                processData: false, // Don't process the data, let jQuery handle it
                success: function(response) {
                    if (response.status == 'success') {
                        show_toastr('Success', response.message, 'success');
                        $('#commonModal').modal('hide');
                        openSidebar('/show-trainer?id='+response.id);
                        return false;
                    } else {
                        show_toastr('Error', response.message, 'error');
                        $(".BulkSendButton").val('Save Changes');
                        $('.BulkSendButton').removeAttr('disabled');
                    }
                },
            });
        });
    });
</script>
