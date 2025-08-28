<?php

namespace App\Facades;

use App\Models\Program;
use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoadHistory
{

    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
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

    public function load(int $year, int $month): mixed
    {

        $recordsWithExercises = [];

        $user = Auth::id();

        $Records = DB::table('workout_sessions')
            ->where('user_id', $user)  
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($Records as $record) {
            
            $workout = Workout::find($record->workout_id);
            $exercises = $this->getExercises($workout->id);

            $duration = $record->duration;
            $completed_at = $record->updated_at;

            $recordsWithExercises[] = [
                'workout' => $workout,
                'exercises' => $exercises,
                'duration' => $duration,
                'completed_at' => $completed_at,
            ];
        }

        return $recordsWithExercises;
    }

}
