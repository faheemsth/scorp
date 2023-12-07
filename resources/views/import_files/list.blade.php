@extends('layouts.admin')
<?php $setting = \App\Models\Utility::colorset(); ?>
{{-- <link rel="stylesheet" href="{{ asset('css/customsidebar.css') }}"> --}}

@section('page-title')
{{ __('Import Files') }}

@endsection


@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Sheet Data') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-end">
            <div>
                <span class="records-success-syncing p-1 btn btn-success d-none"></span>
                <span class="records-error-syncing btn btn-danger p-1 d-none"></span>
                <a href="javascript:void(0)" class="btn btn-sm btn-primary pull-right sync-lead">Sync Lead</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width: 150px;">{{ __('Name') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Deal Stage') }}</th>
                                <th>{{ __('Assign to') }}</th>
                                <th>{{ __('Notes') }}</th>
                                <th>{{ __('Label') }}</th>
                            </tr>
                        </thead>
                        <tbody class="sync-lead-tbody">
                            @if (count($data) > 0)
                            @foreach ($data as $key => $deal)
                            <tr>
                                <td class="icon">{{ $key + 1 }}</td>
                                <td class="name">{{ $deal['name'] }}</td>
                                <td class="subject">{{ $deal['subject'] }}</td>
                                <td class="email">{{ $deal['email'] }}</td>
                                <td class="phone">{{ $deal['phone'] }}</td>
                                <td class="deal_stage">{{ $deal['deal_stage'] }}</td>
                                <td class="assigned_to">{{ $deal['assigned_to'] }}</td>
                                <td class="notes">{{ $deal['notes'] }}</td>
                                <th class="contact_label">{{ $deal['contact_label'] }}</th>
                            </tr>
                            @endforeach
                            @else
                            <tr class="font-style">
                                <td colspan="8" class="text-center">{{ __('No data available in table') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection


@push('script-page')
<script>
    $(function() {



        $(".sync-lead").on("click", function() {
            var sync_btn = $(this);

            sync_btn.html('Syncing <i class="fa fa-spinner fa-spin"></i>');
            sync_btn.prop('disabled', true);

            var rows = $('.sync-lead-tbody').find('tr');
            var total_rows = parseInt(rows.length);
            var success = 0;
            var error = 0;
            $('.records-success-syncing').removeClass('d-none');
            $('.records-error-syncing').removeClass('d-none');
            $('.records-success-syncing').text(success + '/' + total_rows);
            $('.records-error-syncing').text(error + '/' + total_rows);

            function syncRow(row) {
                var icon = row.find('.icon');
                var line = {
                    "name": row.find('.name').text(),
                    "subject": row.find('.subject').text(),
                    "email": row.find('.email').text(),
                    "phone": row.find('.phone').text(),
                    "deal_stage": row.find('.deal_stage').text(),
                    "assigned_to": row.find('.assigned_to').text(),
                    'notes': row.find('.notes').text(),
                    'contact_label': row.find('.contact_label').text()
                };

                $.ajax({
                    url: '/syncing-file-data',
                    type: 'POST',
                    async: true,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        line: line
                    },
                    beforeSend: function() {
                        icon.html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {
                        response = JSON.parse(response);

                        if (response.status == 'success') {
                            success = parseInt(success) + 1;
                            icon.html('<i class="fa fa-check text-success"></i>');
                            $('.records-success-syncing').text(success + '/' + total_rows);
                        } else {
                            error = parseInt(error) + 1;
                            icon.html('<i class="fa fa-times text-danger"></i>');
                            $('.records-error-syncing').text(error + '/' + total_rows);
                        }

                        var nextRow = row.next('tr');
                        if (nextRow.length > 0) {
                            syncRow(nextRow);
                        } else {
                            sync_btn.html('Sync Lead');
                            sync_btn.prop('disabled', false);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error:', errorThrown);
                        error = parseInt(error) + 1;
                        icon.html('<i class="fa fa-close text-danger"></i>');
                        $('.records-error-syncing').text(error + '/' + total_rows);

                        var nextRow = row.next('tr');
                        if (nextRow.length > 0) {
                            syncRow(nextRow);
                        } else {
                            sync_btn.html('Sync Lead');
                            sync_btn.prop('disabled', false);
                        }
                    }
                });
            }

            syncRow(rows.first());

        });


    })
</script>
@endpush