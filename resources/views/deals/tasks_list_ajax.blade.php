@forelse($tasks as $key => $task)
                            @php

                            $due_date = strtotime($task->due_date);
                            $current_date = strtotime(date('Y-m-d'));

                            // Assuming $due_date, $current_date, and $task->status are defined before this code

                            $status = strtolower($task->status); // Store the lowercase status for better readability
                            $color_code = ''; // Initialize $color_code

                            if ($due_date > $current_date && $status === '0') {
                                // Ongoing feture time
                               // $color_code = '#B3CDE1';
                               $color_code = 'green';
                            } elseif ($due_date === $current_date && $status === '0') {
                                // Today date time
                                $color_code = '#E89D25';
                            } elseif ($due_date < $current_date && $status === '0') {
                                // Past date time
                                $color_code = 'red';
                            } elseif ($status === '1') {
                                // Completed task
                                $color_code = 'green';
                            }

                            // Use $color_code as needed in the rest of your code



                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="tasks[]" value="{{$task->id}}" class="sub-check">
                                    </td>
                                    <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                         <span class="badge text-white" style="background-color:{{$color_code }}">{{ $task->due_date  }}</span>
                                    </td>

                                    <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                        <span style="cursor:pointer" class="task-name hyper-link" @can('view task') onclick="openSidebar('/get-task-detail?task_id=<?= $task->id ?>')" @endcan data-task-id="{{ $task->id }}">{{ $task->name }}</span>
                                    </td>
                                    <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                        @if (!empty($task->assigned_to))
                                        <span style="cursor:pointer" class="hyper-link" @can('view task') onclick="openSidebar('/users/'+{{ $task->assigned_to }}+'/user_detail')" @endcan>
                                            {{ $users[$task->assigned_to] }}
                                        </span>
                                        @endif
                                    </td>

                                    <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">

                                        @if (!empty($task->assigned_to))
                                        @if ($task->assigned_type == 'company')
                                        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/'+{{ $task->assigned_to }}+'/user_detail')">
                                            {{ $users[$task->assigned_to] }}
                                        </span>
                                        @else
                                        <?php
                                        $brand = \App\Models\User::where('type', 'company')->where('id', $task->brand_id)->first();
                                        ?>

                                        <span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/users/'+{{ $task->brand_id }}+'/user_detail')">
                                            {{ isset($brand->name) ? $brand->name : '' }}
                                        </span>
                                        @endif
                                        @endif

                                        
                                    </td>

                                    

                                    <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                                        @if ($task->status == 0)
                                            <span class="badge  text-white" style="background-color:#cd9835">{{ __('On Going') }}</span>
                                        @else
                                            <span class="badge text-white" style="background: green; " >{{ __('Completed') }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($task->status == 0)
                                        <button class="btn btn-sm btn-dark position-relative"  @can('edit status task') onclick="ChangeTaskStatus({{ $task->id }})" @endcan data-bs-toggle="tooltip" data-bs-placement="top" title="Change Task Status">
                                            <i class="fa-solid fa-check d-flex justify-content-center align-items-center" style="font-size: 18px;"></i>
                                        </button>

                                        @else 
                                        <span class="badge text-white" style="background: green; " >{{ __('Completed') }}</span>
                                        @endif
                                    </td>

                                    
                                </tr>
                                @empty
                                <tr>
                                    <td class="7">No Record Found!!!</td>
                                </tr>
                                @endforelse