@forelse($tasks as $key => $task)
@php
    
    $due_date = strtotime($task->due_date);
    $current_date = strtotime(date('Y-m-d'));
    
    if ($due_date == $current_date) {
        $color_code = 'bg-warning-scorp';
    } elseif ($due_date < $current_date && strtolower($task->status) == 'on going') {
        $color_code = 'bg-danger-scorp';
    } elseif ($due_date < $current_date && strtolower($task->status) == 'completed') {
        $color_code = 'bg-danger-scorp';
    } else {
        $color_code = 'bg-success-scorp';
    }
    
@endphp
<tr>
    <td>
        <input type="checkbox" name="checkbox">
    </td>
    <td> <span
            class="badge {{ $color_code }} text-white">{{ $task->due_date }}</span>
    </td>
    <td>
        <span style="cursor:pointer" class="task-name hyper-link"
            @can('view task') onclick="openNav(<?= $task->id ?>)" @endcan
            data-task-id="{{ $task->id }}">{{ $task->name }}</span>
    </td>
    <td>
        @if (!empty($task->assigned_to))
            <span style="cursor:pointer" class="hyper-link"
                onclick="openSidebar('/users/'+{{ $task->assigned_to }}+'/user_detail')">
                {{ $users[$task->assigned_to] }}
            </span>
        @endif
    </td>

    <td>

        @if (!empty($task->assigned_to))
            @if ($task->assigned_type == 'company')
                <span style="cursor:pointer" class="hyper-link"
                    onclick="openSidebar('/users/'+{{ $task->assigned_to }}+'/user_detail')">
                    {{ $users[$task->assigned_to] }}
                </span>
            @else
                <?php
                   $assigned_user = \App\Models\User::findOrFail($task->assigned_to);
                ?>

                <span style="cursor:pointer" class="hyper-link"
                    onclick="openSidebar('/users/'+{{ $assigned_user->created_by }}+'/user_detail')">
                    {{ isset($users[$assigned_user->created_by]) ? $users[$assigned_user->created_by] : '' }}
                </span>
            @endif
        @endif
    </td>

    <td>
        @if ($task->status == 1)
            <span class="badge bg-info text-white">{{ __('Completed') }}</span>
        @else
            <span class="badge bg-success-scorp text-white">{{ __('On Going') }}</span>
        @endif
    </td>
</tr>
@empty
@endforelse