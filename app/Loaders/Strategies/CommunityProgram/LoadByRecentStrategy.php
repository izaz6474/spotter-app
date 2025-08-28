<?php

namespace App\Loaders\Strategies\CommunityProgram;
use App\Loaders\Strategies\LoadStrategy;
use App\Models\Program;
use Illuminate\Database\Eloquent\Collection;

class LoadByRecentStrategy implements LoadStrategy
{
    protected string $searchText;

    public function __construct(string $searchText)
    {
        $this->searchText = $searchText;
    }

    public function load(): Collection
    {
        $communityPrograms = Program::fromQuery("
            SELECT programs.*, COUNT(program_access.id) AS users_count
            FROM programs
            LEFT JOIN program_access ON programs.id = program_access.program_id
            WHERE programs.is_public = TRUE
            GROUP BY programs.id
            ORDER BY programs.created_at DESC
        ");

        return $communityPrograms;
    }
}
