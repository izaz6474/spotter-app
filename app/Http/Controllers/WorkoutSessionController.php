<?php

namespace App\Http\Controllers;

use App\Services\WorkoutSessionService;
use Illuminate\Support\Facades\Auth;
use App\Models\Workout;
use Illuminate\Http\Request;

class WorkoutSessionController extends Controller
{
    public function start($workoutId, Request $request)
    {
        $user = Auth::user();
        $workout = Workout::with('exercises')->findOrFail($workoutId);

        $isProgram = $request->has('train') ? true : false;

        //dd($isProgram);

        if (WorkoutSessionService::hasActiveSession()) {
            $activeSession = WorkoutSessionService::getInstance();

            if ($activeSession->getID() == $workoutId) {
                return view('workouts-start', compact('activeSession', 'workout'));
            }

            return view('workouts-start', compact('activeSession', 'workout'))
                ->with('showPopup', true);
        }

        $activeSession = WorkoutSessionService::getInstance($workoutId);

        if($isProgram) {
            $activeSession->setisProgram();
        }

        return view('workouts-start', compact('activeSession', 'workout'));
    }

    public function pause()
    {
        $service = WorkoutSessionService::getInstance();
        $service->pause();

        return back();
    }

    public function resume()
    {
        $service = WorkoutSessionService::getInstance();
        $service->resume();

        return back();
    }

    public function finish(Request $request)
    {
        $service = WorkoutSessionService::getInstance();
        $elapsed = $request->input('elapsed_time');
        $service->setDuration($elapsed);
        $service->finish();

        if($service->getisProgram()){
            return redirect()->route('program.progress');
        }

        return redirect('home');
    }

    public function discard()
    {
        $service = WorkoutSessionService::getInstance();
        $id = $service->discard();

        $workout = Workout::with('exercises')->findOrFail($id);

        if($service->getisProgram()){
            return redirect('train');
        }

        return view('show-workout', compact('workout'));
    }

}
