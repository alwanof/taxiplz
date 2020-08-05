@extends('layouts.master')
@section('title',__('users.roles-manager'))

@section('nav')
<div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('users.roles-manager') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('users') }}">{{ __('users.users') }}</a>
                </li>
                @can('access-roles')
                    <li class="breadcrumb-item">
                        <a href="{{ route('roles') }}">{{ __('users.roles') }}</a>
                    </li>
                @endcan
                @can('access-permissions')
                    <li class="breadcrumb-item active">
                        <a href="{{ route('permissions') }}">{{ __('users.permissions') }}</a>
                    </li>
                @endcan
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
@endsection
@section('content')
  <!-- Default box -->
  <role-component  :lang={{ json_encode(app()->getLocale()) }} :auth="{{ json_encode(Auth::user()) }}" ></role-component>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/plugins/toastr/toastr.css')}}">
@endsection

@section('js')
    <script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
@endsection
