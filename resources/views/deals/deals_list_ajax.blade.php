@if (count($deals) > 0)
    @foreach ($deals as $deal)
        <tr>
            <td>
                <input type="checkbox" name="deals[]" value="{{ $deal->id }}" class="sub-check">
            </td>

            <td style="width: 100px !important; ">
                <span style="cursor:pointer" class="deal-name hyper-link"
                    @can('view deal') onclick="openSidebar('/get-deal-detail?deal_id='+{{ $deal->id }})" @endcan
                    data-deal-id="{{ $deal->id }}">

                    @if (strlen($deal->name) > 40)
                        {{ substr($deal->name, 0, 40) }}...
                    @else
                        {{ $deal->name }}
                    @endif
                </span>
            </td>
            <td> {{ $deal->passport }}</td>
            <td>{{ $deal->stage->name }}</td>
            <td>
                {{ $deal->sources }}
            </td>

            <td>
                @php
                    $month = !empty($deal->intake_month) ? $deal->intake_month : 'January';
                    $year = !empty($deal->intake_year) ? $deal->intake_year : '2023';
                @endphp
                {{ $month . ' 1 ,' . $year }}
            </td>



            <td class="">
                <span style="cursor:pointer" class="hyper-link"
                    onclick="openSidebar('/users/'+{{ $deal->assigned_to }}+'/user_detail')">
                    {{ $deal->assigName }}
                </span>
            </td>
            <td class="lead-info-cell">

                @foreach (\App\Models\LeadTag::whereIn('id', explode(',', $deal->tag_ids))->get() as $tag)
                    <span class="badge text-white tag-badge" data-tag-id="{{ $tag->id }}"
                        data-lead-id="{{ $deal->lead_id }}" data-deal-id="{{ $deal->id }}"
                        style="background-color:#cd9835;cursor:pointer;">{{ $tag->tag }}</span>
                @endforeach
            </td>

            @if (\Auth::user()->type != 'Client')
                <td class="Action d-none">
                    <div class="dropdown">
                        <button class="btn bg-transparents" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                <path
                                    d="M12 3C11.175 3 10.5 3.675 10.5 4.5C10.5 5.325 11.175 6 12 6C12.825 6 13.5 5.325 13.5 4.5C13.5 3.675 12.825 3 12 3ZM12 18C11.175 18 10.5 18.675 10.5 19.5C10.5 20.325 11.175 21 12 21C12.825 21 13.5 20.325 13.5 19.5C13.5 18.675 12.825 18 12 18ZM12 10.5C11.175 10.5 10.5 11.175 10.5 12C10.5 12.825 11.175 13.5 12 13.5C12.825 13.5 13.5 12.825 13.5 12C13.5 11.175 12.825 10.5 12 10.5Z">
                                </path>
                            </svg>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Change</a>
                            </li>
                            <li><a class="dropdown-item" href="#">Edit</a></li>
                            <li><a class="dropdown-item" href="#">Delete</a>
                            </li>
                        </ul>

                </td>
            @endif


        </tr>
    @endforeach
@else
    <tr class="font-style">
        <td colspan="6" class="text-center">
            {{ __('No data available in table') }}
        </td>
    </tr>
@endif
