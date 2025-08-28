<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{

    protected $fillable = [
        'name',
        'note',
        'user_id',
        'inProgram',
    ];

    protected $casts = [
        'note' => 'string',
        'inProgram' => 'boolean',
    ];

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_workout')
                    ->withPivot('week_no', 'day_no')
                    ->withTimestamps();
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'workout_exercise')
                    ->orderBy('exercise_index')
                    ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sessions()
    {
        return $this->hasMany(WorkoutSession::class);
    }

}
