@extends('layouts.app')

@section('title', 'My Tests')

@section('content')

<div class="page-header">
    <div>
        <h1>Available Tests</h1>

        <p class="meta">
            You can attempt each test only once.
            Your timer starts when you open the test and it does not pause if you refresh or leave the page.
        </p>
    </div>
</div>

<div class="grid cards">

    @forelse($attempts as $attempt)

        @php($test = $attempt->test)

        <div class="card stack">

            <div class="inline spaced">

                <h3>{{ $test->title }}</h3>

                <span class="badge neutral">
                    {{ $testStatusArr[$attempt->status] ?? 'Unknown' }}
                </span>

            </div>

            <div class="meta-grid">

                <div class="meta-item">
                    <strong>Duration</strong>
                    <div>{{ $test->duration }} minutes</div>
                </div>

                <div class="meta-item">
                    <strong>Attempts Allowed</strong>
                    <div>1 only</div>
                </div>

                <div class="meta-item">
                    <strong>Your Score</strong>
                    <div>
                        {{ $attempt->score }}/{{ $attempt->total_questions }}
                    </div>
                </div>

            </div>

            <div class="actions">

                @if ($attempt->status == 'pending')

                    <a href="{{ route('user.tests.show', $test) }}"
                       class="button primary">
                        Start Test
                    </a>

                @elseif ($attempt->status == 'in_progress')

                    <a href="{{ route('user.tests.show', $test) }}"
                       class="button primary">
                        Resume Test
                    </a>

                @elseif ($attempt->status == 'submitted' || $attempt->status == 'expired')

                    <a href="{{ route('user.tests.result', $test) }}"
                       class="button secondary">
                        View Result
                    </a>

                @endif

            </div>

        </div>

    @empty

        <div class="card empty">
            No tests assigned yet.
        </div>

    @endforelse

</div>

@endsection
