@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
    <div class="page-header">
        <div>
            <h1>Manage Users</h1>
            <p class="meta">Create users manually with phone numbers, reset their passwords, and control admin access.</p>
        </div>

        <a href="{{ route('admin.users.create') }}" class="button primary">Create User</a>
    </div>

    {{-- ASSIGN TEST --}}
    <div class="card">

        <h3>Assign Test To User</h3>

        <form action="{{ route('admin.tests.assign') }}" method="POST">
            @csrf

            <div class="grid" style="grid-template-columns: repeat(2, 1fr); gap: 16px;">

                <div>
                    <label>User</label>
                    <select name="user_id" required>
                        <option value="">Select User</option>
                        @foreach ($assignableUsers as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name ?? $user->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Test</label>
                    <select name="test_id" required>
                        <option value="">Select Test</option>
                        @foreach ($tests as $test)
                            <option value="{{ $test->id }}">
                                {{ $test->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div style="margin-top: 16px;">
                <button type="submit" class="button primary">
                    Assign Test
                </button>
            </div>
        </form>
    </div>

    <div>
        <h1>Users Table</h1>
    </div>

    {{-- USERS TABLE --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Assign Test </th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->phone }}</td>

                        <td>
                            <span class="badge {{ $user->role === 'admin' ? 'warning' : 'neutral' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <td>{{ $user->test_attempts_count }}</td>

                        <td>{{ $user->created_at?->format('d M Y, h:i A') }}</td>

                        <td>
                            <div class="actions">

                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="button secondary">
                                    Edit
                                </a>

                                {{-- STEP 3: VIEW TESTS BUTTON --}}
                                <button type="button"
                                        class="button info"
                                        onclick="viewTests({{ $user->id }})">
                                    View Tests
                                </button>

                                <form method="POST"
                                      action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Delete this user?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="button danger">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">No users have been created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- STEP 4: MODAL UI --}}
    <div id="testModal"
         style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);">

        <div style="background:#fff; width:600px; margin:8% auto; padding:20px; border-radius:10px;">

            <h3>User Assigned Tests</h3>

            <div id="testContent">
                Loading...
            </div>

            <div style="margin-top:15px;">
                <button onclick="closeTests()" class="button secondary">
                    Close
                </button>
            </div>

        </div>
    </div>

@endsection


{{-- STEP 5: JS LOGIC --}}
@section('scripts')
<script>
const userTestsUrlTemplate = @json(route('admin.users.tests', ['user' => '__USER__']));

function viewTests(userId)
{
    document.getElementById('testModal').style.display = 'block';
    document.getElementById('testContent').innerHTML = 'Loading...';

    fetch(userTestsUrlTemplate.replace('__USER__', userId))
        .then(res => {
            if (!res.ok) {
                throw new Error('Failed to load tests');
            }

            return res.json();
        })
        .then(data => {

            if (data.length === 0) {
                document.getElementById('testContent').innerHTML =
                    '<p>No tests assigned.</p>';
                return;
            }

            let html = `
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>Test</th>
                            <th>Status</th>
                            <th>Assigned At</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach(test => {
                html += `
                    <tr>
                        <td>${test.title}</td>
                        <td>${test.status}</td>
                        <td>${test.created_at}</td>
                    </tr>
                `;
            });

            html += `</tbody></table>`;

            document.getElementById('testContent').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('testContent').innerHTML =
                'Error loading tests.';
        });
}

function closeTests()
{
    document.getElementById('testModal').style.display = 'none';
}

</script>
@endsection
