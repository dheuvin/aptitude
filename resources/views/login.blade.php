@extends('layouts.app')

@section('title', 'Login')

@section('standalone')
    <div class="login-shell">
        <div class="hero stack">
            <div class="center">
                <h1 style="margin: 0 0 10px;">Secure Login</h1>
                <p class="meta" style="margin: 0;">Users can log in only with credentials created by the admin. No name or email is required.</p>
            </div>

            @if(session('success'))
                <div class="flash success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="flash error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="error-box">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="stack" autocomplete="off">
                @csrf

                <div class="field">
                    <label for="phone">Phone Number</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" placeholder="Enter your password">
                </div>

                <button type="submit" class="button primary">Login</button>
            </form>
        </div>
    </div>
@endsection
