
<tr>
    <td>
        <input type="checkbox" class="form">
    </td>
    <td>

        <span style="cursor:pointer" class="org-name" onclick="openNav(<?= $org->id ?>)" data-org-id="{{ $org->id }}">{{$org->name}}</span>

    </td>
    <td>{{ isset($org_data->phone) ? $org_data->phone : '' }}</td>
    <td>{{ isset($org_data->billing_street) ? $org_data->billing_street : '' }}</td>
    <td>{{ isset($org_data->billing_city) ? $org_data->billing_city : ''  }}</td>
    <td>{{ isset($org_data->billing_state) ? $org_data->billing_state : ''  }}</td>
    <td>{{ isset($org_data->billing_country) ? $org_data->billing_country : ''  }}</td>
    <td></td>
    <td></td>
</tr>