<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestAttempt;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        $userId = (int) session('user_id');

        $attempts = TestAttempt::with([
            'test.questions',
        ])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        foreach ($attempts as $attempt) {
            if (
                $attempt->status === TestAttempt::STATUS_IN_PROGRESS &&
                $attempt->expires_at &&
                now()->greaterThan($attempt->expires_at)
            ) {
                $this->markExpired($attempt);
                $attempt->refresh();
            }
        }

        return view('user.dashboard', compact('attempts'));
    }

    public function show(Test $test)
    {
        if ($test->questions()->count() === 0) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'This test has no questions yet.');
        }

        $attempt = TestAttempt::where('user_id', session('user_id'))
            ->where('test_id', $test->id)
            ->first();

        if (! $attempt) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'This test is not assigned to you.');
        }

        if ($attempt && $attempt->status === TestAttempt::STATUS_SUBMITTED) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'You have already submitted this test and cannot attempt it again.');
        }

        if ($attempt && $attempt->expires_at && now()->greaterThan($attempt->expires_at)) {
            $this->markExpired($attempt);

            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Your test time has expired.');
        }

        if ($attempt->status === TestAttempt::STATUS_EXPIRED) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'This test is no longer available.');
        }

        if ($attempt->status === TestAttempt::STATUS_PENDING) {
            $startedAt = now();

            $attempt->update([
                'total_questions' => $test->questions()->count(),
                'status' => TestAttempt::STATUS_IN_PROGRESS,
                'started_at' => $attempt->started_at ?? $startedAt,
                'expires_at' => $attempt->expires_at ?? $startedAt->copy()->addMinutes($test->duration),
            ]);

            $attempt->refresh();
        }

        $test->load(['questions' => fn ($query) => $query->orderBy('id')]);

        $remainingSeconds = max(0, now()->diffInSeconds($attempt->expires_at, false));

        return view('user.test', compact('test', 'attempt', 'remainingSeconds'));
    }

    public function submit(Request $request, Test $test)
    {
        $request->validate([
            'answers' => ['nullable', 'array'],
            'answers.*' => ['nullable', 'in:A,B,C,D'],
        ]);

        $userId = (int) session('user_id');

        $result = DB::transaction(function () use ($request, $test, $userId) {
            $attempt = TestAttempt::where('user_id', $userId)
                ->where('test_id', $test->id)
                ->lockForUpdate()
                ->first();

            if (! $attempt) {
                return 'missing';
            }

            if ($attempt->status === TestAttempt::STATUS_SUBMITTED) {
                return 'submitted';
            }

            if ($attempt->expires_at && now()->greaterThan($attempt->expires_at)) {
                $this->markExpired($attempt);

                return 'expired';
            }

            $questions = $test->questions()->orderBy('id')->get();
            $submittedAnswers = $request->input('answers', []);
            $score = 0;

            foreach ($questions as $question) {
                $selectedAnswer = $submittedAnswers[$question->id] ?? null;
                $isCorrect = $selectedAnswer !== null && $selectedAnswer === $question->correct_answer;

                if ($isCorrect) {
                    $score++;
                }

                UserAnswer::updateOrCreate(
                    [
                        'test_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                    ],
                    [
                        'selected_answer' => $selectedAnswer,
                        'is_correct' => $isCorrect,
                    ]
                );
            }

            $attempt->update([
                'score' => $score,
                'total_questions' => $questions->count(),
                'status' => TestAttempt::STATUS_SUBMITTED,
                'submitted_at' => now(),
            ]);

            return 'submitted_now';
        });

        if ($result === 'missing') {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Your test session was not found. Please contact the administrator.');
        }

        if ($result === 'submitted') {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'This test was already submitted.');
        }

        if ($result === 'expired') {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Time is over. Your test has been marked as expired.');
        }

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Test submitted successfully.');
    }

    public function result(Test $test)
    {
        $attempt = TestAttempt::where('user_id', session('user_id'))
            ->where('test_id', $test->id)
            ->first();

        if (
            $attempt &&
            $attempt->status === TestAttempt::STATUS_IN_PROGRESS &&
            $attempt->expires_at &&
            now()->greaterThan($attempt->expires_at)
        ) {
            $this->markExpired($attempt);
            $attempt->refresh();
        }

        $attempt = TestAttempt::with(['test', 'answers.question'])
            ->where('user_id', session('user_id'))
            ->where('test_id', $test->id)
            ->whereIn('status', [TestAttempt::STATUS_SUBMITTED, TestAttempt::STATUS_EXPIRED])
            ->first();

        if (! $attempt) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Your result is not available yet.');
        }

        return view('user.result', compact('attempt'));
    }

    private function markExpired(TestAttempt $attempt): void
    {
        if ($attempt->status === TestAttempt::STATUS_SUBMITTED || $attempt->status === TestAttempt::STATUS_EXPIRED) {
            return;
        }

        $attempt->loadMissing('test.questions');

        foreach ($attempt->test->questions as $question) {
            UserAnswer::firstOrCreate(
                [
                    'test_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                ],
                [
                    'selected_answer' => null,
                    'is_correct' => false,
                ]
            );
        }

        $attempt->update([
            'score' => 0,
            'total_questions' => $attempt->test->questions->count(),
            'status' => TestAttempt::STATUS_EXPIRED,
            'submitted_at' => $attempt->submitted_at ?? now(),
        ]);
    }
}
