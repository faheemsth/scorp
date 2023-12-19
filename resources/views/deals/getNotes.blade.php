{{-- @forelse($notes as $note)
<tr>
    <td>{{ $note->title }}
    </td>
    <td>{{ $note->description }}
    </td>
    <td>{{ $note->created_at }}
    </td>
    <td>{{ \App\Models\User::where('id', $note->created_by)->first()->name }}
    </td>
    <td class="d-flex">

        <a data-url="{{ route('deals.notes.edit', $note->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Notes Edit') }}" class="btn btn-sm text-white mx-2" style="background-color: #b5282f;">
            <i class="ti ti-pencil "></i>
        </a>

        <a href="javascript:void(0)" class="btn btn-sm text-white delete-notes" data-note-id="{{$note->id}}" style="background-color: #b5282f;">
            <i class="ti ti-trash "></i>
        </a>
    </td>

</tr>
@empty


@endforelse --}}

<ul class="list-group list-group-flush mt-2">

    @foreach ($notes as $note)
        <li class="list-group-item px-3"
            id="lihover">
            <div class="d-block d-sm-flex align-items-start">
                <div class="w-100">
                    <div
                        class="d-flex align-items-center justify-content-between">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="mb-0">
                                {{ $note->description }}
                            </h5>
                            <span
                                class="text-muted text-sm">{{ $note->created_at }}
                            </span><br>
                            <span
                                class="text-muted text-sm"><i class="step__icon fa fa-user" aria-hidden="true"></i>{{ \App\Models\User::where('id', $note->created_by)->first()->name }}
                            </span>
                        </div>

                        <style>
                            #editable {
                                display: none;
                            }

                            #lihover:hover #editable {
                                display: flex;
                            }
                        </style>
                        <div class="d-flex gap-3"
                            id="dellhover">
                            <i class="ti ti-pencil textareaClassedit"
                                data-note="{{ $note->description }}"
                                data-note-id="{{ $note->id }}"
                                id="editable"
                                style="font-size: 20px;cursor:pointer;"></i>
                            <script></script>
                            <i class="ti ti-trash delete-notes"
                                id="editable"
                                data-note-id="{{ $note->id }}"
                                style="font-size: 20px;cursor:pointer;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </li>
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