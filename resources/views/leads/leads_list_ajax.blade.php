@if (count($leads) > 0)
    @foreach ($leads as $lead)
        <tr>
            <td><input type="checkbox" name="leads[]" value="{{ $lead->id }}" class="sub-check"></td>


            <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                <span style="cursor:pointer" class="lead-name hyper-link"
                    @can('view lead') onclick="openSidebar('/get-lead-detail?lead_id=<?= $lead->id ?>')" @endcan
                    data-lead-id="{{ $lead->id }}">{{ $lead->name }}</span>
            </td>

            <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;"> <a
                    href="{{ $lead->email }}">{{ $lead->email }}</a></td>
            <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                {{ $lead->phone }}</td>
            <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                {{ !empty($lead->stage) ? $lead->stage->name : '-' }}</td>
            <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                @php
                    $assigned_to = isset($lead->user_id) && isset($users[$lead->user_id]) ? $users[$lead->user_id] : 0;
                @endphp

                @if ($assigned_to != 0)
                    <span style="cursor:pointer" class="hyper-link"
                        onclick="openSidebar('/users/'+{{ $lead->user_id }}+'/user_detail')">
                        {{ $assigned_to }}
                    </span>
                @endif
            </td>

            <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                {{ $users[$lead->brand_id] ?? '' }}</td>
            <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                {{ $branches[$lead->branch_id] ?? '' }}</td>
            <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                @php
                    $lead_tags = \App\Models\LeadTag::where('lead_id', $lead->id)->get();
                @endphp

                @forelse($lead_tags as $tag)
                    <span
                        class="badge text-white {{ \Auth::user()->type == 'super admin' || \Auth::user()->type == 'Admin Team' || \Auth::user()->type == 'Project Manager' || \Auth::user()->type == 'Project Director' ? 'tag-badge' : '' }}"
                        data-tag-id="{{ $tag->id }}" data-tag-name="{{ $tag->tag }}"
                        style="background-color:#cd9835;cursor:pointer;">{{ $tag->tag }}</span>
                @empty
                @endforelse
            </td>
        </tr>
    @endforeach
@else
    <tr class="font-style">
        <td colspan="6" class="text-center">{{ __('No data available in table') }}
        </td>
    </tr>
@endif
