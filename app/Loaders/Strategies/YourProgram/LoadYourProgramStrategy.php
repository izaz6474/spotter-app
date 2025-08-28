<?php

namespace App\Loaders\Strategies\YourProgram;

use App\Loaders\Strategies\LoadStrategy;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Program;

class LoadYourProgramStrategy implements LoadStrategy
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function load(): Collection
    {
        $userId = $this->userId;

        $Programs = Program::where('user_id', $userId)
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        return $Programs;  
    }
}