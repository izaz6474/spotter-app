<?php

namespace App\Factories;

use App\Loaders\CommunityProgramLoader;
use App\Loaders\Strategies\CommunityProgram\LoadByPopularityStrategy;
use App\Loaders\Strategies\CommunityProgram\LoadByRecentStrategy;
use App\Loaders\Strategies\CommunityProgram\LoadBySearchStrategy;

class LoadCommunityProgramFactory
{
    public static function make(int $strategyType, string $searchText): CommunityProgramLoader
    {
        switch ($strategyType) {
            case 1:
                $strategy = new LoadByPopularityStrategy($searchText);
                break;
            case 2:
                $strategy = new LoadByRecentStrategy($searchText);
                break;
            case 3:
                $strategy = new LoadBySearchStrategy($searchText);
                break;
            default:
                $strategy = new LoadByPopularityStrategy($searchText);
                break;
        }

        return new CommunityProgramLoader($strategy);
    }
}
