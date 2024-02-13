@forelse($users as $key => $user)
<tr>
    <td>
        <input type="checkbox" name="brand_ids[]" value="{{ $user->id }}" class="sub-check">
    </td>
    <td style="max-width: 130px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">

        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/{{ $user->id }}/user_detail')">
            {{ $user->name }}
        </span>
    </td>
    <td style="max-width: 130px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;"><a href="{{ $user->website_link }}">{{ $user->website_link }}</a></td>
    <td style="max-width: 130px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
        @php 
            // $project_director = \App\Models\User::join('company_permission', 'company_permission.user_id', '=', 'users.id')
            //                     ->where('company_permission.permitted_company_id', $user->id)
            //                     ->where('type', 'Project Director')
            //                     ->first();
        @endphp 
        {{-- {{ $project_director->name ?? '' }} --}}

        {{ $user->project_director}}
    </td>
</tr>
@empty
<tr>
    <td colspan="4">No employees found</td>
</tr>
@endforelse