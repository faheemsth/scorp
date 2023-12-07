@extends('layouts.admin')
<?php $setting = \App\Models\Utility::colorset(); ?>
{{-- <link rel="stylesheet" href="{{ asset('css/customsidebar.css') }}"> --}}

@section('page-title')
{{__('Import Files')}}
@endsection


@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Lead')}}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <div class="row">
            <div class="col-md-4">
                {{ Form::open(array('url' => '/show-file-data', 'method' => 'post', 'files' => true)) }}

                {{ Form::file('file', ['class' => 'form form-control']) }}

                {{ Form::submit('Upload', ['class' => 'btn btn-primary mt-2']) }}

                {{ Form::close() }}
            </div>
        </div>



        
        
    </div>
</div>
@endsection