@extends('layouts.app')

@section('title', 'Manage Tests')

@section('content')
    <div class="page-header">
        <div>
            <h1>Aptitude Tests</h1>
            <p class="meta">Create tests, manage durations, and attach question banks before users start attempting them.</p>
        </div>

        <a href="{{ route('admin.tests.create') }}" class="button primary">Create Test</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Duration</th>
                    <th>Questions</th>
                    <th>Attempts</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tests as $test)
                    <tr>
                        <td>{{ $test->title }}</td>
                        <td>{{ $test->duration }} minutes</td>
                        <td>{{ $test->questions_count }}</td>
                        <td>{{ $test->attempts_count }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.tests.questions.index', $test) }}" class="button secondary">Questions</a>
                                <a href="{{ route('admin.tests.edit', $test) }}" class="button secondary">Edit</a>

                                <form method="POST" action="{{ route('admin.tests.destroy', $test) }}" onsubmit="return confirm('Delete this test?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">No tests have been created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
    
@endsection
