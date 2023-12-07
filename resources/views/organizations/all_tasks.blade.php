@forelse($tasks as $task)
<tr style="font-size: 14px;">
    <td>{{$task->name}}</td>
    <td>{{ isset($task->assigned_to) ? \App\Models\User::where('id', $task->assigned_to)->first()->name : ''}}</td>
    <td>{{$task->status}}</td>
    <td>{{$task->start_date}}</td>
    <td>{{$task->due_date}}</td>
    <td class="d-flex">

        <a data-size="lg" data-url="{{ route('organiation.tasks.edit', $task->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Drive Link') }}" class="btn btn-sm text-white mx-2" style="background-color: #b5282f;">
            <i class="ti ti-pencil "></i>
        </a>

        <a data-size="lg" href="javascript:void(0)" class="btn btn-sm text-white delete-task" data-task-id="{{$task->id}}" style="background-color: #b5282f;">
            <i class="ti ti-trash "></i>
        </a>
    </td>
</tr>
@empty

@endforelse