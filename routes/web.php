<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResearchTopicController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\ProfileController;



Route::get('/', function () {
    return view('welcome');
});
//Route::middleware('auth')->group(function () {
//    Route::get('/topics', [ResearchTopicController::class, 'index'])->name('topics.index');
//    Route::post('/topics', [ResearchTopicController::class, 'store'])->name('topics.store');
//});

// Student routes
//Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/topics/search-similar', [ResearchTopicController::class, 'searchSimilar'])->name('topics.search-similar');
    Route::get('/topics/{id}', [ResearchTopicController::class, 'show'])->name('topics.show');
    Route::resource('topics', ResearchTopicController::class)->only(['index', 'create', 'store', 'show']);
//});

// Supervisor routes
//Route::middleware(['auth', 'role:supervisor'])->prefix('supervisor')->group(function () {
//    Route::get('/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
//    Route::post('/topics/{topic}/respond', [SupervisorController::class, 'respondToTopic'])
//        ->name('supervisor.respond');
//});

// routes/web.php
Route::middleware(['auth', 'role:supervisor'])->group(function () {
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
    Route::post('/supervisor/topics/{topic}/approve', [SupervisorController::class, 'approveTopic'])->name('supervisor.topics.approve');
    Route::post('/supervisor/topics/{topic}/reject', [SupervisorController::class, 'rejectTopic'])->name('supervisor.topics.reject');
    Route::get('/topics/import', [SupervisorController::class, 'showImportForm'])->name('supervisor.import');
    Route::get('/topics/import/template', [SupervisorController::class, 'downloadTemplate'])->name('supervisor.import.template');
    Route::post('/topics/import', [SupervisorController::class, 'processImport'])->name('supervisor.import.process');

});
Route::get('/test-import',  [SupervisorController::class, 'showImportForm'])->name('test-topic');


Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    // Add existing logout route if not present
});
// routes/web.php

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
