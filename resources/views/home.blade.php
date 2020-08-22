@extends('layouts.master')
@section('title', __('home.title'))

@section('nav')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __('home.title') }}</h1>
            </div>

        </div>
    </div><!-- /.container-fluid -->
@endsection
@section('content')
    <!-- Default box -->
    <div class="container">

        <home :acl="{{ json_encode($acl) }}" 
            :lang={{ json_encode(app()->getLocale()) }} :auth="{{ json_encode(Auth::user()) }}"></home>


    </div>

    <!-- /.card -->


@endsection

@section('css')

@endsection

@section('js')

@endsection
