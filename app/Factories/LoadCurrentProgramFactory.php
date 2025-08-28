<?php

namespace App\Factories;

use App\Loaders\CurrentProgramLoader;
use App\Strategies\CurrentProgram\LoadCurrentProgramStrategy;

class LoadCurrentProgramFactory
{
    public static function make(int $userId): CurrentProgramLoader
    {
        return new CurrentProgramLoader($userId);
    }
}
