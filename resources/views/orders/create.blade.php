@extends('layouts.app')
@section('title', __('users.users'))

@section('content')

    <div class="card">
        <div class="card-header text-center" style="background-color: #ffa500">
            <img class="img-thumbnail rounded-circle" src="{{ $data['user']->avatar }}" alt="..."
                style="margin-bottom: -32px;" />
        </div>
        <div class="card-body text-center">
            <h5 class="card-title font-weight-bold">{{ $data['user']->name }}</h5>
            @include('alert')
            <form action="{{ route('order.compose') }}" method="POST" class="was-validated text-left">
                @csrf
                <input type="hidden" name="officeEmail" value="{{ $data['user']->email }}">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" placeholder="Enter your name" name="name"
                        value="{{ $data['name'] }}" required>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" class="form-control" placeholder="Enter your phone" name="phone"
                        value="{{ $data['phone'] }}" required>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <div class="form-group">
                    <label for="address">Addrerss:</label>
                    <input type="text" class="form-control" placeholder="Enter your address" name="address"
                        value="{{ $data['address'] }}" required>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>


                <button type="submit" class="btn btn-primary">Submit</button>
            </form>



        </div>
    </div>
@endsection

@section('css')

@endsection

@section('js')

@endsection
