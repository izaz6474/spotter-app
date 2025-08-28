<?php

namespace App\Loaders\Strategies\YourProgram;

use App\Loaders\Strategies\LoadStrategy;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Program;

class LoadYourProgramSearchStrategy implements LoadStrategy{
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

        $Programs = Program::where('user_id', $userId)
                   ->where('name', 'like', '%' . $searchString . '%')
                   ->orderBy('created_at', 'desc')
                   ->get();
                   
        return $Programs;
    }
}
