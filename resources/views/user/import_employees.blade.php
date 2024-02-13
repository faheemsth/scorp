@extends('layouts.admin')
@php
// $profile=asset(Storage::url('uploads/avatar/'));
$profile = \App\Models\Utility::get_file('uploads/avatar');
@endphp
@section('page-title')
{{ __('Manage Import Employees') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('crm.dashboard') }}">{{ __('Dashboard') }}</a>
</li>
<li class="breadcrumb-item">{{ __('Import Employees') }}</li>
@endsection

@section('content')

<div class="row">
    <div class="col-xxl-12">
        <div class="row w-100 m-0">
            <div class="card my-card">
                <div class="card-body">
                    <form action="{{ route('import.employees.csv') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="csv_file">
                        <button type="submit">Import CSV</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection