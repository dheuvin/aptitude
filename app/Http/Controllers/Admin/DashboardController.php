<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::where('role', 'user')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'tests' => Test::count(),
            'questions' => Question::count(),
            'attempts' => TestAttempt::count(),
            'submitted_attempts' => TestAttempt::where('status', TestAttempt::STATUS_SUBMITTED)->count(),
            'expired_attempts' => TestAttempt::where('status', TestAttempt::STATUS_EXPIRED)->count(),
            'average_score_percent' => round((float) TestAttempt::where('status', TestAttempt::STATUS_SUBMITTED)
                ->where('total_questions', '>', 0)
                ->select(DB::raw('AVG((score / total_questions) * 100) as average_score_percent'))
                ->value('average_score_percent'), 1),
        ];

        $latestAttempts = TestAttempt::with(['user', 'test'])
            ->latest('updated_at')
            ->take(8)
            ->get();

        $topTests = Test::withCount('questions')
            ->withCount('attempts')
            ->withCount([
                'attempts as submitted_attempts_count' => fn ($query) => $query->where('status', TestAttempt::STATUS_SUBMITTED),
            ])
            ->orderByDesc('submitted_attempts_count')
            ->orderByDesc('questions_count')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestAttempts', 'topTests'));
    }
}
