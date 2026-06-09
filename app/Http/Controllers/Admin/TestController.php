<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\User;
use App\Models\TestAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TestController extends Controller
{
    public function index()
    {
        $tests = Test::withCount(['questions', 'attempts'])
            ->latest()
            ->get();

        $users = User::where('role', 'user')
            ->latest()
            ->get();

        return view('admin.tests.index', compact('tests', 'users'));
    }


    public function create()
    {
        return view('admin.tests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'integer', 'min:1', 'max:300'],
        ]);

        Test::create($validated);

        return redirect()
            ->route('admin.tests.index')
            ->with('success', 'Test created successfully.');
    }

    public function edit(Test $test)
    {
        return view('admin.tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'integer', 'min:1', 'max:300'],
        ]);

        $test->update($validated);

        return redirect()
            ->route('admin.tests.index')
            ->with('success', 'Test updated successfully.');
    }
    public function assignTest(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'test_id' => ['required', 'exists:tests,id'],
        ]);

        $user = User::where('id', $validated['user_id'])
            ->where('role', 'user')
            ->first();

        if (! $user) {
            return back()->with('error', 'You can assign tests only to user accounts.');
        }

        $alreadyAssigned = TestAttempt::where('user_id', $validated['user_id'])
            ->where('test_id', $validated['test_id'])
            ->exists();

        if ($alreadyAssigned) {
            return back()->with('error', 'Test already assigned.');
        }

        $test = Test::withCount('questions')->findOrFail($validated['test_id']);

        TestAttempt::create([
            'user_id' => $validated['user_id'],
            'test_id' => $validated['test_id'],
            'score' => 0,
            'total_questions' => $test->questions_count,
            'status' => TestAttempt::STATUS_PENDING,
        ]);

        return back()->with('success', 'Test assigned successfully.');
    }

    public function userTests(User $user)
    {
        if ($user->role !== 'user') {
            return response()->json([]);
        }

        $tests = Test::query()
            ->join('test_attempts', 'tests.id', '=', 'test_attempts.test_id')
            ->where('test_attempts.user_id', $user->id)
            ->select([
                'tests.id',
                'tests.title',
                'test_attempts.status',
                'test_attempts.created_at',
            ])
            ->orderBy('test_attempts.created_at', 'desc')
            ->get()
            ->map(function ($test) {
                $test->status = str_replace('_', ' ', ucfirst($test->status));
                $test->created_at = $test->created_at
                    ? \Illuminate\Support\Carbon::parse($test->created_at)->format('d M Y, h:i A')
                    : null;

                return $test;
            });

        return response()->json($tests);
    }

    public function destroy(Test $test)
    {
        // if ($test->attempts()->exists()) {
        //     return back()->with('error', 'Tests with attempt history cannot be deleted.');
        // }

        $test->delete();

        return redirect()
            ->route('admin.tests.index')
            ->with('success', 'Test deleted successfully.');
    }
}
