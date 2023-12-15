<table class="table " data-resizable-columns-id="lead-table" id="tfont">
    <thead>
        <tr>
            <th data-resizable-columns-id="name"><input type="checkbox" class=""></th>
            <th data-resizable-columns-id="name">{{ __('Name') }}</th>
            <th data-resizable-columns-id="email_address" class="ps-3">{{ __('Email Address') }}</th>
            <th data-resizable-columns-id="phone" class="ps-3">{{ __('Phone') }}</th>
            <th data-resizable-columns-id="stage" class="ps-3">{{ __('Stage') }}</th>
            <th data-resizable-columns-id="users" class="ps-3">{{ __('ASSIGNED TO') }}</th>
            @if (\Auth::user()->type == 'super admin')
                <th data-resizable-columns-id="created_by">{{ __('Created By') }}</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if (count($leads) > 0)
            @foreach ($leads as $lead)
                <tr>
                    <td><input type="checkbox" ></td>
                    <td >
                        <span style="cursor:pointer" class="lead-name"
                            onclick="openNav(<?= $lead->id ?>)"
                            data-lead-id="{{ $lead->id }}">{{ $lead->name }}</span>
                    </td>

                    <td >{{ $lead->email }}</td>
                    <td >{{ $lead->phone }}</td>
                    <td>{{ !empty($lead->stage) ? $lead->stage->name : '-' }}</td>
                    <td >
                                                    @php
                                                        $assigned_to = isset($lead->user_id) && isset($users[$lead->user_id]) ? $users[$lead->user_id] : 0;
                                                    @endphp

                                                    @if($assigned_to !=  0)
                                                    <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/'+{{$lead->user_id}}+'/user_detail')" >
                                                        {{ $assigned_to }}
                                                    </span>
                                                    @endif
                                                </td>
                    @if (\Auth::user()->type == 'super admin')
                        <td>{{ $users[$lead->created_by] }}</td>
                    @endif

                    <!--@if (Auth::user()->type != 'client')-->
                    <!--    <td class="Action py-1 px-0">-->
                    <!--        {{-- <span>-->

                    <!--            @if (\Auth::user()->type == 'super admin' || \Gate::check('view lead'))-->
                    <!--                @if ($lead->is_active)-->
                    <!--                    <div class="action-btn bg-warning ms-2">-->
                    <!--                        <a href="{{ route('leads.show', $lead->id) }}"-->
                    <!--                            class="mx-3 btn btn-sm d-inline-flex align-items-center"-->
                    <!--                            data-size="xl" data-bs-toggle="tooltip"-->
                    <!--                            title="{{ __('View') }}"-->
                    <!--                            data-title="{{ __('Lead Detail') }}">-->
                    <!--                            <i class="ti ti-eye text-white"></i>-->
                    <!--                        </a>-->
                    <!--                    </div>-->
                    <!--                @endif-->
                    <!--            @endif-->


                    <!--            @if (\Auth::user()->type == 'super admin' || \Gate::check('edit lead'))-->
                    <!--                <div class="action-btn bg-info ms-2">-->
                    <!--                    <a href="#"-->
                    <!--                        class="mx-3 btn btn-sm d-inline-flex align-items-center"-->
                    <!--                        data-url="{{ route('leads.edit', $lead->id) }}"-->
                    <!--                        data-ajax-popup="true" data-size="xl"-->
                    <!--                        data-bs-toggle="tooltip"-->
                    <!--                        title="{{ __('Edit') }}"-->
                    <!--                        data-title="{{ __('Lead Edit') }}">-->
                    <!--                        <i class="ti ti-pencil text-white"></i>-->
                    <!--                    </a>-->
                    <!--                </div>-->
                    <!--            @endif-->

                    <!--            @if (\Auth::user()->type == 'super admin' || \Gate::check('delete lead'))-->
                    <!--                <div class="action-btn bg-danger ms-2">-->
                    <!--                    {!! Form::open([-->
                    <!--                        'method' => 'DELETE',-->
                    <!--                        'route' => ['leads.destroy', $lead->id],-->
                    <!--                        'id' => 'delete-form-' . $lead->id,-->
                    <!--                    ]) !!}-->
                    <!--                    <a href="#"-->
                    <!--                        class="mx-3 btn btn-sm  align-items-center bs-pass-para"-->
                    <!--                        data-bs-toggle="tooltip"-->
                    <!--                        title="{{ __('Delete') }}"><i-->
                    <!--                            class="ti ti-trash text-white"></i></a>-->

                    <!--                    {!! Form::close() !!}-->
                    <!--                </div>-->
                    <!--            @endif-->
                    <!--        </span> --}}-->
                    <!--        <div class="dropdown">-->
                    <!--            <button class="btn bg-transparents" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">-->
                    <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path d="M12 3C11.175 3 10.5 3.675 10.5 4.5C10.5 5.325 11.175 6 12 6C12.825 6 13.5 5.325 13.5 4.5C13.5 3.675 12.825 3 12 3ZM12 18C11.175 18 10.5 18.675 10.5 19.5C10.5 20.325 11.175 21 12 21C12.825 21 13.5 20.325 13.5 19.5C13.5 18.675 12.825 18 12 18ZM12 10.5C11.175 10.5 10.5 11.175 10.5 12C10.5 12.825 11.175 13.5 12 13.5C12.825 13.5 13.5 12.825 13.5 12C13.5 11.175 12.825 10.5 12 10.5Z"></path></svg>-->
                    <!--            </button>-->
                    <!--            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">-->
                    <!--                <li><a class="dropdown-item" href="#">Change</a></li>-->
                    <!--              <li><a class="dropdown-item" href="#">Edit</a></li>-->
                    <!--              <li><a class="dropdown-item" href="#">Delete</a></li>-->
                    <!--            </ul>-->
                    <!--          </div>-->
                    <!--    </td>-->
                    <!--@endif-->



                </tr>
            @endforeach
        @else
            <tr class="font-style">
                <td colspan="6" class="text-center">{{ __('No data available in table') }}
                </td>
            </tr>
        @endif

    </tbody>
</table>

@if ($total_records > 0)
    @include('layouts.pagination', [
        'total_pages' => $total_records
    ])
@endif

