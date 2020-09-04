@extends('layouts.master')
@section('title',__('members.title'))

@section('content')
<!-- Default box -->

    <members-sheet :levels={{ json_encode($levels) }} :lang={{ json_encode(app()->getLocale()) }} :auth="{{ json_encode(Auth::user()) }}"></members-sheet>

@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/plugins/toastr/toastr.css')}}">
@endsection

@section('js')
<script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
@endsection
