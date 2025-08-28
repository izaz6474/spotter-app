<?php

namespace App\Facades;

use App\Models\Program;
use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;

class LoadTrainingFacade
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getCurrentProgram(): mixed
    {
        $currentProgram = DB::table('program_access')
            ->where('user_id', $this->userId)
            ->select('program_id', 'at_week', 'at_day')
            ->first();

        return $currentProgram;
    }

    public function getWorkout(int $programId, int $week, int $day): Workout
    {
        $workoutId = DB::table('program_workout')
                    ->where('program_id', $programId)
                    ->where('week_no', $week)
                    ->where('day_no', $day)
                    ->value('workout_id');

        $workout = Workout::find($workoutId);

        return $workout;
        
    }   

    public function getExercises(int $workoutId): mixed
    {
        $results = DB::table('workout_exercise as we')
            ->join('exercises as e', 'we.exercise_id', '=', 'e.id')
            ->join('workout_exercise_sets as wes', function($join) {
                $join->on('wes.workout_id', '=', 'we.workout_id')
                    ->on('wes.exercise_id', '=', 'we.exercise_id');
            })
            ->select(
                'e.name as name',
                DB::raw('COUNT(wes.id) as sets'),
                DB::raw('MAX(wes.weight) as top_set'),
            )
            ->where('we.workout_id', $workoutId)
            ->groupBy('we.exercise_id', 'we.exercise_index', 'e.name')
            ->orderBy('we.exercise_index')
            ->get();

            return $results;
    }
}
