@foreach($discussions as $discussion)
<li class="list-group-item px-3">
    <div class="d-block d-sm-flex align-items-start">
        <img src="@if($discussion['avatar'] && $discussion['avatar'] != '') {{asset('/storage/uploads/avatar/'.$discussion['avatar'])}} @else {{asset('/storage/uploads/avatar/avatar.png')}} @endif" class="img-fluid wid-40 me-3 mb-2 mb-sm-0" alt="image">
        <div class="w-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="mb-3 mb-sm-0">
                    <h5 class="mb-0"> {{$discussion['comment'] }}</h5>
                    <span class="text-muted text-sm">{{$discussion['name']}}</span>
                </div>
                <div class=" form-switch form-switch-right mb-4">
                    {{$discussion['created_at']}}
                </div>
            </div>
        </div>
    </div>
</li>
@endforeach