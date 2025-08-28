<?php

namespace App\Factories;

use App\Loaders\WorkoutLoader;
use App\Loaders\Strategies\Workout\LoadWorkoutStrategy;
use App\Loaders\Strategies\Workout\LoadWorkoutSearchStrategy;

class LoadWorkoutFactory
{
    public static function make(int $userId, int $selectedFilter, string $searchText): WorkoutLoader
    {
        if ($selectedFilter === 3) {
            $strategy = new LoadWorkoutSearchStrategy($userId, $searchText);
        } else {
            $strategy = new LoadWorkoutStrategy($userId);
        }

        return new WorkoutLoader($strategy);
    }
}
