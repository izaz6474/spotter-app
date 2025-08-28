<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutExerciseSet extends Model
{
    protected $fillable = [
        'workout_id',
        'exercise_id',
        'set_id',
        'no_of_sets',
        'no_of_reps',
    ];

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
