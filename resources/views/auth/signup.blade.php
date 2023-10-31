@extends('layouts.app')

@section('title', 'Sign Up')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <br/>
    @endif

    <div class="row">
        <div class="col-md-8 col-lg-6 col-xl-4 mx-auto mt-4">
            <h3 class="text-center">Sign up</h3>
            <form method="POST" action="{{ route('post.signup') }}">
                @csrf
                <div class="mb-4">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" value="{{ old('name') }}" name="name" id="name"/>
                </div>
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="email"/>
                </div>
                <div class="mb-4">
                    <label for="login" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password"/>
                </div>
                <div class="d-flex justify-content-between">
                    <a class="btn btn-outline-secondary" href="/signin">Sign In</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
