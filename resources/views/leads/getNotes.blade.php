
    @foreach ($notes as $note)
    <div
    style="border-top:1px solid black;border-bottom:1px solid black ">
    <div class="row my-2 justify-content-between px-4">
        <div class="col-12 my-2">
            <p class="text-dark" style="font-size: 18px;">{!! $note->description !!}</p>
        </div>
        <div class="col-8">
            <div class="row align-items-center">

                <div class="col-8">
                    <p class="mb-0 text-secondary">
                        {{ \App\Models\User::where('id', $note->created_by)->first()->name }}</p>
                    <p class="mb-0">{{ optional(App\models\User::find($note->created_by))->type }}</p>

                </div>
            </div>
        </div>
        <div class="col-4 text-end px-1">
            @php
            $dateTime = new DateTime($note->created_at);
        @endphp
        <p>{{ $dateTime->format('Y-m-d H:i:s') }}</p>
        </div>

    </div>
    <div class="d-flex gap-1 justify-content-end pb-2 px-3" id="dellhover">
        <div class="btn btn-outline-dark text-dark textareaClassedit"
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
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                    url: "{{ url('update/from/leadsNoteForm') }}",
                    method: 'POST',
                    data: {
                        id: dataId
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },

                    success: function(data) {
                        data = JSON.parse(data);
                        if (data.status === 'success') {
                            $("#leadsNoteForm").html('');
                            $("#leadsNoteForm").html(data.html);
                        } else {
                            console.error('Server returned an error:', data.message);
                        }


                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });

        });
    </script>
