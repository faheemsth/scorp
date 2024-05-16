<?php
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $count = ($_GET['page'] - 1) * $_GET['num_results_on_page'] + 1;
} else {
    $count = 1;
}
?>

@forelse($users as $key => $employee)
<tr>
    <td>
        <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" class="sub-check">
    </td>

    <td >
        <img class="img-fluid rounded-3 shadow-sm" src="{{ $employee->avatar ? asset('storage/uploads/avatar/' . $employee->avatar) : asset('assets/images/user/default.jpg') }}" width="50" height="50" alt="{{ $employee->avatar ? 'User Avatar' : 'Default Avatar' }}">

    </td>
    <td class="text-start">
                <span style="cursor:pointer" class="hyper-link" @can('view employee') onclick="openSidebar('/user/employee/{{ $employee->id }}/show')" @endcan>
                    {{ $employee->name }}
                </span>

    </td>
    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;"><a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></td>
    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $employee->phone }}</td>
    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $employee->type }}</td>
    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $Branchs[$employee->branch_id] ?? '' }}</td>
    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $Regions[$employee->region_id] ?? '' }}</td>
    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $brandss[$employee->brand_id] ?? '' }}</td>
    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ !empty($employee->last_login_at) ? $employee->last_login_at : '' }}
    </td>

</tr>
@empty
<tr>
    <td colspan="6">No employees found</td>
</tr>
@endforelse
