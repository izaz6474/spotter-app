<?php

namespace App\Loaders;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class CurrentProgramLoader
{
    protected int $userId;
    
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function load(): ?Program
    {
        $userId = $this->userId;

        $currentProgramId = DB::table('program_access')
        ->where('user_id', $userId)
        ->value('program_id'); 

        $currentProgram = Program::find($currentProgramId);

        return $currentProgram;
    }
    
}
