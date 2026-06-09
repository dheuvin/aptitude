@extends('layouts.app')

@section('title', 'Edit Test')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Test</h1>
            <p class="meta">Update the test name or duration. Question content stays locked once attempts exist.</p>
        </div>
        <a href="{{ route('admin.tests.index') }}" class="button secondary">Back to Tests</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.tests.update', $test) }}" class="stack">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="title">Test Title</label>
                <input id="title" type="text" name="title" value="{{ old('title', $test->title) }}">
            </div>

            <div class="field">
                <label for="duration">Duration in Minutes</label>
                <input id="duration" type="number" name="duration" value="{{ old('duration', $test->duration) }}" min="1" max="300">
            </div>

            <div class="inline">
                <button type="submit" class="button primary">Update Test</button>
            </div>
        </form>
    </div>
@endsection
