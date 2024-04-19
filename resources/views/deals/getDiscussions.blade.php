@foreach ($discussions as $discussion)
<div
    style="border-top:1px solid black;border-bottom:1px solid black ">
    <div class="row my-2 justify-content-between px-4">
        <div class="col-8">
            <div class="row align-items-center">
                <div class="col-2 text-center">
                    <img src="@if ($discussion['avatar'] && $discussion['avatar'] != '') {{ asset('/storage/uploads/avatar/' . $discussion['avatar']) }} @else {{ asset('/storage/uploads/avatar/avatar.png') }} @endif"
                        style="width: 70px;height:70px;border-radius:50%;">
                </div>
                <div class="col-6">
                    <h4 class="mb-0">{{ $discussion['name'] }}</h4>
                    <p class="mb-0">{{ optional(App\models\User::find(optional(App\models\TaskDiscussion::find($discussion['id']))->created_by))->type }}</p>

                </div>
            </div>
        </div>
        <div class="col-4 text-end">
            @php
                $dateTime = new DateTime($discussion['created_at']);
            @endphp
            <p>{{ $dateTime->format('Y-m-d H:i:s') }}</p>
        </div>
        <div class="col-12 my-2">
            <p>{{ $discussion['comment'] }}</p>
        </div>
    </div>
    <div class="d-flex gap-1 justify-content-end pb-2 px-3" id="dellhover">
        <div class="btn btn-sm btn-outline-dark text-dark textareaClassedit"
            data-comment="{{ $discussion['comment'] }}"
            data-id="{{ $discussion['id'] }}"
            id="editable"
            style="font-size: ;">Edit</div>

        <div class="btn btn-dark btn-sm text-white" id="editable"
            style="font-size: ;"
            onclick="DeleteComment('{{ $discussion['id'] }}','{{ App\Models\TaskDiscussion::find($discussion['id'])->task_id }}')">Delete</div>
    </div>

</div>
@endforeach
<script>
    $(document).ready(function() {

        $('.textareaClassedit').click(function() {
            var dataId = $(this).data('id');
            var dataComment = $(this).data('comment');
            $('textarea[name="comment"]').val(dataComment);
            $('#id').val(dataId);
            $('#textareaID, #dellhover, .textareaClass').show();
            $('.textareaClass').toggle("slide");
        });

    });
</script>
