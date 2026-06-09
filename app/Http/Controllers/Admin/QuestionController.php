<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestAttempt;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Test $test)
    {
        $questions = $test->questions()->latest()->get();
        $questionManagementLocked = $this->questionsAreLocked($test);

        return view('admin.questions.index', compact('test', 'questions', 'questionManagementLocked'));
    }

    public function create(Test $test)
    {
        if ($response = $this->redirectIfQuestionsLocked($test)) {
            return $response;
        }

        return view('admin.questions.create', compact('test'));
    }

    public function store(Request $request, Test $test)
    {
        if ($response = $this->redirectIfQuestionsLocked($test)) {
            return $response;
        }

        $validated = $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_answer' => ['required', 'in:A,B,C,D'],
        ]);

        $test->questions()->create($validated);

        return redirect()
            ->route('admin.tests.questions.index', $test)
            ->with('success', 'Question added successfully.');
    }

    public function edit(Test $test, Question $question)
    {
        $this->ensureQuestionBelongsToTest($test, $question);

        if ($response = $this->redirectIfQuestionsLocked($test)) {
            return $response;
        }

        return view('admin.questions.edit', compact('test', 'question'));
    }

    public function update(Request $request, Test $test, Question $question)
    {
        $this->ensureQuestionBelongsToTest($test, $question);

        if ($response = $this->redirectIfQuestionsLocked($test)) {
            return $response;
        }

        $validated = $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_answer' => ['required', 'in:A,B,C,D'],
        ]);

        $question->update($validated);

        return redirect()
            ->route('admin.tests.questions.index', $test)
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Test $test, Question $question)
    {
        $this->ensureQuestionBelongsToTest($test, $question);

        if ($response = $this->redirectIfQuestionsLocked($test)) {
            return $response;
        }

        $question->delete();

        return redirect()
            ->route('admin.tests.questions.index', $test)
            ->with('success', 'Question deleted successfully.');
    }

    private function ensureQuestionBelongsToTest(Test $test, Question $question): void
    {
        abort_unless($question->test_id === $test->id, 404);
    }

    private function questionsAreLocked(Test $test): bool
    {
        return $test->attempts()
            ->where('status', '!=', TestAttempt::STATUS_PENDING)
            ->exists();
    }

    private function redirectIfQuestionsLocked(Test $test)
    {
        if (! $this->questionsAreLocked($test)) {
            return null;
        }

        return redirect()
            ->route('admin.tests.questions.index', $test)
            ->with('error', 'Questions are locked because users have already attempted this test.');
    }
}
