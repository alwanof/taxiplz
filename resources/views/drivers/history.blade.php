@extends('layouts.master')
@section('title', __('drivers.history.title'))

@section('nav')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __('drivers.history.title') }}</h1>
            </div>

        </div>
    </div><!-- /.container-fluid -->
@endsection
@section('content')
    <!-- Default box -->

    <history :lang={{ json_encode(app()->getLocale()) }} :auth="{{ json_encode(Auth::user()) }}"></history>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
@endsection
