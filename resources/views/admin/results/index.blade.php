@extends('layouts.app')

@section('title', 'Test Attempts')

@section('content')
    <div class="page-header">
        <div>
            <h1>All Test Attempts</h1>
            <p class="meta">Review user scores, submission status, and detailed answer sheets.</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Phone</th>
                    <th>Test</th>
                    <th>Status</th>
                    <th>Score</th>
                    <th>Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attempts as $attempt)
                    <tr>
                        <td>{{ $attempt->user->phone }}</td>
                        <td>{{ $attempt->test->title }}</td>
                        <td>
                            <span class="badge {{ $attempt->status === 'submitted' ? '' : ($attempt->status === 'expired' ? 'danger' : 'warning') }}">
                                {{ str_replace('_', ' ', ucfirst($attempt->status)) }}
                            </span>
                        </td>
                        <td>
                            {{ $attempt->score }}/{{ $attempt->total_questions }}
                        </td>
                        <td>{{ $attempt->submitted_at?->format('d M Y, h:i A') ?? 'Not submitted' }}</td>
                        <td>
                            <a href="{{ route('admin.results.show', $attempt) }}" class="button secondary">View Answers</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">No attempts recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
