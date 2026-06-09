@extends('layouts.app')

@section('title', 'Add Question')

@section('content')
    <div class="page-header">
        <div>
            <h1>Add Question</h1>
            <p class="meta">Test: {{ $test->title }}</p>
        </div>
        <a href="{{ route('admin.tests.questions.index', $test) }}" class="button secondary">Back to Questions</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.tests.questions.store', $test) }}" class="stack">
            @csrf

            <div class="field">
                <label for="question">Question</label>
                <textarea id="question" name="question" placeholder="Enter the full question text">{{ old('question') }}</textarea>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="option_a">Option A</label>
                    <input id="option_a" type="text" name="option_a" value="{{ old('option_a') }}">
                </div>

                <div class="field">
                    <label for="option_b">Option B</label>
                    <input id="option_b" type="text" name="option_b" value="{{ old('option_b') }}">
                </div>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="option_c">Option C</label>
                    <input id="option_c" type="text" name="option_c" value="{{ old('option_c') }}">
                </div>

                <div class="field">
                    <label for="option_d">Option D</label>
                    <input id="option_d" type="text" name="option_d" value="{{ old('option_d') }}">
                </div>
            </div>

            <div class="field">
                <label for="correct_answer">Correct Answer</label>
                <select id="correct_answer" name="correct_answer">
                    <option value="A" @selected(old('correct_answer') === 'A')>A</option>
                    <option value="B" @selected(old('correct_answer') === 'B')>B</option>
                    <option value="C" @selected(old('correct_answer') === 'C')>C</option>
                    <option value="D" @selected(old('correct_answer') === 'D')>D</option>
                </select>
            </div>

            <div class="inline">
                <button type="submit" class="button primary">Save Question</button>
            </div>
        </form>
    </div>
@endsection
