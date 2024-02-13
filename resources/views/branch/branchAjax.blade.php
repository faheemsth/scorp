@foreach ($branches as $branch)
<tr>
    <td>
        <input type="checkbox" name="branch_ids[]" value="{{ $branch->id }}" class="sub-check">
    </td>

    <td>
        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/branch/{{ $branch->id }}/show')">
            {{ $branch->name }}
        </span>
    </td>

    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;"><a href="mailto:{{ $branch->email }}">{{ $branch->email }}</a></td>
    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ $branch->phone }}</td>
    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ !empty($branch->branch_manager_id) && isset($users[$branch->branch_manager_id]) ? $users[$branch->branch_manager_id] : '' }}</td>
    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ !empty($regions[$branch->region_id]) ? $regions[$branch->region_id] : '' }}</td>

    <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">{{ isset($branch->brands) ? \App\Models\User::where('id', $branch->brands)->first()->name : '' }}</td>
    
</tr>
@endforeach