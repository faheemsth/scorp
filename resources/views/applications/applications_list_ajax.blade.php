@forelse($applications as $app)
@php
$university = \App\Models\University::where('id', $app->university_id)->first();
$deal = \App\Models\Deal::where('id', $app->deal_id)->first();
$users = \App\Models\User::pluck('name', 'id')->toArray();
$branch = \App\Models\Branch::where('id', $deal->branch_id)->first();
@endphp
<tr>
    <td>
        <input type="checkbox" name="applications[]" value="{{$app->id}}" class="sub-check">
    </td>
    <td>
        <span style="cursor:pointer" class="hyper-link" @can('view application') onclick="openSidebar('deals/'+{{ $app->id }}+'/detail-application')" @endcan>
            {{ strlen($app->name) > 10 ? substr($app->name, 0, 10) . '...' : $app->name }}
        </span>
    </td>
    <td>{{ $app['course'] }}</td>
    <td>{{ $universities[$app->university_id]  ?? '' }}</td>
    <td>{{ isset($app->stage_id) && isset($stages[$app->stage_id]) ? $stages[$app->stage_id] : '' }}</td>
    <td> {{ !empty($deal->assigned_to) ? (isset($users[$deal->assigned_to]) ? $users[$deal->assigned_to] : '') : '' }} </td>


    <td class="d-none"> {{ $app->intake }} </td>
    <td class="d-none"> {{ isset($users[$deal->brand_id]) ? $users[$deal->brand_id] : '' }} </td>
    <td class="d-none"> {{ isset($branch->name) ? $branch->name : ''  }} </td>


</tr>
@empty
@endforelse