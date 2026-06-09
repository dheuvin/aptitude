@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="page-header">
        <div>
            <h1>Create User</h1>
            <p class="meta">Only a phone number is required. If you leave the password blank, a secure numeric password will be generated for you.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="button secondary">Back to Users</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.users.store') }}" class="stack">
            @csrf

            <div class="field-row">
                <div class="field">
                    <label for="phone">Phone Number</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number">
                </div>

                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="user" @selected(old('role') === 'user')>User</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    </select>
                </div>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="text" name="password" placeholder="Optional">
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="text" name="password_confirmation" placeholder="Optional">
                </div>
            </div>

            <div class="inline">
                <button type="submit" class="button primary">Save User</button>
                <span class="help">Users will log in only with the credentials created here.</span>
            </div>
        </form>
    </div>
@endsection
