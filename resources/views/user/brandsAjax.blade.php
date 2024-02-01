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
    <td>
        @php 
                                            $project_director = \App\Models\User::join('company_permission', 'company_permission.user_id', '=', 'users.id')
                                                                ->where('company_permission.permitted_company_id', $user->id)
                                                                ->first();
                                        @endphp 
                                        {{ $project_director->name ?? '' }}
    </td>
</tr>
@empty
<tr>
    <td colspan="4">No employees found</td>
</tr>
@endforelse