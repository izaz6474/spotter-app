<?php

namespace App\Loaders\Strategies\Workout;

use App\Loaders\Strategies\LoadStrategy;
use App\Models\Workout;
use Illuminate\Database\Eloquent\Collection;

class LoadWorkoutStrategy implements LoadStrategy
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function load(): Collection
    {
        $userId = $this->userId;
        $yourWorkouts = Workout::where('user_id', $userId)
                        ->where('inProgram', 0)
                        ->orderBy('created_at', 'desc')
                        ->get();
        return $yourWorkouts;
    }
}
