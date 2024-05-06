<!-- Modal for Tags Creating -->
<div class="modal fade" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Tag</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('lead_tags') }}" method="POST" id="addTagForm">
                <div class="modal-body">
                    <input type="hidden" value="" name="selectedIds" id="selectedIds">

                    <div class="form-group">
                        <label for="">Tag</label>
                        <select class="form form-control select2 selectTage" name="tagid" id="tagSelect" style="width: 95%;">
                            <option value="">Select Tag</option>
                            @foreach ($tags as $key => $tag)
                            @if (!empty($tag))
                            <option value="{{ $tag }}" <?= isset($_GET['tag']) && $key == $_GET['tag'] ? 'selected' : '' ?> class="">{{ $key }}</option>
                            @endif
                            @endforeach
                            {{-- @if(\Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'Project Director' || \Auth::user()->type == 'Project Manager') --}}
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-dark add-tags">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal for Importing Csv-->
<div class="modal fade" style="z-index: 9999999; overflow: scroll;" id="import_csv" tabindex="-1" aria-labelledby="import_csv Label" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="import_csvLabel">Leads import</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ url('leads/import-csv') }}" method="POST" id="importCsvForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body pt-0">
                    <div class="lead-content my-2" style="max-height: 100%; overflow-y: scroll;">
                        <div class="card-body px-2 py-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-groups mt-2 ps-2">
                                        <label for="extension" class="form-label">Extension</label>
                                        <select type="file" class="form-control" name="extension" id="extension" required>
                                            <option value="">Select type</option>
                                            <option value="csv">CSV</option>
                                            <option value="excel">Excel</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-groups mt-2 pe-3">
                                        <label for="lead-file" class="form-label">{{ __('Column') }}</label>
                                        <input type="file" name="leads_file" id="lead-file" class="form-control" accept="*" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mt-2 columns-matching">
                                    <!-- Put any additional form elements here, if needed -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-dark submit_btn" id="submit_btn">{{ __('Create') }}</button>
                </div>
            </form>


        </div>
    </div>
</div>


<!-- Modal for Tags Updating -->
<div class="modal" id="UpdateTageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tags Update</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form action="{{ route('lead_tags') }}" method="POST" id="UpdateTagForm">
                <div class="modal-body" id="sheraz">

                </div>
                <br>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-dark px-2 Update" value="Update">
                    <a class="btn btn-danger text-white" onclick="deleteTage()">Delete</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Bulk Assign -->
<div class="modal" id="massAssignModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md my-0" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mass Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('update-bulk-leads') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row" id="bulk-assign">
                        @if(\Auth::user()->can('level 1') || \Auth::user()->can('level 2'))
                        <div class="col-md-12 mt-2" id="brand_id_div">
                            <label for="">Brand</label>
                            <select name="brand" class="form form-control select2" id="filter_brand_id">
                                @foreach ($filters['brands'] ?? [] as $key => $Brand)
                                <option value="{{ $key }}" {{ !empty($_GET['brand']) && $_GET['brand'] == $key ? 'selected' : '' }}>{{ $Brand }}</option>
                                @endforeach
                                @empty($filters['brands'])
                                <option value="" disabled>No brands available</option>
                                @endempty
                            </select>
                        </div>
                        @endif

                        @if(\Auth::user()->type == 'company' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3'))
                        <div class="col-md-12 mt-2" id="region_bulkassign_div">
                            <label for="">Region</label>
                            <select name="region_id" class="form form-control select2" id="filter_region_id">
                                @foreach ($filters['regions'] ?? [] as $key => $region)
                                <option value="{{ $key }}" {{ !empty($_GET['region_id']) && $_GET['region_id'] == $key ? 'selected' : '' }}>{{ $region }}</option>
                                @endforeach
                                @empty($filters['regions'])
                                <option value="" disabled>No regions available</option>
                                @endempty
                            </select>
                        </div>
                        @endif

                        @if(\Auth::user()->type == 'company' || \Auth::user()->can('level 1') || \Auth::user()->can('level 2') || \Auth::user()->can('level 3') || \Auth::user()->can('level 4'))
                        <div class="col-md-12 mt-2" id="branch_bulkassign_div">
                            <label for="">Branch</label>
                            <select name="branch_id" class="form form-control select2" id="filter_branch_id">
                                @foreach ($filters['branches'] ?? [] as $key => $branch)
                                <option value="{{ $key }}" {{ !empty($_GET['branch_id']) && $_GET['branch_id'] == $key ? 'selected' : '' }}>{{ $branch }}</option>
                                @endforeach
                                @empty($filters['branches'])
                                <option value="" disabled>No regions available</option>
                                @endempty
                            </select>
                        </div>
                        @endif

                        <div class="col-md-12 mt-2"> <label for="">Assigned To</label>
                            <div class="" id="bulkassign_to_div">
                                <select name="lead_assgigned_user" id="choices-multiple333" class="form form-control select2" style="width: 95%;">
                                    @foreach ($filters['employees'] ?? [] as $key => $user)
                                    <option value="{{ $key }}" <?= isset($_GET['lead_assgigned_user']) && $key == $_GET['lead_assgigned_user'] ? 'selected' : '' ?> class="">{{ $user }}</option>
                                    @endforeach
                                    <option value="null">Not Assign</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" class="" id="mySelectedIds" name="selectedIds">

                    </div>
                </div>
                <br>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-dark px-2" value="Update">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
