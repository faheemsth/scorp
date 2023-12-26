@foreach ($universities as $key => $university)
                                    <tr class="font-style">
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            @if (!empty($university->name))
                                                <span style="cursor:pointer" class="hyper-link"
                                                    onclick="openSidebar('/university/'+{{ $university->id }}+'/university_detail')">
                                                    {{ !empty($university->name) ? $university->name : '' }}
                                                </span>
                                            @endif

                                        </td>
                                        <td>{{ !empty($university->country) ? $university->country : '' }}</td>
                                        <td>{{ !empty($university->city) ? $university->city : '' }}</td>
                                        <td>{{ !empty($university->phone) ? $university->phone : '' }}</td>
                                        <td>{{ !empty($university->note) ? $university->note : '' }}</td>

                                        @if (\Auth::user()->type == 'super admin')
                                            <td>{{ isset($users[$university->created_by]) ? $users[$university->created_by] : '' }}
                                            </td>
                                        @endif

                                        @if (\Auth::user()->type != 'super admin')
                                            <td class="action ">
                                                @can('edit university')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-url="{{ route('university.edit', $university->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}"
                                                            data-title="{{ __('Edit University') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('delete university')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['university.destroy', $university->id]]) !!}
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                                class="ti ti-trash text-white"></i></a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach