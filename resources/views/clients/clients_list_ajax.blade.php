@forelse($clients as $client)
<tr>
    <td>
        <input type="checkbox" name="contacts[]" value="{{ $client->id }}"
            class="sub-check">
    </td>
    <td><span style="cursor:pointer" class="hyper-link"
        @can('show client') onclick="openSidebar('/clients/'+{{ $client->id }}+'/client_detail')" @endcan>
            {{ $client->name }}
        </span>

    </td>
    <td>{{ $client->email }}</td>
    <td>{{ $client->clientDeals->count() }}</td>
    <td>{{ $client->clientApplications($client->id) }}</td>
</tr>
@empty
@endforelse