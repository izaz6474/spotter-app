<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProgramController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WorkoutSessionController;
use App\Http\Controllers\ProgramAccessController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AnalyticsController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Home route
    Route::post('/home', [HomeController::class, 'index'])->name('home.submit');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    //Analytics routes
    Route::get('/analytics', [AnalyticsController::class, 'load'])->name('analytics');

    //History routes
    Route::get('/history', [HistoryController::class, 'load'])->name('history');
    //Route::post('/history', [HistoryController::class, 'load'])->name('history');

    // Train routes
    Route::get('/train', [TrainController::class, 'loadCurrentProgram'])->name('train');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Create Program routes
    Route::get('/program/create', [ProgramController::class, 'create'])->name('program-controller.create');
    Route::get('/programs/create/additional', [ProgramController::class, 'createAdditional'])->name('programs.createAdditional');

    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::post('/programs/create/additional', [ProgramController::class, 'storeAdditional'])->name('programs.storeAdditional');

    Route::get('/programs/add-workout', [ProgramController::class, 'addWorkoutView'])->name('programs.addWorkout');
    Route::post('/program/remove-workout', [ProgramController::class, 'removeWorkout'])->name('program.removeWorkout');
    Route::get('/programs/add-workout/save', [ProgramController::class, 'addWorkoutAndSave'])->name('program.Save');

    Route::get('/programs/{program}', [ProgramController::class, 'show'])->name('programs.show');

    // Create Workout routes
    Route::get('/workouts/{id}', [WorkoutController::class, 'show'])->name('workouts.show');
    Route::get('/workout/create', [WorkoutController::class, 'create'])->name('workout-controller.create');
    Route::post('/workouts', [WorkoutController::class, 'store'])->name('workouts.store');
    
    
    //Exercise routes
    Route::middleware('auth')->get('/exercises', [ExerciseController::class, 'index']);

    //Program access routes
    Route::post('/program/join', [ProgramAccessController::class, 'join'])->name('program.join');
    Route::post('/program/leave', [ProgramAccessController::class, 'leave'])->name('program.leave');
    Route::post('/program/reset', [ProgramAccessController::class, 'reset'])->name('program.reset');
    Route::get('/program/progress', [ProgramAccessController::class, 'progress'])->name('program.progress');

});

//Workout Session routes
Route::middleware('auth')->prefix('workout-sessions')->group(function () {

    Route::post('/start/{workoutId}', [WorkoutSessionController::class, 'start'])->name('workout-sessions.start');

    Route::get('/start/{id}', [WorkoutSessionController::class, 'start'])->name('workout-sessions.start');

    Route::post('/pause', [WorkoutSessionController::class, 'pause'])->name('workout-sessions.pause');

    Route::post('/resume', [WorkoutSessionController::class, 'resume'])->name('workout-sessions.resume');

    Route::post('/finish', [WorkoutSessionController::class, 'finish'])->name('workout-sessions.finish');

    Route::post('/discard', [WorkoutSessionController::class, 'discard'])->name('workout-sessions.discard');
});


require __DIR__.'/auth.php';
