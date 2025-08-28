<?php

namespace App\Factories;

use App\Loaders\TopRecommendedProgramLoader;
use App\Strategies\TopRecommendedProgram\LoadTopRecommendedProgramStrategy;

class LoadTopRecommendedProgramFactory
{
    public static function make(): TopRecommendedProgramLoader
    {
        return new TopRecommendedProgramLoader();
    }
}
