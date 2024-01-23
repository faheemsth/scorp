<?php
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $count = ($_GET['page'] - 1) * $_GET['num_results_on_page'] + 1;
} else {
    $count = 1;
}
?>

@forelse($users as $key => $employee)
    <tr>
        <td>{{ $count++ }}</td>
        <td>

            <span style="cursor:pointer" class="hyper-link"
                @can('view employee') onclick="openSidebar('/user/employee/{{ $employee->id }}/show')" @endcan>
                {{ $employee->name }}
            </span>
        </td>
        <td><a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></td>
        <td>{{ $employee->type }}</td>
        <td>{{ $employee->phone }}</td>
        <td>{{ $Regions[$employee->region_id] ?? '' }}</td>
        <td>{{ !empty($employee->last_login_at) ? $employee->last_login_at : '' }}
        </td>

    </tr>
@empty
    <tr>
        <td colspan="6">No employees found</td>
    </tr>
@endforelse
