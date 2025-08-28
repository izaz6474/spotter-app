<?php

namespace App\Loaders;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class TopRecommendedProgramLoader
{
    public function load(): Program
    {
        $topProgramId = DB::table('program_access')
        ->select('program_id')
        ->groupBy('program_id')
        ->orderByDesc(DB::raw('COUNT(user_id)'))
        ->value('program_id'); 

        $topProgram = Program::find($topProgramId);

        return $topProgram;
    }
}
