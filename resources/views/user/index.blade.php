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
                    <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                        <div class="col-2">
                            <p class="mb-0 pb-0">Brands</p>
                            <div class="dropdown">
                                <button class="All-leads" type="button">
                                    ALL BRAND
                                </button>
                            </div>
                        </div>
                        <div class="col-10 d-flex justify-content-end gap-2">
                            <div class="input-group w-25 rounded">
                                <button class="btn list-global-search-btn px-0 ">
                                    <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                        <i class="ti ti-search" style="font-size: 18px"></i>
                                    </span>
                                </button>
                                <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                            <button class="btn filter-btn-show p-2 btn-dark" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-filter" style="font-size:18px"></i>
                            </button>
                            @can('create user')
                            <a href="#" data-size="lg" data-url="{{ route('users.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create') }}" class="btn btn-dark px-2 py-2">
                                <i class="ti ti-plus "></i>
                            </a>
                            @endcan
                            
                            <a href="http://127.0.0.1:8000/university-download" class="btn p-2 btn-dark" style="color:white;" data-bs-toggle="tooltip" title="" data-original-title="Download in Csv">
                                <i class="ti ti-download" style="font-size:18px"></i>
                            </a>
                            
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
                    <div class="filter-data px-3" id="filterToggle" <?= isset($_GET['Brand']) || isset($_GET['Director']) ? '' : 'style="display: none;"' ?>>
                        <form action="/users" method="GET" class="">
                            <div class="row my-3 align-items-end">
                                <div class="col-md-4 mt-2">
                                    <label for="">Brand</label>
                                    <select name="Brand" class="form form-control select2" id="">
                                        <option value="">Select Option</option>
                                        @if (!empty($Brands))
                                        @foreach ($Brands as $key => $Brand)
                                        <option value="{{ $key }}" {{ !empty($_GET['Brand']) && $_GET['Brand'] == $key ? 'selected' : '' }}>{{ $Brand }}</option>
                                        @endforeach
                                        @endif

                                    </select>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label for="">Project Director</label>
                                    <select name="Director" class="form form-control select2" id="">
                                        <option value="">Select Option</option>
                                        @if (!empty($ProjectDirector))
                                        @foreach ($ProjectDirector as $key => $ProjectDirect)
                                        <option value="{{ $key }}" {{ !empty($_GET['Director']) && $_GET['Director'] == $key ? "selected" : "" }}>{{ $ProjectDirect }}</option>
                                        @endforeach
                                        @endif

                                    </select>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <br>
                                    <input type="submit" class="btn me-2 bg-dark" style=" color:white;">
                                    <a href="/users" class="btn bg-dark" style="color:white;">Reset</a>
                                </div>
                            </div>
                            <!-- <div class="row">
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
                            </div> -->
                        </form>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Website Link</th>
                                    <th>Project Director</th>
                                </tr>
                            </thead>

                            <tbody class="list-div">
                                @forelse($users as $key => $user)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">

                                        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/{{ $user->id }}/user_detail')">
                                            {{ $user->name }}
                                        </span>
                                    </td>
                                    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;"><a href="{{ $user->website_link }}">{{ $user->website_link }}</a></td>
                                    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ !empty($user->project_director_id) && isset($projectDirectors[$user->project_director_id]) ? $projectDirectors[$user->project_director_id] : '' }}</td>
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
</script>
@endsection