<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\TestController as AdminTestController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\User\TestController as UserTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('role:admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUserController::class)->except('show');
        Route::resource('tests', AdminTestController::class)->except('show');

        Route::post('tests/assign', [AdminTestController::class, 'assignTest'])->name('tests.assign');
        Route::get('users/{user}/tests', [AdminTestController::class, 'userTests'])->name('users.tests');


        Route::get('tests/{test}/questions', [AdminQuestionController::class, 'index'])->name('tests.questions.index');
        Route::get('tests/{test}/questions/create', [AdminQuestionController::class, 'create'])->name('tests.questions.create');
        Route::post('tests/{test}/questions', [AdminQuestionController::class, 'store'])->name('tests.questions.store');
        Route::get('tests/{test}/questions/{question}/edit', [AdminQuestionController::class, 'edit'])->name('tests.questions.edit');
        Route::put('tests/{test}/questions/{question}', [AdminQuestionController::class, 'update'])->name('tests.questions.update');
        Route::delete('tests/{test}/questions/{question}', [AdminQuestionController::class, 'destroy'])->name('tests.questions.destroy');

        Route::get('results', [AdminResultController::class, 'index'])->name('results.index');
        Route::get('results/{attempt}', [AdminResultController::class, 'show'])->name('results.show');
});

Route::middleware('role:user')->group(function () {
    Route::get('/dashboard', [UserTestController::class, 'index'])->name('user.dashboard');
    Route::get('/tests/{test}', [UserTestController::class, 'show'])->name('user.tests.show');
    Route::post('/tests/{test}/submit', [UserTestController::class, 'submit'])->name('user.tests.submit');
    Route::get('/tests/{test}/result', [UserTestController::class, 'result'])->name('user.tests.result');
});
