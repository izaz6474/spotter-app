<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'muscle_targeted',
        'description',
    ];
    
    public const MUSCLE_GROUPS = [
        'legs',
        'arms',
        'back',
        'chest',
        'abs',
        'shoulders',
        'forearms',
    ];


    public function setsForWorkout($workoutId)
    {
    return $this->hasMany(WorkoutExerciseSet::class, 'exercise_id')
                ->where('workout_id', $workoutId)
                ->orderBy('set_index')
                ->get();
    }

}
