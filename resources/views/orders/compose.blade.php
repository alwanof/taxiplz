@extends('layouts.app')
@section('title', __('users.users'))

@section('content')

    <order :request="{{ json_encode($data) }}"></order>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
@endsection
