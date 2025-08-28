<?php

namespace App\Facades;

use App\Models\Workout;
use Illuminate\Support\Facades\DB;

class WorkoutExerciseLoader
{
    public function load(int $id): array
    {
        // 1. Load workout
        $workout = Workout::findOrFail($id);

        $exercises = $workout->exercises();

        //dd($creator$exercises);

        foreach ($exercises as $exercise) {
            $sets = DB::table('workout_exercise_set')
                ->where('workout_id', $workout->id)
                ->where('exercise_id', $exercise->exercise_id)
                ->orderBy('set_index')
                ->get(['set_index', 'weight', 'no_of_reps']);
        }

        return [
            'workout'     => $workout,
            'exercises' => $exercises,
            'exerciseSet' => $sets
        ];
    }
}
