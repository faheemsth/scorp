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
    <td>

        <div class="card-header-right">
            <div class="btn-group card-option">
                <button type="button" class="btn" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="ti ti-dots-vertical"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end">
                    @can('edit client')
                        <a href="#!" data-size="md"
                            data-url="{{ route('clients.edit', $client->id) }}"
                            data-ajax-popup="true" class="dropdown-item"
                            data-bs-original-title="{{ __('Edit User') }}">
                            <i class="ti ti-pencil"></i>
                            <span>{{ __('Edit') }}</span>
                        </a>
                    @endcan

                    @can('delete client')
                        {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['clients.destroy', $client['id']],
                            'id' => 'delete-form-' . $client['id'],
                        ]) !!}
                        <a href="#!" class="dropdown-item bs-pass-para">
                            <i class="ti ti-archive"></i>
                            <span>
                                @if ($client->delete_status != 0)
                                    {{ __('Delete') }}
                                @else
                                    {{ __('Restore') }}
                                @endif
                            </span>
                        </a>

                        {!! Form::close() !!}
                    @endcan

                    <a href="#!"
                        data-url="{{ route('clients.reset', \Crypt::encrypt($client->id)) }}"
                        data-ajax-popup="true" class="dropdown-item"
                        data-bs-original-title="{{ __('Reset Password') }}">
                        <i class="ti ti-adjustments"></i>
                        <span> {{ __('Reset Password') }}</span>
                    </a>
                </div>
            </div>
        </div>

    </td>
</tr>
@empty
@endforelse