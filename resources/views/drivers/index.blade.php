@extends('layouts.master')
@section('title', __('drivers.title'))

@section('nav')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __('drivers.title') }}</h1>
            </div>

        </div>
    </div><!-- /.container-fluid -->
@endsection
@section('content')
    <!-- Default box -->

    <drivers :lang={{ json_encode(app()->getLocale()) }} :auth="{{ json_encode(Auth::user()) }}"></drivers>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
@endsection
