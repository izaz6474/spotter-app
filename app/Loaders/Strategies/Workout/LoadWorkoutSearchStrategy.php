<?php

namespace App\Loaders\Strategies\Workout;

use App\Loaders\Strategies\LoadStrategy;
use App\Models\Workout;
use Illuminate\Database\Eloquent\Collection;

class LoadWorkoutSearchStrategy implements LoadStrategy
{
    protected int $userId;
    protected string $searchText;

    public function __construct(int $userId, string $searchText)
    {
        $this->userId = $userId;
        $this->searchText = $searchText;
    }

    public function load(): Collection
    {
        $userId = $this->userId;
        $searchString = $this->searchText;
        $yourWorkouts = Workout::where('user_id', $userId)
                   ->where('name', 'like', '%' . $searchString . '%')
                   ->where('inProgram', 0)
                   ->orderBy('created_at', 'desc')
                   ->get();
                   
        return $yourWorkouts;
    }
}