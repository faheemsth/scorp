
    @foreach ($notes as $note)
    <div
    style="border-top:1px solid black;border-bottom:1px solid black ">
    <div class="row my-2 justify-content-between px-4">
        <div class="col-8">
            <div class="row align-items-center">

                <div class="col-8">
                    <h4 class="mb-0">
                        {{ \App\Models\User::where('id', $note->created_by)->first()->name }}</h4>
                    <p class="mb-0">{{ optional(App\models\User::find($note->created_by))->type }}</p>

                </div>
            </div>
        </div>
        <div class="col-4 text-end">
            @php
            $dateTime = new DateTime($note->created_at);
        @endphp
        <p>{{ $dateTime->format('Y-m-d H:i:s') }}</p>
        </div>
        <div class="col-12 my-2">
            <p>{{ $note->description }}</p>
        </div>
    </div>
    <div class="d-flex gap-1 justify-content-end pb-2 px-3" id="dellhover">
        <div class="btn btn-outline-dark text-dark textareaClassedit"
            data-note="{{ $note->description }}"
            data-note-id="{{ $note->id }}"
            id="editable"
            style="font-size: ;">Edit</div>

        <div class="delete-notes btn btn-dark  text-white" id="editable"
            style="font-size: ;"
            data-note-id="{{ $note->id }}">Delete</div>
    </div>

</div>
    @endforeach

    </ul>
    <script>
        $('.textareaClassedit').click(function() {
                var dataId = $(this).data('note-id');
                var dataNote = $(this).data('note');
                $('textarea[name="description"]').val(dataNote);
                $('#note_id').val(dataId);
                $('#textareaID, #dellhover, .textareaClass').show();
                $('.textareaClass').toggle("slide");
            });
    </script>
