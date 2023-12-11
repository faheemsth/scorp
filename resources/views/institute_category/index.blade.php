@extends('layouts.admin')
@section('page-title')
{{__('Manage university')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('University')}}</li>
@endsection


@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body table-border-style">

                <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                    <div class="col-2">
                        <p class="mb-0 pb-0">Institute Categories</p>
                        <div class="dropdown">
                            <button class="All-leads" type="button">
                                ALL Institute Categories
                            </button>
                        </div>
                    </div>

                    <div class="col-10 d-flex justify-content-end gap-2">
                        <div class="input-group w-25">
                            <button class="btn btn-sm list-global-search-btn">
                                <span class="input-group-text bg-transparent border-0  px-2 py-1" id="basic-addon1">
                                    <i class="ti ti-search" style="font-size: 18px"></i>
                                </span>
                            </button>
                            <input type="Search" class="form-control border-0 bg-transparent ps-0 list-global-search" placeholder="Search this list..." aria-label="Username" aria-describedby="basic-addon1">
                        </div>


                        <a href="#" data-size="md" data-url="{{ route('institute-category.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-size="lg" title="{{__('Create Institute Category')}}" class="btn btn-sm btn-primary pt-2">
                            <i class="ti ti-plus"></i>
                        </a>

                    </div>
                </div>
            </div>



            <div class="table-responsive my-3">
                <table class="table">

                    <thead>
                        <tr>
                            <th scope="col">{{__('#')}}</th>
                            <th scope="col">{{__('Name')}}</th>
                            <th scope="col">{{__('Action')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($institute_categories as $key => $category)
                        <tr class="font-style">
                            <td>{{ $key+1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td class="action ">
                                <div class="action-btn bg-info ms-2">
                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('institute-category.edit',$category->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Institute Category')}}">
                                        <i class="ti ti-pencil text-white"></i>
                                    </a>
                                </div>


                                <div class="action-btn bg-danger ms-2">
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['institute-category.destroy', $category->id]]) !!}
                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
                                    {!! Form::close() !!}
                                </div>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection