@extends('layouts.admin')
<?php $setting = \App\Models\Utility::colorset(); ?>
{{-- <link rel="stylesheet" href="{{ asset('css/customsidebar.css') }}"> --}}

@section('page-title')
    {{ __('Manage Leads') }} @if ($pipeline)
        - {{ $pipeline->name }}
    @endif
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}" id="main-style-link">
@endpush
@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dragula.min.js') }}"></script>
    <script>
        ! function(a) {
            "use strict";
            var t = function() {
                this.$body = a("body")
            };
            t.prototype.init = function() {
                a('[data-plugin="dragula"]').each(function() {
                    var t = a(this).data("containers"),
                        n = [];
                    if (t)
                        for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]);
                    else n = [a(this)[0]];
                    var r = a(this).data("handleclass");
                    r ? dragula(n, {
                        moves: function(a, t, n) {
                            return n.classList.contains(r)
                        }
                    }) : dragula(n).on('drop', function(el, target, source, sibling) {

                        var order = [];
                        $("#" + target.id + " > div").each(function() {
                            order[$(this).index()] = $(this).attr('data-id');
                        });

                        var id = $(el).attr('data-id');

                        var old_status = $("#" + source.id).data('status');
                        var new_status = $("#" + target.id).data('status');
                        var stage_id = $(target).attr('data-id');
                        var pipeline_id = '{{ $pipeline->id }}';

                        $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div")
                            .length);
                        $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div")
                            .length);
                        $.ajax({
                            url: '{{ route('leads.order') }}',
                            type: 'POST',
                            data: {
                                lead_id: id,
                                stage_id: stage_id,
                                order: order,
                                new_status: new_status,
                                old_status: old_status,
                                pipeline_id: pipeline_id,
                                "_token": $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                show_toastr('{{ __('Success') }}',
                                    '{{ __('Drag Content Successfully!') }}', 'success');
                            },
                            error: function(data) {
                                data = data.responseJSON;
                                show_toastr('error', data.error, 'error')
                            }
                        });
                    });
                })
            }, a.Dragula = new t, a.Dragula.Constructor = t
        }(window.jQuery),
        function(a) {
            "use strict";

            a.Dragula.init()

        }(window.jQuery);
    </script>
    <script>
        $(document).on("change", "#default_pipeline_id", function() {
            $('#change-pipeline').submit();
        });

        $(document).on("click", ".btn_submit", function() {
            var discussion = $(".discussion-msg").val();
            var lead_id = $(".lead_id").val();
            var csrf_token = $('meta[name="csrf-token"]').attr('content');

            if (discussion == '') {
                return false;
            }

            $.ajax({
                url: "/leads/saveDiscussions",
                data: {
                    lead_id,
                    discussion,
                    _token: csrf_token,
                },
                type: "POST",
                cache: false,
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status) {
                        $(".discussion-list-group").append(data.content);
                        $(".discussion-msg").val('');
                        $('.modal-discussion-add-span').removeClass('ti-minus');
                        $('.modal-discussion-add-span').addClass('ti-plus');
                        $(".add-discussion-div").addClass('d-none');
                        Swal.fire(
                            'Discussion Save!',
                            'Discussion saved successfully.',
                            'success'
                        );
                    }
                }
            });

        })

        $(document).on("click", "#modal-discussion-add", function() {

            if ($('.modal-discussion-add-span').hasClass('ti-plus')) {
                $('.modal-discussion-add-span').removeClass('ti-plus');
                $('.modal-discussion-add-span').addClass('ti-minus');
                $(".add-discussion-div").removeClass('d-none');
            } else {
                $('.modal-discussion-add-span').removeClass('ti-minus');
                $('.modal-discussion-add-span').addClass('ti-plus');
                $(".add-discussion-div").addClass('d-none');
            }
        })

        $(document).on("click", "#import_csv_modal_btn", function() {
            $("#import_csv").modal('show');
        })

        $(document).on("change", "#lead-file", function() {
            var form = $(this).closest('form')[0]; // Get the form element
            var formData = new FormData(form); // Pass the form element to FormData constructor
            $.ajax({
                url: "{{ route('leads.fetchColumns') }}",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        $(".columns-matching").html(response.data);
                        $(".submit_btn").removeClass('d-none');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        })

        var dropdownValues = [];
        var dropdownKeys = [];

        $(document).on("change", ".lead-columns", function() {

            var key = $(this).attr('data-id');
            var value = $(this).val();


            if (value == '') {

                if (key > -1 && key < dropdownValues.length) {
                    dropdownValues.splice(key, 1);
                }

            } else {

                if (dropdownValues.indexOf(value) !== -1) {
                    $(this).val('');
                    show_toastr('error', 'Field is already assigned. Change the existing feild first', 'error');
                    return false;
                }


                dropdownValues[key] = value;
                console.log(dropdownValues);
            }
            return true;
        })


        $(document).on("submit", "#import_csv", function() {
            var assigned_to = $("#assigned_to").val();

            if (assigned_to == undefined || assigned_to == '') {
                show_toastr('error', 'Please assigned the leads', 'error');
                return false;
            }
        })
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Lead') }}</li>
@endsection
<div id="mySidenav" class=" sidenav <?= $setting['cust_darklayout'] == 'on' ? 'sidenav-dark' : 'sidenav-light' ?>">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

    <div class="d-flex justify-content-between px-3">
        <h5>Discussion</h5>
        <div class="d-flex">
            <a href="#" class="btn btn-sm btn-dark" id="modal-discussion-add">
                <i class="ti ti-plus modal-discussion-add-span"></i>
            </a>
        </div>
    </div>
    <ul class="discussion-list-group list-group list-group-flush mt-2" style="max-height: 400px; overflow-y: scroll;">
    </ul>
    <input type="hidden" name='lead_id' class='lead_id' />
    <div class="add-discussion-div d-none">
        <div class="form form-group">
            <label for="">Discussion</label>
            <textarea name="" id="" cols="30" rows="10" class="form form-control discussion-msg"></textarea>
        </div>

        <div class="form form-group">
            <input type="button" value="Submit" class="btn btn-dark btn-icon btn_submit">
        </div>
    </div>
</div>
@section('content')
    <div class="col-xl-12">
        <div class="card my-card" style="max-width: 98%;border-radius:0px; min-height: 250px !important;">
            <div class="card-body table-border-style" style="padding: 25px 3px;">
                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                    <div class="col-4">
                        <p class="mb-0 pb-0">LEADS</p>
                        <div class="dropdown">
                            <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                ALL LEAD
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-8 d-flex justify-content-end gap-2">
                        <div class="input-group w-25">
                            <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                <i class="ti ti-search" style="font-size: 18px"></i>
                            </span>
                            <input type="Search" class="form-control border-0 bg-transparent ps-0"
                                placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                        <div>
                            <button class="btn btn-dark px-2 pb-2 pt-2"><i class="ti ti-refresh"
                                    style="font-size: 18px"></i></button>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-dark dropdown-toggle px-2 pb-1 pt-2" type="button"
                                id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-settings" style="font-size:18px"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>

                        <button class="btn btn-dark  p-2" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="ti ti-filter" style="font-size:18px"></i>
                        </button>

                        <button class="btn btn-dark p-2" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="ti ti-layout" style="font-size:18px"></i>
                        </button>


                        <button class="btn btn-dark px-2 py-1" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            New Lead
                        </button>
                        {{-- {!! Form::open(['route' => 'deals.change.pipeline', 'id' => 'change-pipeline', 'class' => 'btn btn-sm']) !!}
                            {!! Form::select('default_pipeline_id', $pipelines, $pipeline->id, ['class' => 'form-control select px-2 py-1', 'id' => 'default_pipeline_id', 'style' => 'width: 200px']) !!}
                        {!! Form::close() !!} --}}
                        <a href="{{ route('leads.list') }}" data-size="lg" data-bs-toggle="tooltip"
                            title="{{ __('List View') }}" class="btn btn-sm btn-dark px-2 py-1">
                            <i class="ti ti-list" style="font-size:18px"></i>
                        </a>
                        <a href="#" data-size="lg" data-url="{{ route('leads.create') }}" data-ajax-popup="true"
                            data-bs-toggle="tooltip" title="{{ __('Create New Lead') }}"
                            class="btn btn-sm btn-dark px-2 py-1">
                            <i class="ti ti-plus" style="font-size:18px"></i>
                        </a>
                        <button data-size="lg" data-bs-toggle="tooltip" title="{{ __('Import Csv') }}"
                            class="btn btn-sm btn-dark px-2 py-1" id="import_csv_modal_btn" data-bs-toggle="modal"
                            data-bs-target="#import_csv">
                            <i class="fa fa-file-csv"></i>
                        </button>
                    </div>
                </div>



                <div class="my-4 mx-4">
                    <div class="enries_per_page" style="max-width: 300px; display: flex;">

                        <?php
                        $all_params = isset($_GET) ? $_GET : '';
                        if (isset($all_params['num_results_on_page'])) {
                            unset($all_params['num_results_on_page']);
                        }
                        ?>
                        <input type="hidden" value="<?= http_build_query($all_params) ?>" class="url_params">
                        <select name="" id="" class="enteries_per_page form form-control"
                            style="width: 100px; margin-right: 1rem;">
                            <option
                                <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 25 ? 'selected' : '' ?>
                                value="25">25</option>
                            <option
                                <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 100 ? 'selected' : '' ?>
                                value="100">100</option>
                            <option
                                <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 300 ? 'selected' : '' ?>
                                value="300">300</option>
                            <option
                                <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 1000 ? 'selected' : '' ?>
                                value="1000">1000</option>
                            <option
                                <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == $total_records ? 'selected' : '' ?>
                                value="{{ $total_records }}">all</option>
                        </select>

                        <span style="margin-top: 5px;">entries per page</span>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">

                @php
                    $lead_stages = $pipeline->LeadStages;
                    $json = [];
                    foreach ($lead_stages as $lead_stage) {
                        $json[] = 'task-list-' . $lead_stage->id;
                    }
                @endphp
                <div class="row kanban-wrapper horizontal-scroll-cards" data-containers='{!! json_encode($json) !!}'
                    data-plugin="dragula">
                    @foreach ($lead_stages as $lead_stage)
                        @php($leads = $lead_stage->lead())
                        <div class="col" style="width:250px;">
                            <div class="card">
                                <div class="card-header">
                                    <div class="float-end">
                                        <span class="btn btn-sm btn-dark btn-icon count" style="font-size: 10px;">
                                            {{ $lead_stage->lead_count() }}
                                        </span>
                                    </div>
                                    <h4 class="mb-0" style="font-size: 14px;">{{ $lead_stage->name }}</h4>
                                </div>


                                <div class="card-body kanban-box" id="task-list-{{ $lead_stage->id }}"
                                    data-id="{{ $lead_stage->id }}">
                                    @foreach ($leads as $lead)
                                        <div class="card" data-id="{{ $lead->id }}">
                                            <div class="pt-3 ps-3">
                                                @php($labels = $lead->labels())
                                                @if ($labels)
                                                    @foreach ($labels as $label)
                                                        <div
                                                            class="badge-xs badge bg-{{ $label->color }} p-2 px-3 rounded">
                                                            {{ $label->name }}</div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="card-header border-0 pb-0 position-relative">
                                                <h5 style="font-size: 14px;"><a
                                                        href="@can('view lead') @if ($lead->is_active)
                                                    {{ route('leads.show', $lead->id) }}@else#
                            @endif @else#
                            @endcan">{{ $lead->name }}</a>
                                                    <span style="cursor:pointer" onclick="openNav(<?= $lead->id ?>)"
                                                        data-lead-id="{{ $lead->id }}"
                                                        class="ti ti-brand-hipchat"></span>
                                                </h5>
                                                <div class="card-header-right">
                                                    @if (Auth::user()->type != 'client')
                                                        <div class="btn-group card-option">
                                                            <button type="button" class="btn dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                @can('edit lead')
                                                                    <a href="#!" data-size="md"
                                                                        data-url="{{ URL::to('leads/' . $lead->id . '/labels') }}"
                                                                        data-ajax-popup="true" class="dropdown-item"
                                                                        data-bs-original-title="{{ __('Labels') }}">
                                                                        <i class="ti ti-bookmark"></i>
                                                                        <span>{{ __('Labels') }}</span>
                                                                    </a>

                                                                    <a href="#!" data-size="lg"
                                                                        data-url="{{ URL::to('leads/' . $lead->id . '/edit') }}"
                                                                        data-ajax-popup="true" class="dropdown-item"
                                                                        data-bs-original-title="{{ __('Edit Lead') }}">
                                                                        <i class="ti ti-pencil"></i>
                                                                        <span>{{ __('Edit') }}</span>
                                                                    </a>
                                                                @endcan
                                                                @can('delete lead')
                                                                    {!! Form::open([
                                                                        'method' => 'DELETE',
                                                                        'route' => ['leads.destroy', $lead->id],
                                                                        'id' => 'delete-form-' . $lead->id,
                                                                    ]) !!}
                                                                    <a href="#!" class="dropdown-item bs-pass-para">
                                                                        <i class="ti ti-archive"></i>
                                                                        <span> {{ __('Delete') }} </span>
                                                                    </a>
                                                                    {!! Form::close() !!}
                                                                @endcan


                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <?php
                                            $products = $lead->products();
                                            $sources = $lead->sources();
                                            ?>
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <ul class="list-inline mb-0">

                                                        <li class="list-inline-item d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" title="{{ __('Product') }}">
                                                            <i class="f-16 text-primary ti ti-shopping-cart"></i>
                                                            {{ count($products) }}
                                                        </li>

                                                        <li class="list-inline-item d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" title="{{ __('Source') }}">
                                                            <i
                                                                class="f-16 text-primary ti ti-social"></i>{{ count($sources) }}
                                                        </li>
                                                    </ul>
                                                    <div class="user-group">
                                                        @foreach ($lead->users as $user)
                                                            <img style="margin: 0; width: 15px; height: 15px;"
                                                                src="{{ !empty($avatar[$lead->created_by]) ? asset('/storage/uploads/avatar/' . $avatar[$lead->created_by]) : asset('storage/uploads/avatar/avatar.png') }}"
                                                                alt="image" data-bs-toggle="tooltip"
                                                                title="{{ $username[$lead->created_by] ?? '' }}">

                                                            <img style="margin: 0; width: 15px; height: 15px;"
                                                                src="{{ !empty($avatar[$lead->user_id]) ? asset('/storage/uploads/avatar/' . $avatar[$lead->user_id]) : asset('storage/uploads/avatar/avatar.png') }}"
                                                                alt="image" data-bs-toggle="tooltip"
                                                                title="{{ $username[$lead->user_id] ?? '' }}">
                                                        @endforeach
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($total_records > 0)
                    @include('layouts.pagination', [
                        'total_pages' => $total_records,
                        'num_results_on_page' => 25,
                    ])
                @endif
            </div>
        </div>
    </div>
@endsection

<script>
    function openNav(lead_id) {
        var ww = $(window).width()


        if (ww < 500) {
            $("#mySidenav").css('width', ww + 'px');
            $("#main").css('margin-right', ww + 'px');
        } else {
            $("#mySidenav").css('width', '500px');;
            $("#main").css('margin-right', "500px");
        }

        $("#modal-discussion-add").attr('data-lead-id', lead_id);
        $('.modal-discussion-add-span').removeClass('ti-minus');
        $('.modal-discussion-add-span').addClass('ti-plus');
        $(".add-discussion-div").addClass('d-none');
        $(".block-screen").css('display', 'block');
        $("#body").css('overflow', 'hidden');

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "/leads/getDiscussions",
            data: {
                lead_id,
                _token: csrf_token,
            },
            type: "POST",
            cache: false,
            success: function(data) {
                data = JSON.parse(data);
                if (data.status) {
                    $(".discussion-list-group").html(data.content);
                    $(".lead_id").val(lead_id);
                }
            }
        });

    }
    function closeNav() {
        $("#mySidenav").css("width", '0');
        $("#main").css("margin-right", '0');
        $("#modal-discussion-add").removeAttr('data-deal-id');
        $('.modal-discussion-add-span').removeClass('ti-minus');
        $('.modal-discussion-add-span').addClass('ti-plus');
        $(".add-discussion-div").addClass('d-none');
        $(".block-screen").css('display', 'none');
        $("#body").css('overflow', 'visible');
    }
</script>
