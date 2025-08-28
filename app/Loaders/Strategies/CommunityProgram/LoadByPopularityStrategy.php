<?php

namespace App\Loaders\Strategies\CommunityProgram;
use App\Loaders\Strategies\LoadStrategy;
use App\Models\Program;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LoadByPopularityStrategy implements LoadStrategy
{
    protected string $searchText;

    public function __construct(string $searchText)
    {
        $this->searchText = $searchText;
    }

    public function load(): Collection
    {
        $communityPrograms = Program::fromQuery("
            SELECT programs.*, COUNT(program_access.id) as users_count
            FROM programs
            LEFT JOIN program_access ON programs.id = program_access.program_id
            WHERE is_public = true
            GROUP BY programs.id
            ORDER BY users_count DESC
        ");

        return $communityPrograms;
    }
}
