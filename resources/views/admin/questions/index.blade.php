@extends('layouts.app')

@section('title', 'Manage Questions')

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $test->title }} Questions</h1>
            <p class="meta">Each question has four options and one correct answer. Once users attempt the test, questions become read only to protect result history.</p>
        </div>

        <div class="inline">
            <a href="{{ route('admin.tests.index') }}" class="button secondary">Back to Tests</a>
            {{-- @if(! $questionManagementLocked) --}}
                <a href="{{ route('admin.tests.questions.create', $test) }}" class="button primary">Add Question</a>
            {{-- @endif --}}
        </div>
    </div>

    {{-- @if($questionManagementLocked)
        <div class="flash warning">
            This test already has attempts, so question creation, editing, and deletion are locked.
        </div>
    @endif --}}

    <div class="stack">
        @forelse($questions as $question)
            <div class="card question-card">
                <div class="inline spaced">
                    <h3>{{ $loop->iteration }}. {{ $question->question }}</h3>

                    <div class="actions">
                        {{-- @if(! $questionManagementLockedc || auth()->user()->role === 'admin') --}}
                            <a href="{{ route('admin.tests.questions.edit', [$test, $question]) }}" class="button secondary">Edit</a>

                            <form method="POST" action="{{ route('admin.tests.questions.destroy', [$test, $question]) }}" onsubmit="return confirm('Delete this question?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button danger">Delete</button>
                            </form>
                        {{-- @endif --}}
                    </div>
                </div>

                <div class="question-options">
                    <div class="option {{ $question->correct_answer === 'A' ? 'correct' : '' }}"><strong>A</strong> <span>{{ $question->option_a }}</span></div>
                    <div class="option {{ $question->correct_answer === 'B' ? 'correct' : '' }}"><strong>B</strong> <span>{{ $question->option_b }}</span></div>
                    <div class="option {{ $question->correct_answer === 'C' ? 'correct' : '' }}"><strong>C</strong> <span>{{ $question->option_c }}</span></div>
                    <div class="option {{ $question->correct_answer === 'D' ? 'correct' : '' }}"><strong>D</strong> <span>{{ $question->option_d }}</span></div>
                </div>
            </div>
        @empty
            <div class="card empty">No questions added yet for this test.</div>
        @endforelse
    </div>
@endsection
