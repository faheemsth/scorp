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
     #myDIV{
        background-color: #CCC;
        z-index: 1021;
    }
</style>

<div id="wrapper" id="savefilter">

    <div class="sidebar" id="myDIV" style="display: none;" >
        @foreach($filters as $filter)
            <p>
                <a href="{{$filter->url}}">{{$filter->filter_name}}</a> {{$filter->module}} ({{$filter->count}})
                <a onclick="deleteFilter(`{{$filter->id}}`)" class="btn px-2 btn-danger text-white" style="float:right">
                    <i class="ti ti-trash "></i>
                </a>
            </p>
        @endforeach
    </div>
    <div class="modal" id="save-filter-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg my-0" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Save Filter</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="min-height: 40vh;">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="filter_name" class="form-label text-dark">Filter Name</label>
                            <input type="text" class="form-control" id="filter_name" name="filter_name" value="" required="">
                        </div>
                        <input type="text" hidden class="form-control" id="module" name="module" value="" >
                        <input type="number" hidden class="form-control" id="count" name="count" value="0" >

                    </div>

                </div>
                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark px-2" onclick="storeFilter()">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script-page')

<script>

function storeFilter(){

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

function saveFilter(mod,count){
    $('#module').val(mod);
    $('#count').val(count);

    $('#save-filter-modal').modal('show')
}

function deleteFilter(id){
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        type: "POST",
        url: "{{ route('delete-filter') }}",
        data: {
            id: id,
            _token: csrf_token,
        },
        success: function(data) {            
            show_toastr('{{__("success")}}', 'Filter deleted successfully!', 'success');
            location.reload();
            if (data.status == 'success') {
                
            } else {
                
            }
        }
    });
}

</script>

@endpush

