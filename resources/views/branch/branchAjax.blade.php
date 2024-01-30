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
        <td>{{ isset($branch->brands) ? \App\Models\User::where('id', $branch->brands)->first()->name : '' }}</td>
        <td>{{ !empty($regions[$branch->region_id]) ? $regions[$branch->region_id] : '' }}</td>
        <td>{{ !empty($branch->branch_manager_id) && isset($users[$branch->branch_manager_id]) ? $users[$branch->branch_manager_id] : '' }}
        </td>
        <td>{{ $branch->phone }}</td>
        <td><a href="mailto:{{ $branch->email }}">{{ $branch->email }}</a></td>
        <td><a href="{{ $branch->google_link }}">{{ $branch->google_link }}</a></td>
        <td><a href="{{ $branch->social_media_link }}">{{ $branch->social_media_link }}</a></td>
    </tr>
@endforeach
