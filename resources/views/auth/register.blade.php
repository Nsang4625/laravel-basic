@extends('layouts.app')

@section('content')
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Name</label>
            <input name="name" required value="{{ old('name') }}"
                class="form-control{{ $errors->has('name') ? 'is-invalid' : '' }}">
            @if ($errors->has('name'))
                <span class="is-invalid">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>Email</label>
            <input name="email" required value="{{ old('email') }}"
                class="form-control{{ $errors->has('email') ? 'is-invalid' : '' }}">
            @if ($errors->has('email'))
                <span class="is-invalid">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required class="form-control{{ $errors->has('password') ? 'is-invalid' : '' }}">
            @if ($errors->has('password'))
                <span class="is-invalid">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>Confirm password</label>
            <input type="password" name="password_confirmation" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
@endsection
