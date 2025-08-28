<?php

namespace App\Facades;

use App\Factories\LoadCommunityProgramFactory;
use App\Factories\LoadTopRecommendedProgramFactory;
use App\Factories\LoadCurrentProgramFactory;
use App\Factories\LoadWorkoutFactory;
use App\Factories\LoadYourProgramFactory;

class HomeLoader
{
    protected int $selectedTab;
    protected int $selectedFilter;
    protected string $searchText;
    protected int $userId;

    public function __construct(int $selectedTab, int $selectedFilter, string $searchText, int $userId)
    {
        $this->selectedTab = $selectedTab;
        $this->selectedFilter = $selectedFilter;
        $this->searchText = $searchText;
        $this->userId = $userId;
        //dd($selectedTab, $selectedFilter, $searchText, $userId);
    }

    public function load()
    {
        $communityPrograms = [];
        $yourPrograms = [];
        $yourWorkouts = [];

        switch ($this->selectedTab) {
            case 1:
                $communityPrograms = LoadCommunityProgramFactory::make($this->selectedFilter, $this->searchText)->load();
                break;

            case 2:
                $yourPrograms = LoadYourProgramFactory::make($this->userId, $this->selectedFilter, $this->searchText)->load();
                break;

            case 3: 
                $yourWorkouts = LoadWorkoutFactory::make($this->userId, $this->selectedFilter, $this->searchText)->load();
                break;
        }

        $currentProgram = LoadCurrentProgramFactory::make($this->userId)->load();
        $topRecommended = LoadTopRecommendedProgramFactory::make()->load();

        return [
            'communityPrograms' => $communityPrograms,
            'yourPrograms' => $yourPrograms,
            'yourWorkouts' => $yourWorkouts,
            'currentProgram' => $currentProgram,
            'topRecommendedProgram' => $topRecommended,
            'selectedTab' => $this->selectedTab,
            'selectedFilter' => $this->selectedFilter,
            'searchText' => $this->searchText
        ];
    }
}
