@extends('layouts.admin')
@php
// $profile=asset(Storage::url('uploads/avatar/'));
$profile = \App\Models\Utility::get_file('uploads/avatar');
@endphp
@section('page-title')
{{ __('Manage Brand') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a>
</li>
<li class="breadcrumb-item">{{ __('Brand') }}</li>
@endsection
<style>
    .full-card {
        min-height: 165px !important;
    }

    table {
        font-size: 14px;
    }
</style>
@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<div class="row">
    <div class="col-xxl-12">
        <div class="row w-100 m-0">
            <div class="card my-card">
                <div class="card-body">
                    <div class="row align-items-center ps-0 ms-0 pe-4 my-2 justify-content-between">
                        <div class="col-2">
                            <p class="mb-0 pb-0">Brands</p>
                            <div class="dropdown">
                                <button class="All-leads" type="button">
                                    ALL BRANDS
                                </button>
                            </div>
                        </div>
                        <div class="col-8 d-flex justify-content-end gap-2">
                            <div class="input-group w-25 rounded"  style="width:36px; height: 36px; margin-top:10px;">
                                <button class="btn list-global-search-btn p-0 pb-2 ">
                                    <span class="input-group-text bg-transparent border-0  px-1" id="basic-addon1">
                                        <i class="ti ti-search" style="font-size: 18px"></i>
                                    </span>
                                </button>
                                <input type="Search" class="form-control border-0 bg-transparent p-0 pb-2 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                            </div>

                            @if(\Auth::user()->type == 'super admin'|| \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'HR' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
                            <button class="btn filter-btn-show p-2 btn-dark" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false" style="width:36px; height: 36px; margin-top:10px;">
                                <i class="ti ti-filter" style="font-size:18px"></i>
                            </button>
                            @endif

                            @can('create user')
                            <a href="#" data-size="lg" data-url="{{ route('users.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create') }}" class="btn btn-dark px-2 py-2" style="width:36px; height: 36px; margin-top:10px;">
                                <i class="ti ti-plus "></i>
                            </a>
                            @endcan

                            @if(auth()->user()->type == 'super admin' || auth()->user()->type == 'Admin Team')
                            <a href="{{ route('users.download') }}" class="btn  btn-dark px-0" style="color:white; width:36px; height: 36px; margin-top:10px;" data-bs-toggle="tooltip" title="" data-original-title="Download in Csv" class="btn  btn-dark px-0">
                                <i class="ti ti-download" style="font-size:18px"></i>
                            </a>
                            @endif

                            @if(auth()->user()->type == 'super admin' || auth()->user()->can('delete user'))
                            <a href="javascript:void(0)" id="actions_div" class="btn p-2 d-none delete-bulk btn-dark" style="color:white; width:36px; height: 36px; margin-top:10px;" data-bs-toggle="tooltip" title="" data-original-title="Delete in bulk">
                                <i class="ti ti-trash"></i>
                            </a>
                            @endif

                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $("#dropdownMenuButton3").click(function() {
                                $("#filterToggle").toggle();
                            });
                        });
                    </script>
                    {{-- Filters --}}


                    @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'HR' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
                    <div class="filter-data px-3" id="filterToggle" <?= isset($_GET['Brand']) || isset($_GET['Director']) ? '' : 'style="display: none;"' ?>>
                        <form action="/users" method="GET" class="">
                            @php
                            $userType = \Auth::user()->type;
                            @endphp

                            <div class="row mb-2 align-items-end">
                                @if($userType == 'super admin'|| $userType == 'Admin Team' || $userType == 'HR' || $userType == 'Project Director' || $userType == 'Project Manager' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
                                <div class="col-md-3 mt-2">
                                    <label for="">Brand</label>
                                    <select name="Brand" class="form form-control select2" id="filter_brand">
                                        <option value="">Select Option</option>
                                        @if (!empty($Brands))
                                        @foreach (BrandsRegionsBranches()['brands'] as $key => $Brand)
                                        <option value="{{ $key }}" {{ !empty($_GET['Brand']) && $_GET['Brand'] == $key ? 'selected' : '' }}>{{ $Brand }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @endif

                                @if($userType == 'super admin')
                                <div class="col-md-3 mt-2">
                                    <label for="">Project Director</label>
                                    <select name="Director" class="form form-control select2" id="project_director">
                                        <option value="">Select Option</option>
                                        @if (!empty($ProjectDirector))
                                        @foreach ($ProjectDirector as $key => $ProjectDirect)
                                        <option value="{{ $key }}" {{ !empty($_GET['Director']) && $_GET['Director'] == $key ? "selected" : "" }}>{{ $ProjectDirect }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @endif

                                <div class="col-md-4 mt-2">

                                    <input type="submit" class="btn me-2 bg-dark p-2" style=" color:white;">
                                    <a href="/users" class="btn bg-dark p-2" style="color:white;">Reset</a>
                                </div>
                            </div>

                            <!-- Uncommented and corrected the following block -->
                            <div class="row d-none">
                                <div class="enries_per_page" style="max-width: 300px; display: flex;">
                                    <?php
                                    $all_params = isset($_GET) ? $_GET : '';
                                    if (isset($all_params['num_results_on_page'])) {
                                        unset($all_params['num_results_on_page']);
                                    }
                                    ?>
                                    <input type="hidden" value="<?= http_build_query($all_params) ?>" class="url_params">
                                    <select name="" id="" class="enteries_per_page form form-control select2 " style="width: 100px; margin-right: 1rem;">
                                        <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 25 ? 'selected' : '' ?> value="25">25</option>
                                        <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 100 ? 'selected' : '' ?> value="100">100</option>
                                        <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 300 ? 'selected' : '' ?> value="300">300</option>
                                        <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == 1000 ? 'selected' : '' ?> value="1000">1000</option>
                                        <option <?= isset($_GET['num_results_on_page']) && $_GET['num_results_on_page'] == $total_records ? 'selected' : '' ?> value="{{ $total_records }}">all</option>
                                    </select>
                                    <span style="margin-top: 5px;">entries per page</span>
                                </div>
                            </div>
                            <!-- End of uncommented and corrected block -->
                        </form>
                    </div>
                    @endif


                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 50px !important;">
                                        <input type="checkbox" class="main-check">
                                    </th>
                                    <th>Name</th>
                                    <th>Website Link</th>
                                    <th>Project Director</th>
                                </tr>
                            </thead>

                            <tbody class="list-div">
                                @forelse($users as $key => $user)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="brand_ids[]" value="{{ $user->id }}" class="sub-check">
                                    </td>
                                    <td style="max-width: 130px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">

                                        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/{{ $user->id }}/user_detail')">
                                            {{ $user->name }}
                                        </span>
                                    </td>
                                    <td style="max-width: 130px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;"><a href="{{ $user->website_link }}">{{ $user->website_link }}</a></td>
                                    <td style="max-width: 130px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                        @php
                                            // $project_director = \App\Models\User::join('company_permission', 'company_permission.user_id', '=', 'users.id')
                                            //                     ->where('company_permission.permitted_company_id', $user->id)
                                            //                     ->where('type', 'Project Director')
                                            //                     ->first();
                                        @endphp
                                        {{-- {{ $project_director->name ?? '' }} --}}

                                        {{ $user->project_director}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">No employees found</td>
                                </tr>
                                @endforelse
                            </tbody>


                        </table>

                        <div class="pagination_div">
                            @if ($total_records > 0)
                            @include('layouts.pagination', [
                            'total_pages' => $total_records,
                            'num_results_on_page' => 25,
                            ])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.filter-btn-show').click(function() {
        $("#filter-show").toggle();
    });



    // Attach an event listener to the input field
    $('.list-global-search').keypress(function(e) {

        // Check if the pressed key is Enter (key code 13)
        if (e.which === 13) {
            var search = $(".list-global-search").val();
            var ajaxCall = 'true';
            $(".list-div").html('Loading...');
            $.ajax({
                type: 'GET',
                url: "{{ route('users.index') }}",
                data: {
                    search: search,
                    ajaxCall: ajaxCall
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        console.log(data.html);
                        $(".list-div").html(data.html);
                        $(".pagination_div").html(data.pagination_html);
                    }
                }
            })
        }
    });


    // Attach an event listener to the input field
    $('.list-global-search-btn').click(function(e) {

        var search = $(".list-global-search").val();
        var ajaxCall = 'true';
        $(".list-div").html('Loading...');

        $.ajax({
            type: 'GET',
            url: "{{ route('users.index') }}",
            data: {
                search: search,
                ajaxCall: ajaxCall
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 'success') {
                    console.log(data.html);
                    $(".list-div").html(data.html);
                }
            }
        })
    });


    $(document).on('change', '.main-check', function() {
        $(".sub-check").prop('checked', $(this).prop('checked'));

        var selectedIds = $('.sub-check:checked').map(function() {
            return this.value;
        }).get();

        // console.log(selectedIds.length)

        if (selectedIds.length > 0) {
            selectedArr = selectedIds;
            $("#actions_div").removeClass('d-none');
        } else {
            selectedArr = selectedIds;

            $("#actions_div").addClass('d-none');
        }
    });

    $(document).on('change', '.sub-check', function() {
        var selectedIds = $('.sub-check:checked').map(function() {
            return this.value;
        }).get();

        // console.log(selectedIds.length)

        if (selectedIds.length > 0) {
            selectedArr = selectedIds;
            $("#actions_div").removeClass('d-none');
        } else {
            selectedArr = selectedIds;

            $("#actions_div").addClass('d-none');
        }
        let commaSeperated = selectedArr.join(",");
        //console.log(commaSeperated)
        //$("#region_ids").val(commaSeperated);

    });


    $(document).on("click", '.delete-bulk', function() {
        var task_ids = $(".sub-check:checked");
        var selectedIds = $('.sub-check:checked').map(function() {
            return this.value;
        }).get();

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/delete-bulk-brands?ids=' + selectedIds.join(',');
            }
        });
    })

    $(document).on("submit", "#update-brand", function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Serialize form data
        var formData = $(this).serialize();

        $(".update-brand").text('Updating...');
        $(".update-brand").prop("disabled", true);

        // AJAX request
        $.ajax({
            type: "POST",
            url: $(this).attr("action"), // Form action URL
            data: formData, // Serialized form data
            success: function(response) {
              data = JSON.parse(response);

              if(data.status == 'success'){
                show_toastr('Success', data.msg, 'success');
                  $('#commonModal').modal('hide');
                  $(".modal-backdrop").removeClass("modal-backdrop");
                  $(".block-screen").css('display', 'none');
                  $(".update-brand").text('Update');
                  $(".update-brand").prop("disabled", false);
                  openSidebar('/users/'+data.id+'/user_detail');
              }else{
                $(".update-brand").text('Update');
                $(".update-brand").prop("disabled", false);
                show_toastr('Error', data.msg, 'error');
              }

            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        });
    });


    $(document).on("submit", "#create-brand", function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Serialize form data
        var formData = $(this).serialize();

         // Change button text and disable it
        $(".create-brand").text('Creating...').prop("disabled", true);

        // AJAX request
        $.ajax({
            type: "POST",
            url: $(this).attr("action"), // Form action URL
            data: formData, // Serialized form data
            success: function(response) {
              data = JSON.parse(response);

              if(data.status == 'success'){
                show_toastr('Success', data.msg, 'success');
                  $('#commonModal').modal('hide');
                  $(".modal-backdrop").removeClass("modal-backdrop");
                  $(".block-screen").css('display', 'none');
                   // Change button text and disable it
                  $(".create-brand").text('Create').prop("disabled", false);
                  openSidebar('/users/'+data.id+'/user_detail');
              }else{
                $(".create-brand").text('Create').prop("disabled", false);
                show_toastr('Error', data.msg, 'error');
              }

            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        });
    });
</script>
@endsection
