@forelse($Notifications as $key => $task)
@php

    $due_date = strtotime($task->created_at);
    $current_date = strtotime(date('Y-m-d'));
    $color_code = '';

    if ($due_date > $current_date) {
        $color_code = 'green';
    } elseif ($due_date === $current_date) {
        $color_code = '#E89D25';
    } elseif ($due_date < $current_date) {
        $color_code = 'red';
    } elseif ($status === '1') {
        $color_code = 'green';
    }

@endphp
<tr class="<?php echo (!empty($_GET['id']) && $_GET['id'] == $task->id) ? 'bg-info' : ''; ?>">
    <td>
        <input type="checkbox" name="tasks[]" value="{{ $task->id }}"
            class="sub-check">
    </td>
    <td
        style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
        <span class="badge text-white" style="background-color:{{ $color_code }}">
            <?php
            $date = new DateTime($task->created_at);
            $formattedDate = $date->format('Y-m-d');
            echo $formattedDate;
            ?>
        </span>
    </td>

    <td
        style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
        <span style="cursor:pointer" class="task-name hyper-link"
            @can('view task') onclick="openSidebar('/get-task-detail?task_id=<?= $task->id ?>')" @endcan
            data-task-id="{{ $task->id }}">{{ $task->Notifier->name }}</span>
    </td>
    <td
        style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">

        <span class="hyper-link">
            {{ $task->type }}
        </span>

    </td>

    <td
        style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">


        <span class="hyper-link">
            {{ $task->data }}
        </span>




    </td>



    <td
        style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
        @if ($task->is_read == 0)
            <span class="badge  text-white"
                style="background-color:#cd9835">{{ __('Unseen') }}</span>
        @else
            <span class="badge text-white"
                style="background: green; ">{{ __('Seen') }}</span>
        @endif
    </td>

    <td>
        @if ($task->status == 0)
            <button class="btn btn-sm btn-dark position-relative"
                @can('edit status task') onclick="ChangeNotificationStatus({{ $task->id }})" @endcan
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="Change Task Status">
                <i class="fa-solid fa-check d-flex justify-content-center align-items-center"
                    style="font-size: 18px;"></i>
            </button>
        @else
            <span class="badge text-white"
                style="background: green; ">{{ __('Completed') }}</span>
        @endif
    </td>


</tr>
@empty
<tr>
    <td class="7">No Record Found!!!</td>
</tr>
@endforelse
