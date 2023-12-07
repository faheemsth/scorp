@forelse($organizations as $org)
@php
$org_data = $org->organization($org->id);

@endphp

<tr>
    <!-- <td class="py-1">
        <input type="checkbox" class="form">
    </td> -->
    <td class="py-1">
        <span style="cursor:pointer" class="org-name hyper-link" onclick="openNav(<?= $org->id ?>)" data-org-id="{{ $org->id }}">{{$org->name}}</span>
    </td>
    <td class="py-1">{{ isset($org_data->phone) ? $org_data->phone : '' }}</td>
    <td class="py-1">{{ isset($org_data->billing_street) ? $org_data->billing_street : '' }}</td>
    <td class="py-1">{{ isset($org_data->billing_city) ? $org_data->billing_city : ''  }}</td>
    <td class="py-1">{{ isset($org_data->billing_state) ? $org_data->billing_state : ''  }}</td>
    <td class="py-1">{{ isset($org_data->billing_country) ? $org_data->billing_country : ''  }}</td>
    <td class="py-1"></td>
    <td class="py-1">
        <div class="dropdown">
            <button class="btn bg-transparents" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                    <path d="M12 3C11.175 3 10.5 3.675 10.5 4.5C10.5 5.325 11.175 6 12 6C12.825 6 13.5 5.325 13.5 4.5C13.5 3.675 12.825 3 12 3ZM12 18C11.175 18 10.5 18.675 10.5 19.5C10.5 20.325 11.175 21 12 21C12.825 21 13.5 20.325 13.5 19.5C13.5 18.675 12.825 18 12 18ZM12 10.5C11.175 10.5 10.5 11.175 10.5 12C10.5 12.825 11.175 13.5 12 13.5C12.825 13.5 13.5 12.825 13.5 12C13.5 11.175 12.825 10.5 12 10.5Z"></path>
                </svg>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                @if(\Auth::user()->type == 'super admin' ||\Auth::user()->can('edit organization'))
                <li>
                    <a href="#" data-size="lg" data-url="{{ route('organization.edit', $org->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Edit') }}" class="dropdown-item">
                        Edit this Organization
                    </a>
                </li>
                @endif

                <li>
                    @if(\Auth::user()->type == 'super admin' ||\Auth::user()->can('delete organization'))
                    
                    <a href="{{ route('organization.delete', $org->id) }}" class="dropdown-item">
                        Delete this Organization
                    </a>
                    
                    @endif
                </li>
                 @can('create task')
                <li>
                    <a href="{{ route('organiation.tasks.create', $org->id) }}" data-bs-toggle="tooltip" title="{{ __('Add Message') }}" class="dropdown-item">
                        Add new task For Organization
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="9" class="py-1">
        No Organizations found
    </td>
</tr>
@endforelse