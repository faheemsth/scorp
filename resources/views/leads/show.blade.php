@extends('layouts.admin')
@section('page-title')
    {{ $lead->name }}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
@endpush
@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#lead-sidenav',
            offset: 300
        })
        Dropzone.autoDiscover = false;
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            // maxFilesize: 2000,
            parallelUploads: 1,
            filename: false,
            // acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{ route('leads.file.upload', $lead->id) }}",
            success: function(file, response) {
                if (response.is_success) {
                    dropzoneBtn(file, response);
                } else {
                    myDropzone.removeFile(file);
                    show_toastr('error', response.error, 'error');
                }
            },
            error: function(file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    show_toastr('error', response.error, 'error');
                } else {
                    show_toastr('error', response, 'error');
                }
            }
        });
        myDropzone.on("sending", function(file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("lead_id", {{ $lead->id }});
        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "badge bg-info mx-1");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "{{ __('Download') }}");
            download.innerHTML = "<i class='ti ti-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "badge bg-danger mx-1");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "{{ __('Delete') }}");
            del.innerHTML = "<i class='ti ti-trash'></i>";

            del.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'DELETE',
                        success: function(response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                show_toastr('error', response.error, 'error');
                            }
                        },
                        error: function(response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                show_toastr('error', response.error, 'error');
                            } else {
                                show_toastr('error', response, 'error');
                            }
                        }
                    })
                }
            });

            var html = document.createElement('div');
            html.appendChild(download);
            @if (Auth::user()->type != 'client')
                @can('edit lead')
                    html.appendChild(del);
                @endcan
            @endif

            file.previewTemplate.appendChild(html);
        }

        @foreach ($lead->files as $file)
            @if (file_exists(storage_path('lead_files/' . $file->file_path)))
                // Create the mock file:
                var mockFile = {
                    name: "{{ $file->file_name }}",
                    size: {{ \File::size(storage_path('lead_files/' . $file->file_path)) }}
                };
                // Call the default addedfile event handler
                myDropzone.emit("addedfile", mockFile);
                // And optionally show the thumbnail of the file:
                myDropzone.emit("thumbnail", mockFile, "{{ asset(Storage::url('lead_files/' . $file->file_path)) }}");
                myDropzone.emit("complete", mockFile);

                dropzoneBtn(mockFile, {
                    download: "{{ route('leads.file.download', [$lead->id, $file->id]) }}",
                    delete: "{{ route('leads.file.delete', [$lead->id, $file->id]) }}"
                });
            @endif
        @endforeach

        @can('edit lead')
            $('.summernote-simple').on('summernote.blur', function() {

                $.ajax({
                    url: "{{ route('leads.note.store', $lead->id) }}",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        notes: $(this).val()
                    },
                    type: 'POST',
                    success: function(response) {
                        if (response.is_success) {
                            // show_toastr('Success', response.success,'success');
                        } else {
                            show_toastr('error', response.error, 'error');
                        }
                    },
                    error: function(response) {
                        response = response.responseJSON;
                        if (response.is_success) {
                            show_toastr('error', response.error, 'error');
                        } else {
                            show_toastr('error', response, 'error');
                        }
                    }
                })
            });
        @else
            $('.summernote-simple').summernote('disable');
        @endcan
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">{{ __('Lead') }}</a></li>
    <li class="breadcrumb-item"> {{ $lead->name }}</li>
@endsection

<style>
    .stages h2 {
        font-size: 12px;
        line-height: 14px;
        display: inline-block;
        white-space: nowrap;
        font-weight: bold;
        margin-top: 10px;
    }

    .wizard a {
        padding: 10px 22px 10px;
        margin-right: 5px;
        background: #efefef;
        position: relative;
        display: inline-block;
    }

    .wizard a:before {
        width: 0;
        height: 0;
        border-top: 20px inset transparent;
        border-bottom: 20px inset transparent;
        border-left: 20px solid #fff;
        position: absolute;
        content: "";
        top: 0;
        left: 0;
    }

    .wizard a:after {
        width: 0;
        height: 0;
        border-top: 20px inset transparent;
        border-bottom: 20px inset transparent;
        border-left: 20px solid #efefef;
        position: absolute;
        content: "";
        top: 0;
        right: -20px;
        z-index: 2;
    }

    .wizard a:first-child:before,
    .wizard a:last-child:after {
        border: none;
    }

    .wizard a:first-child {
        -webkit-border-radius: 4px 0 0 4px;
        -moz-border-radius: 4px 0 0 4px;
        border-radius: 4px 0 0 4px;
    }

    .wizard a:last-child {
        -webkit-border-radius: 0 4px 4px 0;
        -moz-border-radius: 0 4px 4px 0;
        border-radius: 0 4px 4px 0;
    }

    .wizard .badge {
        margin: 0 5px 0 18px;
        position: relative;
        top: -1px;
    }

    .wizard a:first-child .badge {
        margin-left: 0;
    }

    .wizard .current {
        background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d;;
        color: #fff;
    }

    .wizard .current:after {
        border-left-color: #98408a;
    }


    .lead-topbar {
        border-bottom: 1px solid rgb(230, 230, 230);
        background-color: #e9ecef;
    }

    .lead-avator img {
        width: 50px;
        height: 50px;
        border-radius: 50px;
    }

    .lead-basic-info {
        margin: 10px 0px -10px 10px;
    }

    .lead-basic-info span {
        font-size: 10px;
    }

    .lead-basic-info p {
        font-weight: bold;
    }

    .lead-info {
        border-bottom: 1px solid rgb(230, 230, 230);
    }

    .lead-info small {
        font-size: 10px;
        line-height: 14px;
        display: block;
    }

    .lead-info span {
        font-size: 13px;
        line-height: 18px;
        width: calc(100% - 10px);
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .action-btn{
        display: inline-grid !important;
    }

    .accordion-button::after{
        margin-left: 0;
    }

    .accordion-button:focus{
        box-shadow: none;
        background: #e9ecef; !important;
    }
</style>

@php
    $products = $lead->products();
    $sources = $lead->sources();
    $calls = $lead->calls;
    $emails = $lead->emails;
@endphp

@section('content')
    <div class="row">
        <div class="col-sm-12">
            {{-- topbar --}}
            <div class="lead-topbar d-flex flex-wrape justify-content-between p-1">
                <div class="float-left d-flex">
                    <div class="lead-avator">
                        <img src="https://www.iconarchive.com/download/i89179/icons8/ios7/Users-User-Male-4.ico"
                            alt="" class="">
                    </div>

                    <div class="lead-basic-info">
                        <span>{{ __('LEAD') }}</span>
                        <p class="">{{ $lead->name }}</p>
                    </div>

                </div>

                <div class="float-end my-auto">
                    @can('convert lead to deal')
                        @if (!empty($deal))
                            <a href="@can('View Deal') @if ($deal->is_active) {{ route('deals.show', $deal->id) }} @else # @endif @else # @endcan"
                                data-size="lg" data-bs-toggle="tooltip" title=" {{ __('Already Converted To Deal') }}"
                                class="btn btn-sm btn-primary">
                                <i class="ti ti-exchange"></i>
                            </a>
                        @else
                            <a href="#" data-size="lg" data-url="{{ URL::to('leads/' . $lead->id . '/show_convert') }}"
                                data-ajax-popup="true" data-bs-toggle="tooltip"
                                title="{{ __('Convert [' . $lead->subject . '] To Deal') }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-exchange"></i>
                            </a>
                        @endif
                    @endcan

                    <a href="#" data-url="{{ URL::to('leads/' . $lead->id . '/labels') }}" data-ajax-popup="true"
                        data-size="lg" data-bs-toggle="tooltip" title="{{ __('Label') }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-bookmark"></i>
                    </a>
                    <a href="#" data-size="lg" data-url="{{ route('leads.edit', $lead->id) }}" data-ajax-popup="true"
                        data-bs-toggle="tooltip" title="{{ __('Edit') }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-pencil"></i>
                    </a>
                </div>
            </div>


            <div class="lead-info d-flex justify-content-between p-2 text-center">
                <div class="">
                    <small>{{ __('Stage') }}</small>
                    <span class="font-weight-bolder">{{ $lead->stage->name }}</span>
                </div>
                <div class="">
                    <small>{{ __('Email') }}</small>
                    <span>{{ !empty($lead->email) ? $lead->email : '' }}</span>
                </div>
                <div class="">
                    <small>{{ __('Phone') }}</small>
                    <span>{{ !empty($lead->phone) ? $lead->phone : '' }}</span>
                </div>
                <div class="">
                    <small> {{ __('Pipeline') }} </small>
                    <span>{{ $lead->pipeline->name }}</span>
                </div>
                <div class="">
                    <small>{{ __('Created') }}</small>
                    <span>{{ \Auth::user()->dateFormat($lead->created_at) }}</span>
                </div>
            </div>


            {{-- Stages --}}
            <div class="stages">
                <h2>LEAD STATUS: <small>{{ $lead->stage->name }}</small></h2>
                <div class="wizard">
                    @forelse ($lead_stages as $stage)
                        <a class="{{ $lead->stage->name == $stage->name ? 'current' : '' }}">{{ $stage->name }}</a>
                    @empty
                    @endforelse
                </div>
            </div>





            <div class="lead-content my-2">

                <div class="card">
                    <div class="card-header p-1">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-details-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-details" type="button" role="tab"
                                    aria-controls="pills-details" aria-selected="true">{{ __('Details') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-related-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-related" type="button" role="tab"
                                    aria-controls="pills-related" aria-selected="false">{{ __('Related') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-activity-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-activity" type="button" role="tab"
                                    aria-controls="pills-activity" aria-selected="false">{{ __('Activity') }}</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-0">

                        <div class="tab-content" id="pills-tabContent">
                            {{-- Details Pill Start --}}
                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                                aria-labelledby="pills-details-tab">

                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button collapsed p-2" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseOne">
                                                {{ __('LEAD INFORMATION') }}
                                            </button>
                                        </h2>

                                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">

                                                <div class="table-responsive mt-3" style="margin-left: 10px;">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 12px;">
                                                                    {{ __('Record ID') }}</td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 12px;">
                                                                    {{ $lead->id }}</td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 12px;">
                                                                    {{ __('Name') }}</td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 12px;">
                                                                    {{ $lead->name }}</td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 12px;">
                                                                    {{ __('Lead Stage') }}</td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 12px;">
                                                                    {{ $lead->stage->name }}</td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 12px;">
                                                                    {{ __('Pipeline') }}</td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 12px;">
                                                                    {{ $lead->pipeline->name }}</td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 12px;">
                                                                    {{ __('Phone') }}</td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 12px;">
                                                                    {{ $lead->phone }}</td>
                                                            </tr>

                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 12px;">
                                                                    {{ __('Email') }}</td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 12px;">
                                                                    {{ $lead->email }}</td>
                                                            </tr>


                                                            <tr>
                                                                <td class=""
                                                                    style="width: 100px; text-align: right; font-size: 12px;">
                                                                    {{ __('Lead Created') }}</td>
                                                                <td class=""
                                                                    style="padding-left: 10px; font-size: 12px;">
                                                                    {{ $lead->created_at }}</td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingTwo">
                                            <button class="accordion-button collapsed p-2" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                aria-expanded="false" aria-controls="flush-collapseTwo">
                                                {{ __('Users | Courses') }}
                                            </button>
                                        </h2>
                                        <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">

                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between">

                                                                    <h5>{{ __('Users') }}</h5>
                                                                    <div class="float-end">
                                                                        <a data-size="md"
                                                                            data-url="{{ route('leads.users.edit', $lead->id) }}"
                                                                            data-ajax-popup="true"
                                                                            data-bs-toggle="tooltip"
                                                                            title="{{ __('Add User') }}"
                                                                            class="btn btn-sm btn-primary ">
                                                                            <i class="ti ti-plus"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover mb-0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>{{ __('Name') }}</th>
                                                                                <th>{{ __('Action') }}</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($lead->users as $user)
                                                                                <tr>
                                                                                    <td>
                                                                                        <div
                                                                                            class="d-flex align-items-center">
                                                                                            <div>
                                                                                                <img @if ($user->avatar) src="{{ asset('/storage/uploads/avatar/' . $user->avatar) }}" @else src="{{ asset('/storage/uploads/avatar/avatar.png') }}" @endif
                                                                                                    class="wid-30 rounded-circle me-3"
                                                                                                    alt="avatar image">
                                                                                            </div>
                                                                                            <p class="mb-0">
                                                                                                {{ $user->name }}</p>
                                                                                        </div>
                                                                                    </td>
                                                                                    @can('edit lead')
                                                                                        <td>
                                                                                            <div
                                                                                                class="action-btn bg-danger ms-2">
                                                                                                {!! Form::open([
                                                                                                    'method' => 'DELETE',
                                                                                                    'route' => ['leads.users.destroy', $lead->id, $user->id],
                                                                                                    'id' => 'delete-form-' . $lead->id,
                                                                                                ]) !!}
                                                                                                <a href="#"
                                                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                                                    data-bs-toggle="tooltip"
                                                                                                    title="{{ __('Delete') }}"><i
                                                                                                        class="ti ti-trash text-white"></i></a>

                                                                                                {!! Form::close() !!}
                                                                                            </div>
                                                                                        </td>
                                                                                    @endcan
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="card">

                                                            <div class="card-header">
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    <h5>{{ __('Courses') }}</h5>
                                                                    <div class="float-end">
                                                                        <a data-size="md"
                                                                            data-url="{{ route('leads.courses.edit', $lead->id) }}"
                                                                            data-ajax-popup="true"
                                                                            data-bs-toggle="tooltip"
                                                                            title="{{ __('Add Course') }}"
                                                                            class="btn btn-sm btn-primary">
                                                                            <i class="ti ti-plus"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover mb-0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>{{ __('Course') }}</th>
                                                                                <th>{{ __('University') }}</th>
                                                                                <th>{{ __('Fee') }}</th>
                                                                                <th>{{ __('Action') }}</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($lead->courses() as $course)
                                                                                <tr>
                                                                                    <td>
                                                                                        {{ $course->name }}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ $course->university->name }}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ $course->currency . ' ' . number_format($course->fee) }}
                                                                                    </td>
                                                                                    @can('edit lead')
                                                                                        <td>
                                                                                            <div
                                                                                                class="action-btn bg-danger ms-2">
                                                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['leads.courses.destroy', $lead->id, $course->id]]) !!}
                                                                                                <a href="#"
                                                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                                                    data-bs-toggle="tooltip"
                                                                                                    title="{{ __('Delete') }}"><i
                                                                                                        class="ti ti-trash text-white"></i></a>

                                                                                                {!! Form::close() !!}
                                                                                            </div>
                                                                                        </td>
                                                                                    @endcan
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingThree">
                                            <button class="accordion-button collapsed p-2" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"
                                                aria-expanded="false" aria-controls="flush-collapseThree">
                                                {{ __('Sources | Email') }}
                                            </button>
                                        </h2>
                                        <div id="flush-collapseThree" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div id="sources_emails">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between">
                                                                        <h5>{{ __('Sources') }}</h5>
                                                                        <div class="float-end">
                                                                            <a data-size="md"
                                                                                data-url="{{ route('leads.sources.edit', $lead->id) }}"
                                                                                data-ajax-popup="true"
                                                                                data-bs-toggle="tooltip"
                                                                                title="{{ __('Add Source') }}"
                                                                                class="btn btn-sm btn-primary">
                                                                                <i class="ti ti-plus"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-hover mb-0">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>{{ __('Name') }}</th>
                                                                                    <th>{{ __('Action') }}</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($sources as $source)
                                                                                    <tr>
                                                                                        <td>{{ $source->name }} </td>
                                                                                        @can('edit lead')
                                                                                            <td>
                                                                                                <div
                                                                                                    class="action-btn bg-danger ms-2">
                                                                                                    {!! Form::open([
                                                                                                        'method' => 'DELETE',
                                                                                                        'route' => ['leads.sources.destroy', $lead->id, $source->id],
                                                                                                        'id' => 'delete-form-' . $lead->id,
                                                                                                    ]) !!}
                                                                                                    <a href="#"
                                                                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                                                        data-bs-toggle="tooltip"
                                                                                                        title="{{ __('Delete') }}"><i
                                                                                                            class="ti ti-trash text-white"></i></a>

                                                                                                    {!! Form::close() !!}
                                                                                                </div>
                                                                                            </td>
                                                                                        @endcan
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between">
                                                                        <h5>{{ __('Emails') }}</h5>
                                                                        @can('create lead email')
                                                                            <div class="float-end">
                                                                                <a data-size="md"
                                                                                    data-url="{{ route('leads.emails.create', $lead->id) }}"
                                                                                    data-ajax-popup="true"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="{{ __('Create Email') }}"
                                                                                    class="btn btn-sm btn-primary">
                                                                                    <i class="ti ti-plus"></i>
                                                                                </a>
                                                                            </div>
                                                                        @endcan
                                                                    </div>

                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="list-group list-group-flush mt-2">
                                                                        @if (!$emails->isEmpty())
                                                                            @foreach ($emails as $email)
                                                                                <li class="list-group-item px-0">
                                                                                    <div
                                                                                        class="d-block d-sm-flex align-items-start">
                                                                                        <img src="{{ asset('/storage/uploads/avatar/avatar.png') }}"
                                                                                            class="img-fluid wid-40 me-3 mb-2 mb-sm-0"
                                                                                            alt="image">
                                                                                        <div class="w-100">
                                                                                            <div
                                                                                                class="d-flex align-items-center justify-content-between">
                                                                                                <div class="mb-3 mb-sm-0">
                                                                                                    <h6 class="mb-0">
                                                                                                        {{ $email->subject }}
                                                                                                    </h6>
                                                                                                    <span
                                                                                                        class="text-muted text-sm">{{ $email->to }}</span>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="form-check form-switch form-switch-right mb-2">
                                                                                                    {{ $email->created_at->diffForHumans() }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                            @endforeach
                                                                        @else
                                                                            <li class="text-center">
                                                                                {{ __(' No Emails Available.!') }}
                                                                            </li>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>



                            {{-- Details Pill End --}}

                            <div class="tab-pane fade" id="pills-related" role="tabpanel"
                                aria-labelledby="pills-related-tab">

                                <div class="row">

                                    <div id="discussion_note">
                                        <div class="row">

                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h5>{{ __('Discussion') }}</h5>
                                                            <div class="float-end">
                                                                <a data-size="lg"
                                                                    data-url="{{ route('leads.discussions.create', $lead->id) }}"
                                                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                    title="{{ __('Add Message') }}" class="btn btn-sm btn-primary">
                                                                    <i class="ti ti-plus"></i>
                                                                </a>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list-group list-group-flush mt-2">
                                                            @if (!$lead->discussions->isEmpty())
                                                                @foreach ($lead->discussions as $discussion)
                                                                    <li class="list-group-item px-0">
                                                                        <div class="d-block d-sm-flex align-items-start">
                                                                            <img src="@if ($discussion->user->avatar) {{ asset('/storage/uploads/avatar/' . $discussion->user->avatar) }} @else {{ asset('/storage/uploads/avatar/avatar.png') }} @endif"
                                                                                class="img-fluid wid-40 me-3 mb-2 mb-sm-0" alt="image">
                                                                            <div class="w-100">
                                                                                <div
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <div class="mb-3 mb-sm-0">
                                                                                        <h6 class="mb-0"> {{ $discussion->comment }}
                                                                                        </h6>
                                                                                        <span
                                                                                            class="text-muted text-sm">{{ $discussion->user->name }}</span>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-switch form-switch-right mb-2">
                                                                                        {{ $discussion->created_at->diffForHumans() }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            @else
                                                                <li class="text-center">
                                                                    {{ __(' No Data Available.!') }}
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>{{ __('Notes') }}</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <textarea class="summernote-simple">{!! $lead->notes !!}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="files" class="card">
                                        <div class="card-header ">
                                            <h5>{{ __('Files') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="col-md-12 dropzone top-5-scroll browse-file" id="dropzonewidget"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="tab-pane fade" id="pills-activity" role="tabpanel"
                                aria-labelledby="pills-activity-tab">


                                <div id="calls" class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5>{{ __('Calls') }}</h5>

                                            <div class="float-end">
                                                <a data-size="lg" data-url="{{ route('leads.calls.create', $lead->id) }}"
                                                    data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Add Call') }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th width="">{{ __('Subject') }}</th>
                                                        <th>{{ __('Call Type') }}</th>
                                                        <th>{{ __('Duration') }}</th>
                                                        <th>{{ __('User') }}</th>
                                                        <th>{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($calls as $call)
                                                        <tr>
                                                            <td>{{ $call->subject }}</td>
                                                            <td>{{ ucfirst($call->call_type) }}</td>
                                                            <td>{{ $call->duration }}</td>
                                                            <td>{{ isset($call->getLeadCallUser) ? $call->getLeadCallUser->name : '-' }}
                                                            </td>
                                                            <td>
                                                                @can('edit lead call')
                                                                    <div class="action-btn bg-info ms-2">
                                                                        <a href="#"
                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                            data-url="{{ URL::to('leads/' . $lead->id . '/call/' . $call->id . '/edit') }}"
                                                                            data-ajax-popup="true" data-size="xl"
                                                                            data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                            data-title="{{ __('Role Edit') }}">
                                                                            <i class="ti ti-pencil text-white"></i>
                                                                        </a>
                                                                    </div>
                                                                @endcan
                                                                @can('delete lead call')
                                                                    <div class="action-btn bg-danger ms-2">
                                                                        {!! Form::open([
                                                                            'method' => 'DELETE',
                                                                            'route' => ['leads.calls.destroy', $lead->id, $call->id],
                                                                            'id' => 'delete-form-' . $lead->id,
                                                                        ]) !!}
                                                                        <a href="#"
                                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                            data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                                                class="ti ti-trash text-white"></i></a>

                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                @endcan
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="activity" class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Activity') }}</h5>
                                    </div>
                                    <div class="card-body ">

                                        <div class="row leads-scroll">
                                            <ul class="event-cards list-group list-group-flush mt-3 w-100">
                                                @if (!$lead->activities->isEmpty())
                                                    @foreach ($lead->activities as $activity)
                                                        <li class="list-group-item card mb-3">
                                                            <div class="row align-items-center justify-content-between">
                                                                <div class="col-auto mb-3 mb-sm-0">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="theme-avtar bg-primary">
                                                                            <i class="ti {{ $activity->logIcon() }}"></i>
                                                                        </div>
                                                                        <div class="ms-3">
                                                                            <span
                                                                                class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                                                            <h6 class="m-0">{!! $activity->getLeadRemark() !!}</h6>
                                                                            <small
                                                                                class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">

                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                @else
                                                    No activity found yet.
                                                @endif
                                            </ul>
                                        </div>

                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Drive') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <input type="text" class="form form-control lead-drive-link"
                                                placeholder="Drive Link" value="{{ $deal->drive_link }}">
                                            <input type="hidden" class="lead-drive-url"
                                                value="{{ $lead->id }}/drive-link">
                                            <input type="hidden" class="lead-id" value="{{ $lead->id }}">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
