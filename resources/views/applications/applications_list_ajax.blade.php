@forelse($applications as $app)
<tr>
    <td>
        <span style="cursor:pointer" class="hyper-link" @can('view application') onclick="openSidebar('deals/'+{{ $app->id }}+'/detail-application')" @endcan >
            {{ $shortened_name = substr($app->name, 0, 10) }}
            {{ strlen($app->name) > 10 ? $shortened_name . '...' : $app->name }}
        </span>
    </td>
    <td>
        {{ $shortened_name = substr($app->application_key, 0, 10) }}
        {{ strlen($app->application_key) > 10 ? $shortened_name . '...' : $app->application_key}}
    </td>
    <td>{{ isset($app->university_id) && isset($universities[$app->university_id]) ? $universities[$app->university_id] : '' }}</td>

    <td>
        {{ $app->intake }}
    </td>
    <td>
        {{ isset($app->stage_id) && isset($stages[$app->stage_id]) ? $stages[$app->stage_id] : '' }}
    </td>
    <td>


        @can('edit application')
            <div class="action-btn ms-2">

                <a data-size="lg"
                    title="{{ __('Edit Application') }}"
                    href="#"
                    class="btn px-2 btn-dark text-white mx-1"
                    data-url="{{ route('deals.application.edit', $app->id) }}"
                    data-ajax-popup="true"
                    data-title="{{ __('Edit Application') }}"
                    data-toggle="tooltip"
                    data-original-title="{{ __('Edit') }}">
                    <i class="ti ti-edit"></i>
                </a>

            </div>
        @endcan

        @can('delete application')
            <div class="action-btn ms-2">
                {!! Form::open([
                    'method' => 'DELETE',
                    'route' => ['deals.application.destroy', $app->id],
                    'id' => 'delete-form-' . $app->id,
                ]) !!}
                <a href="#"
                    class="mx-3 btn btn-sm bg-danger  align-items-center bs-pass-para"
                    data-bs-toggle="tooltip"
                    title="{{ __('Delete') }}"><i
                        class="ti ti-trash text-white"></i></a>

                {!! Form::close() !!}
            </div>
        @endcan




    </td>
</tr>
@empty
@endforelse
