<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'user_id', 'name', 'weeks', 'is_public',
        'goal', 'difficulty', 'description', 'average_time',
    ];

    protected $casts = [
    'is_public' => 'boolean',
    'weeks' => 'integer',
    'average_time' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workouts()
    {
        return $this->belongsToMany(Workout::class, 'program_workout')
                    ->withPivot(['week_no', 'day_no'])
                    ->withTimestamps();
    }
}
