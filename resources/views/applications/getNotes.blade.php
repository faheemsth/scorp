<ul class="list-group list-group-flush mt-2">

    @foreach ($notes as $note)
        <li class="list-group-item px-3"
            id="lihover">
            <div class="d-block d-sm-flex align-items-start">
                <div class="w-100">
                    <div
                        class="d-flex align-items-center justify-content-between">
                        <div class="mb-3 mb-sm-0">
                              <p class="">
                              {{ $note->description }}
                              </p>
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
