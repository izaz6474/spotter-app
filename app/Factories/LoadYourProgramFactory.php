<?php

namespace App\Factories;

use App\Loaders\YourProgramLoader;
use App\Loaders\Strategies\YourProgram\LoadYourProgramStrategy;
use App\Loaders\Strategies\YourProgram\LoadYourProgramSearchStrategy;

class LoadYourProgramFactory
{
    public static function make(int $userId, int $selectedFilter, string $searchText): YourProgramLoader
    {
        if ($selectedFilter === 3) {
            $strategy = new LoadYourProgramSearchStrategy($userId, $searchText);
        } else {
            $strategy = new LoadYourProgramStrategy($userId);
        }

        return new YourProgramLoader($strategy);
    }
}
