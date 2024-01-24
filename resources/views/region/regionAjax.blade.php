@if (!empty($regions))
    @foreach ($regions as $region)
        <tr>
            <td>
                <input type="checkbox" name="deals[]" value="{{ $region->id }}" class="sub-check">
            </td>
            <td>
                <span style="cursor:pointer" class="hyper-link"
                    @can('view region') onclick="openSidebar('/regions/{{ $region->id }}/show')" @endcan>
                    {{ $region->name }}
                </span>
            </td>
            <td><a href="mailto:{{ $region->email }}">{{ $region->email }}</a></td>
            <td>{{ $region->phone }}</td>
            <td>{{ $region->location }}</td>

            <td>{{ $users[$region->region_manager_id] ?? '' }}</td>

            <td>
                @php
                    $brands = explode(',', $region->brands);
                @endphp

                @foreach ($brands as $brand_id)
                    {{ $users[$brand_id] ?? '' }}
                @endforeach
            </td>

            <td class="Action d-none">
                <div class="dropdown">
                    <button class="btn bg-transparents" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                            <path
                                d="M12 3C11.175 3 10.5 3.675 10.5 4.5C10.5 5.325 11.175 6 12 6C12.825 6 13.5 5.325 13.5 4.5C13.5 3.675 12.825 3 12 3ZM12 18C11.175 18 10.5 18.675 10.5 19.5C10.5 20.325 11.175 21 12 21C12.825 21 13.5 20.325 13.5 19.5C13.5 18.675 12.825 18 12 18ZM12 10.5C11.175 10.5 10.5 11.175 10.5 12C10.5 12.825 11.175 13.5 12 13.5C12.825 13.5 13.5 12.825 13.5 12C13.5 11.175 12.825 10.5 12 10.5Z">
                            </path>
                        </svg>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        @can('edit region')
                            <li><a class="dropdown-item" href="#" data-size="lg"
                                    data-url="{{ url('region/update?id=') . $region->id }}"
                                    title="{{ __('Update Origin') }}" data-ajax-popup="true"
                                    data-bs-toggle="tooltip">Edit</a>
                            </li>
                        @endcan
                        @can('delete region')
                            <li><a class="dropdown-item" href="{{ url('region/delete?id=') . $region->id }}">Delete</a>
                            </li>
                        @endcan
                    </ul>
            </td>
        </tr>
    @endforeach
@else
    <tr class="font-style">
        <td colspan="6" class="text-center">
            {{ __('No data available in table') }}
        </td>
    </tr>
@endif
