@extends('layouts.app')

@section('title', 'Create Test')

@section('content')
    <div class="page-header">
        <div>
            <h1>Create Aptitude Test</h1>
            <p class="meta">Set the test title and duration first, then add questions with four answer options.</p>
        </div>
        <a href="{{ route('admin.tests.index') }}" class="button secondary">Back to Tests</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.tests.store') }}" class="stack">
            @csrf

            <div class="field">
                <label for="title">Test Title</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" placeholder="Example: Quantitative Aptitude Round 1">
            </div>

            <div class="field">
                <label for="duration">Duration in Minutes</label>
                <input id="duration" type="number" name="duration" value="{{ old('duration', 30) }}" min="1" max="300">
            </div>

            <div class="inline">
                <button type="submit" class="button primary">Create Test</button>
            </div>
        </form>
    </div>
@endsection
