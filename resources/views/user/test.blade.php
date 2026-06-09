@extends('layouts.app')

@section('title', $test->title)

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $test->title }}</h1>
            <p class="meta">You can submit only once. After submission, answers cannot be changed.</p>
        </div>

        <div class="card">
            <div class="meta">Time Remaining</div>
            <div id="timer" class="timer">--:--</div>
        </div>
    </div>

    <div class="flash warning">
        Your timer is locked on the server. Refreshing or leaving this page will not pause the test, and you cannot re-attempt it after submission.
    </div>

    <form id="test-form" method="POST" action="{{ route('user.tests.submit', $test) }}" class="stack" autocomplete="off">
        @csrf

        @foreach($test->questions as $question)
            <div class="card question-card">
                <h3>{{ $loop->iteration }}. {{ $question->question }}</h3>

                <div class="question-options">
                    <label class="option">
                        <input type="radio" name="answers[{{ $question->id }}]" value="A">
                        <span class="option-body"><strong>A.</strong> {{ $question->option_a }}</span>
                    </label>

                    <label class="option">
                        <input type="radio" name="answers[{{ $question->id }}]" value="B">
                        <span class="option-body"><strong>B.</strong> {{ $question->option_b }}</span>
                    </label>

                    <label class="option">
                        <input type="radio" name="answers[{{ $question->id }}]" value="C">
                        <span class="option-body"><strong>C.</strong> {{ $question->option_c }}</span>
                    </label>

                    <label class="option">
                        <input type="radio" name="answers[{{ $question->id }}]" value="D">
                        <span class="option-body"><strong>D.</strong> {{ $question->option_d }}</span>
                    </label>
                </div>
            </div>
        @endforeach

        <div class="inline">
            <button type="submit" class="button primary">Submit Answers</button>
        </div>
    </form>

    <script>
        (() => {
            const form = document.getElementById('test-form');
            const timer = document.getElementById('timer');
            const deadline = {{ $attempt->expires_at->timestamp }} * 1000;
            let submitted = false;

            const formatTime = (seconds) => {
                const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
                const secs = String(seconds % 60).padStart(2, '0');
                return `${mins}:${secs}`;
            };

            const updateTimer = () => {
                const remaining = Math.max(0, Math.floor((deadline - Date.now()) / 1000));
                timer.textContent = formatTime(remaining);

                if (remaining <= 300) {
                    timer.classList.add('danger');
                }

                if (remaining <= 0) {
                    submitted = true;
                    form.submit();
                }
            };

            window.addEventListener('beforeunload', (event) => {
                if (submitted) {
                    return;
                }

                event.preventDefault();
                event.returnValue = '';
            });

            form.addEventListener('submit', () => {
                submitted = true;
            });

            updateTimer();
            setInterval(updateTimer, 1000);
        })();
    </script>
@endsection
