@extends('layouts.app')

@section('title', 'My Result')

@section('content')
    <div class="page-header">
        <div>
            <h1>My Result</h1>
            <p class="meta">Review your submitted answers and final score for {{ $attempt->test->title }}.</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="button secondary">Back to Dashboard</a>
    </div>

    <div class="card stack">
        <div class="meta-grid">
            <div class="meta-item">
                <strong>Test</strong>
                <div>{{ $attempt->test->title }}</div>
            </div>
            <div class="meta-item">
                <strong>Status</strong>
                <div>{{ str_replace('_', ' ', ucfirst($attempt->status)) }}</div>
            </div>
            <div class="meta-item">
                <strong>Score</strong>
                <div>{{ $attempt->score }}/{{ $attempt->total_questions }}</div>
            </div>
            <div class="meta-item">
                <strong>Submitted</strong>
                <div>{{ $attempt->submitted_at?->format('d M Y, h:i A') ?? 'Not submitted' }}</div>
            </div>
        </div>
    </div>

    <div class="stack" style="margin-top: 20px;">
        @forelse($attempt->answers->sortBy('question_id') as $answer)
            <div class="card question-card">
                <h3>{{ $loop->iteration }}. {{ $answer->question->question }}</h3>

                <div class="question-options">
                    <div class="option {{ $answer->question->correct_answer === 'A' ? 'correct' : '' }} {{ $answer->selected_answer === 'A' && ! $answer->is_correct ? 'wrong' : '' }}">
                        <strong>A</strong>
                        <span>{{ $answer->question->option_a }}</span>
                    </div>
                    <div class="option {{ $answer->question->correct_answer === 'B' ? 'correct' : '' }} {{ $answer->selected_answer === 'B' && ! $answer->is_correct ? 'wrong' : '' }}">
                        <strong>B</strong>
                        <span>{{ $answer->question->option_b }}</span>
                    </div>
                    <div class="option {{ $answer->question->correct_answer === 'C' ? 'correct' : '' }} {{ $answer->selected_answer === 'C' && ! $answer->is_correct ? 'wrong' : '' }}">
                        <strong>C</strong>
                        <span>{{ $answer->question->option_c }}</span>
                    </div>
                    <div class="option {{ $answer->question->correct_answer === 'D' ? 'correct' : '' }} {{ $answer->selected_answer === 'D' && ! $answer->is_correct ? 'wrong' : '' }}">
                        <strong>D</strong>
                        <span>{{ $answer->question->option_d }}</span>
                    </div>
                </div>

                <div class="inline" style="margin-top: 14px;">
                    <span class="badge neutral">Your Answer: {{ $answer->selected_answer ?? 'Not answered' }}</span>
                    <span class="badge {{ $answer->is_correct ? '' : 'danger' }}">
                        {{ $answer->is_correct ? 'Correct' : 'Incorrect' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="card empty">No answers are available for this attempt.</div>
        @endforelse
    </div>
@endsection
