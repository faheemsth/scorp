@php
use App\Models\Utility;
// $logo=asset(Storage::url('uploads/logo/'));
$logo = \App\Models\Utility::get_file('uploads/logo/');
$company_logo = Utility::getValByName('company_logo_dark');
$company_logos = Utility::getValByName('company_logo_light');
$company_small_logo = Utility::getValByName('company_small_logo');
$setting = \App\Models\Utility::colorset();
$mode_setting = \App\Models\Utility::mode_layout();
$emailTemplate = \App\Models\EmailTemplate::first();
$lang = Auth::user()->lang;
$filters = \App\Models\SavedFilter::where('created_by',\Auth::user()->id)->get();
@endphp
<style>
    #myDIV {
        background-color: #fff;
        box-shadow: 2px 0px 3px gray;
        z-index: 1021;
    }

    .dright {
        position: absolute;
        inset: 0px auto auto 0px;
        transform: translate(-143px, 34px) !important;
    }
</style>

<div id="wrapper" id="savefilter">

    <div class="sidebar" id="myDIV" style="display: none;">
        <h5 class="fw-bold px-2 py-2">Save Filter List</h5>
        <ul style="list-style: none;">
            @foreach($filters as $filter)
            {{-- <p>
                <a href="{{$filter->url}}">{{$filter->filter_name}}</a> {{$filter->module}} ({{$filter->count}})
            <a onclick="deleteFilter(`{{$filter->id}}`)" class="btn px-2 btn-danger text-white" style="float:right">
                <i class="ti ti-trash "></i>
            </a>
            </p> --}}
            <li class="px-2 py-2 d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{$filter->url}}" class="text-capitalize fw-bold text-dark">{{$filter->filter_name}}</a>
                    <span class="text-dark"> {{$filter->module}} ({{$filter->count}})</span>
                </div>
                <div class="dropdown">
                    <button class="btn bg-transparent" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-vertical" style="color: #000000;"></i>
                    </button>
                    <ul class="dropdown-menu dright" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#">Rename</a></li>
                        <li><a class="dropdown-item" onclick="deleteFilter(`{{$filter->id}}`)" href="#">Delete</a></li>
                    </ul>
                </div>
            </li>
            @endforeach
        </ul>
        <!-- <button data-bs-toggle="tooltip" title="" class="btn px-2 pb-2 pt-2 refresh-list btn-dark" data-original-title="Refresh"><i class="ti ti-refresh" style="font-size: 18px"></i></button> -->
    </div>
    <div class="modal" id="save-filter-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog my-0" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Save Filter</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label for="filter_name" class="form-label text-dark">Filter Name</label>
                            <input type="text" class="form-control" id="filter_name" name="filter_name" value="" required="">
                        </div>
                        <div class="row justify-content-end">
                            <input type="text" hidden class="form-control" id="module" name="module" value="">
                            <input type="number" hidden class="form-control" id="count" name="count" value="0">
                        </div>

                    </div>

                </div>
                <br>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-dark px-2 mx-2" onclick="storeFilter()">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal" id="edit-filter-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog my-0" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Filter</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label for="filter_name" class="form-label text-dark">Filter Name</label>
                            <input type="text" class="form-control" id="edit_filter_name" name="filter_name" value="" required="">
                        </div>
                        <div class="row justify-content-end">
                            <input type="text" hidden class="form-control" id="module" name="module" value="">
                            <input type="number" hidden class="form-control" id="count" name="count" value="0">
                            <input type="hidden" class="" id="filter_id" name="id" value="">
                        </div>

                    </div>

                </div>
                <br>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-dark px-2 mx-2" onclick="updateFilter()">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script-page')

<script>
    function storeFilter() {

        let url = window.location.href;
        let name = $('#filter_name').val();
        let count = $('#count').val();
        let mod = $('#module').val();

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: "{{ route('save-filter') }}",
            data: {
                url: url,
                filter_name: name,
                module: mod,
                count: count,
                _token: csrf_token,
            },
            success: function(data) {
                $('#save-filter-modal').modal('hide')
                show_toastr('{{__("success")}}', 'Filter saved successfully!', 'success');
                location.reload();
                if (data.status == 'success') {

                } else {

                }
            }
        });

    }


    function updateFilter() {

        let url = window.location.href;
        let name = $('#edit_filter_name').val();
        var id = $('#filter_id').val();
        // let count = $('#count').val();
        // let mod = $('#module').val();

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: "{{ route('edit-filter') }}",
            data: {
                filter_name: name,
                id: id,
                _token: csrf_token,
                url: url
            },
            success: function(data) {
                $('#edit-filter-modal').modal('hide')
                show_toastr('{{__("success")}}', 'Filter updated successfully!', 'success');
                location.reload();
                if (data.status == 'success') {

                } else {

                }
            }
        });

    }

    function saveFilter(mod, count) {
        $('#module').val(mod);
        $('#count').val(count);

        $('#save-filter-modal').modal('show')
    }

    function editFilter(name, id) {
        $('#edit_filter_name').val(name);
        $("#filter_id").val(id);

        $('#edit-filter-modal').modal('show')
    }

    // function deleteFilter(id){
    //     var csrf_token = $('meta[name="csrf-token"]').attr('content');

    //     $.ajax({
    //         type: "POST",
    //         url: "{{ route('delete-filter') }}",
    //         data: {
    //             id: id,
    //             _token: csrf_token,
    //         },
    //         success: function(data) {
    //             show_toastr('{{__("success")}}', 'Filter deleted successfully!', 'success');
    //             location.reload();
    //             if (data.status == 'success') {

    //             } else {

    //             }
    //         }
    //     });
    // }


    function deleteFilter(id) {
        // Prompt the user for confirmation using SweetAlert
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, proceed with the AJAX request
                $.ajax({
                    type: "POST",
                    url: "{{ route('delete-filter') }}",
                    data: {
                        id: id,
                        _token: csrf_token,
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            show_toastr('{{__("success")}}', 'Filter deleted successfully!', 'success');
                            location.reload();
                        } else {
                            // Handle other cases if needed
                        }
                    }
                });
            }
        });
    }
</script>

@endpush