@extends('layouts.admin')
@php
   // $profile=asset(Storage::url('uploads/avatar/'));
    $profile=\App\Models\Utility::get_file('uploads/avatar/');
@endphp
@section('page-title')
    {{__('Manage Client')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Client')}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="card py-3">
            <div class="row align-items-center ps-0 ms-0 pe-4 my-2">
                <div class="col-2">
                    <p class="mb-0 pb-0">CONTACTS</p>
                    <div class="dropdown">
                        <button class="dropdown-toggle All-leads" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            ALL CONTACTS
                        </button>
                        {{-- <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul> --}}
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
    
                    <button data-url="{{ route('clients.create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" title="{{__('Create')}}" class="btn btn-sm p-2 btn-primary" data-bs-toggle="modal">
                        <i class="ti ti-plus" style="font-size:18px"></i>
                    </button>
                  
                </div>
            </div>
    
    
    
    
            <div class=" mt-3">
                <table class="table">
                    <thead style="background: #ddd; color:rgb(0, 0, 0); font-size: 14px; font-weight: bold;">
                        <tr>
                            <!-- <td style="border-left: 1px solid #fff;"></td> -->
                            <th style="border-left: 1px solid #fff;"><input type="checkbox"></th>
                            <th style="border-left: 1px solid #fff;">Name</th>
                            <th style="border-left: 1px solid #fff;">Email</th>
                            <th style="border-left: 1px solid #fff;">Admissions</th>
                            <th style="border-left: 1px solid #fff;">Applications</th>
                            <th style="border-left: 1px solid #fff;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="leads-list-div" style="color:rgb(0, 0, 0); font-size: 12px;" class="new-organization-list-tbody">
    
                        @forelse($clients as $client)
                            <tr>
                                <td>
                                    <input type="checkbox">
                                </td>
                                <td><span style="cursor:pointer" class="hyper-link" onclick="openSidebar('/clients/'+{{$client->id}}+'/client_detail')" >
                                        {{ $client->name }}
                                    </span>
                                    
                                </td>
                                <td>{{$client->email}}</td>
                                <td>{{$client->clientDeals->count()}}</td>
                                <td>{{$client->clientApplications($client->id)}}</td>
                                <td> 
    
                                    <div class="card-header-right">
                                        <div class="btn-group card-option">
                                            <button type="button" class="btn"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
    
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @can('edit client')
                                                    <a href="#!" data-size="md" data-url="{{ route('clients.edit',$client->id) }}" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="{{__('Edit User')}}">
                                                        <i class="ti ti-pencil"></i>
                                                        <span>{{__('Edit')}}</span>
                                                    </a>
                                                @endcan
    
                                                @can('delete client')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['clients.destroy', $client['id']],'id'=>'delete-form-'.$client['id']]) !!}
                                                    <a href="#!"  class="dropdown-item bs-pass-para">
                                                        <i class="ti ti-archive"></i>
                                                        <span> @if($client->delete_status!=0){{__('Delete')}} @else {{__('Restore')}}@endif</span>
                                                    </a>
    
                                                    {!! Form::close() !!}
                                                @endcan
    
                                                <a href="#!" data-url="{{route('clients.reset',\Crypt::encrypt($client->id))}}" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="{{__('Reset Password')}}">
                                                    <i class="ti ti-adjustments"></i>
                                                    <span>  {{__('Reset Password')}}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
    
                                </td>
                            </tr>
                        @empty 
    
                        @endforelse 
                    </tbody>
                </table>
            </div>
    
            @if ($total_records > 0)
                @include('layouts.pagination', [
                    'total_pages' => $total_records,
                    'num_results_on_page' => 50,
                ])
            @endif
        </div>
    </div>
@endsection
