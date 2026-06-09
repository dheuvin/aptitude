@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p class="meta">Overview of users, tests, attempts, and platform activity.</p>
        </div>
    </div>

    <div class="grid stats">
        <div class="card stat"><span class="meta">Users</span><strong>{{ $stats['users'] }}</strong></div>
        <div class="card stat"><span class="meta">Admins</span><strong>{{ $stats['admins'] }}</strong></div>
        <div class="card stat"><span class="meta">Tests</span><strong>{{ $stats['tests'] }}</strong></div>
        <div class="card stat"><span class="meta">Questions</span><strong>{{ $stats['questions'] }}</strong></div>
        <div class="card stat"><span class="meta">Attempts</span><strong>{{ $stats['attempts'] }}</strong></div>
        <div class="card stat"><span class="meta">Completed</span><strong>{{ $stats['submitted_attempts'] }}</strong></div>
        <div class="card stat"><span class="meta">Expired</span><strong>{{ $stats['expired_attempts'] }}</strong></div>
        <div class="card stat"><span class="meta">Avg Score</span><strong>{{ $stats['average_score_percent'] }}%</strong></div>
    </div>

    <div class="grid cards" style="margin-top: 22px;">
        <div class="card stack">
            <div class="inline spaced">
                <h3>Recent Attempts</h3>
                <a href="{{ route('admin.results.index') }}" class="button secondary">View All</a>
            </div>

            @forelse($latestAttempts as $attempt)
                <div class="meta-item">
                    <strong>{{ $attempt->user->phone }}</strong>
                    <div>{{ $attempt->test->title }}</div>
                    <div class="inline" style="margin-top: 10px;">
                        <span class="badge neutral">{{ ucfirst($attempt->status) }}</span>
                        <span class="badge">{{ $attempt->score }}/{{ $attempt->total_questions }}</span>
                    </div>
                </div>
            @empty
                <div class="empty">No attempts yet.</div>
            @endforelse
        </div>

        <div class="card stack">
            <div class="inline spaced">
                <h3>Most Used Tests</h3>
                <a href="{{ route('admin.tests.index') }}" class="button secondary">Manage Tests</a>
            </div>

            @forelse($topTests as $test)
                <div class="meta-item">
                    <strong>{{ $test->title }}</strong>
                    <div>{{ $test->questions_count }} questions</div>
                    <div>{{ $test->submitted_attempts_count }} completed attempts</div>
                </div>
            @empty
                <div class="empty">No test data yet.</div>
            @endforelse
        </div>
    </div>
@endsection
