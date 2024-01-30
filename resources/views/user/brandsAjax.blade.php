@forelse($users as $key => $user)
<tr>
    <td>
        <input type="checkbox" name="brand_ids[]" value="{{ $user->id }}" class="sub-check">
    </td>
    <td>

        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/{{ $user->id }}/user_detail')">
            {{ $user->name }}
        </span>
    </td>
    <td><a href="{{ $user->website_link }}">{{ $user->website_link }}</a></td>
    <td>{{ !empty($user->project_director_id) && isset($projectDirectors[$user->project_director_id]) ? $projectDirectors[$user->project_director_id] : '' }}</td>
</tr>
@empty
<tr>
    <td colspan="4">No employees found</td>
</tr>
@endforelse