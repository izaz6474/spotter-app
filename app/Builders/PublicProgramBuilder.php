<?php

namespace App\Builders;

class PublicProgramBuilder extends ProgramBuilder
{
    public function addPublicInfo(bool $is_public, string $goal, string $difficulty, string $description, int $averageMins): self
    {
        $this->program->is_public = $is_public;
        $this->program->goal = $goal;
        $this->program->difficulty = $difficulty;
        $this->program->description = $description;
        $this->program->average_time = $averageMins;

        return $this;
    }
}