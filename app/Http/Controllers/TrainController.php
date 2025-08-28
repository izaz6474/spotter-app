<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Facades\LoadTrainingFacade;
use Illuminate\Support\Facades\Auth;

class TrainController extends Controller
{
    public function loadCurrentProgram()
    {

        $facade = new LoadTrainingFacade(Auth::id());
        
        $programDetails = $facade->getCurrentProgram();
        if ($programDetails) {

        $program = Program::find($programDetails->program_id);
        $programName = $program->name;
        $week = $programDetails->at_week;
        $day = $programDetails->at_day;

        $workout = $facade->getWorkout($program->id, $week, $day);

        $exercises = $facade->getExercises($workout->id);

        } else {
            $program = null;
        }

        //dd($exercises);

        return view('train', [
            'programName' => $programName ?? null,
            'week' => $week ?? null,
            'day' => $day ?? null,
            'exercises' => $exercises ?? null,
            'workout' => $workout ?? null
        ]);
    }

}
