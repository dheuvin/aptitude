<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestAttempt;

class ResultController extends Controller
{
    public function index()
    {
        $attempts = TestAttempt::with(['user', 'test'])
            ->latest('submitted_at')
            ->get();

        return view('admin.results.index', compact('attempts'));
    }

    public function show(TestAttempt $attempt)
    {
        $attempt->load([
            'user',
            'test',
            'answers.question'
        ]);

        return view('admin.results.show', compact('attempt'));
    }
}
