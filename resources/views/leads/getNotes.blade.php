@forelse($notes as $note)
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

        <a data-url="{{ route('leads.notes.edit', $note->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Notes Edit') }}" class="btn btn-sm text-white mx-2" style="background-color: #b5282f;">
            <i class="ti ti-pencil "></i>
        </a>

        <a href="javascript:void(0)" class="btn btn-sm text-white delete-notes" data-note-id="{{$note->id}}" style="background-color: #b5282f;">
            <i class="ti ti-trash "></i>
        </a>
    </td>

</tr>
@empty


@endforelse