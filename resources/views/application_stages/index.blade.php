@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Applications Stages') }}
@endsection
@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script>
        $(function() {
            $(".sortable").sortable();
            $(".sortable").disableSelection();
            $(".sortable").sortable({
                stop: function() {
                    var order = [];
                    $(this).find('li').each(function(index, data) {
                        order[index] = $(data).attr('data-id');
                    });

                    $.ajax({
                        url: "{{ route('lead_stages.order') }}",
                        data: {
                            order: order,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        success: function(data) {},
                        error: function(data) {
                            data = data.responseJSON;
                            show_toastr('error', data.error, 'error')
                        }
                    })
                }
            });
        });
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Applications Stage') }}</li>
@endsection
@section('content')
<style>
    span{
        font-size:14px !important;
    }
    </style>
    <div class="row">
        <div class="col-3">
            @include('layouts.crm_setup')
        </div>
        <div class="col-9">
            <div class="row justify-content-center">
                <div class="p-3 card">
                    <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                        @php($i = 0)
                        @foreach ($pipelines as $key => $pipeline)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($i == 0) active @endif"
                                    id="pills-user-tab-1" data-bs-toggle="pill" data-bs-target="#tab{{ $key }}"
                                    type="button">{{ $pipeline['name'] }}
                                </button>
                            </li>
                            @php($i++)
                        @endforeach
                    </ul>
                </div>
                <div class="card">

                    <div class="card-header" style="display: flex; justify-content: space-between;">
                        <h3>{{ __('Application Stages') }}</h3>

                        @can('create application stage')
                            <div class="float-end">
                                <a href="#" data-size="md" data-url="{{ url('application_stages/create') }}"
                                    data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create Application Stage') }}"
                                    class="btn btn-sm btn-dark">
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        @endcan
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            @php($i = 0)
                            @foreach ($pipelines as $key => $pipeline)
                                <div class="tab-pane fade show @if ($i == 0) active @endif"
                                    id="tab{{ $key }}" role="tabpanel" aria-labelledby="pills-user-tab-1">
                                    <ul class="list-group sortable">
                                        @foreach ($pipeline['application_stages'] as $lead_stages)
                                            <li class="list-group-item d-flex justify-content-between"
                                                data-id="{{ $lead_stages->id }}">

                                                <span class=" text-dark"
                                                    style="width: 100px;">{{ $lead_stages->name }}</span>

                                                <span class=" text-dark">{{ $lead_stages->type }}</span>

                                                <span class="d-flex justify-content-center">

                                                    @can('edit application stage')
                                                        <a href="#"
                                                            class="mx-1 btn btn-sm btn-dark d-inline-flex align-items-center"
                                                            data-url="{{ URL::to('application_stages/' . $lead_stages->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}"
                                                            data-title="{{ __('Edit Lead Stages') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    @endcan
                                                    @if (count($pipeline['application_stages']))
                                                        @can('delete application stage')
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['lead_stages.destroy', $lead_stages->id]]) !!}
                                                            <a href="#"
                                                                class="btn btn-sm btn-danger align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                                    class="ti ti-trash text-white"></i></a>
                                                            {!! Form::close() !!}
                                                        @endcan
                                                    @endif
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @php($i++)
                            @endforeach
                        </div>
                        <p class="text-muted mt-4"><strong>{{ __('Note') }} :
                            </strong>{{ __('You can easily change order of application stage using drag & drop.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
