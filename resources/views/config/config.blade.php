@extends('layouts.master')
@section('title',__('config.configTitle'))

@section('nav')
<div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('config.configTitle') }}</h1>
          </div>
          <div class="col-sm-6">

          </div>
        </div>
      </div><!-- /.container-fluid -->
@endsection
@section('content')
  <!-- Default box -->
  <config-component :roles="{{ $roles }}" :lang={{ json_encode(app()->getLocale()) }} :auth="{{ json_encode(Auth::user()) }}" />

@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/plugins/toastr/toastr.css')}}">
@endsection

@section('js')
    <script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
@endsection
