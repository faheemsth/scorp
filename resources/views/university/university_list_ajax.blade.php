<?php
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $count = ($_GET['page'] - 1) * $_GET['num_results_on_page'] + 1;
} else {
    $count = 1;
}
?>
@forelse ($universities as $key => $university)
    <tr class="font-style">
        <td>
            {{ $count++ }}
        </td>
        <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $university->name }}">
            @if (!empty($university->name))
                <span style="cursor:pointer" class="hyper-link"
                    @can('show university') onclick="openSidebar('/university/'+{{ $university->id }}+'/university_detail')" @endcan>
                    {{ !empty($university->name) ? (strlen($university->name) > 10 ? substr($university->name, 0, 10) . '...' : $university->name) : '' }}
                </span>
            @endif

        </td>
        {{-- <td >{{ !empty($university->Institutes) ? $university->Institutes: '' }}</td> --}}


        <td style="max-width: 110px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
            {{ !empty($university->campuses) ? $university->campuses : '' }}</td>

        <td style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
            {{ !empty($university->intake_months) ? $university->intake_months : '' }}</td>
        <td style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
            {{ !empty($university->territory) ? $university->territory : '' }}</td>
        <td style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
            {{ $users[$university->company_id] ?? '' }}</td>
        <td style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
            <a href="{{ !empty($university->resource_drive_link) ? $university->resource_drive_link : '' }}">
                Click to view
            </a>
        </td>
        <td style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
            <a
                href="{{ !empty($university->application_method_drive_link) ? $university->application_method_drive_link : '' }}">
                {{ !empty($university->name) ? $university->name : '' }}
            </a>
        </td>

        @if (\Auth::user()->type == 'super admin')
            <td style="max-width: 140px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;">
                {{ isset($users[$university->created_by]) ? $users[$university->created_by] : '' }}
            </td>
        @endif

        @if (\Auth::user()->type != 'super admin')
            <td class="action d-none">
                @can('edit university')
                    <div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                            data-url="{{ route('university.edit', $university->id) }}" data-ajax-popup="true"
                            data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                            data-title="{{ __('Edit University') }}">
                            <i class="ti ti-pencil text-white"></i>
                        </a>
                    </div>
                @endcan
                @can('delete university')
                    <div class="action-btn bg-danger ms-2">
                        {!! Form::open(['method' => 'DELETE', 'route' => ['university.destroy', $university->id]]) !!}
                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip"
                            title="{{ __('Delete') }}"><i class="ti ti-trash text-white"></i></a>
                        {!! Form::close() !!}
                    </div>
                @endcan
            </td>
        @endif
    </tr>
@empty
    <tr>
        <td colspan="10 text-center" style="text-align: center !important;">No Record Found!!!</td>
    </tr>
@endforelse
