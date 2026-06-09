@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit User</h1>
            <p class="meta">Update the phone number, role, or reset the password for this account.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="button secondary">Back to Users</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="stack">
            @csrf
            @method('PUT')

            <div class="field-row">
                <div class="field">
                    <label for="phone">Phone Number</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="user" @selected(old('role', $user->role) === 'user')>User</option>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                    </select>
                </div>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="password">New Password</label>
                    <input id="password" type="text" name="password" placeholder="Leave blank to keep current password">
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input id="password_confirmation" type="text" name="password_confirmation" placeholder="Repeat new password">
                </div>
            </div>

            <div class="inline">
                <button type="submit" class="button primary">Update User</button>
            </div>
        </form>
    </div>
@endsection
