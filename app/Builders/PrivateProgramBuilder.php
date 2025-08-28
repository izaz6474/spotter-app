<?php

namespace App\Builders;

class PrivateProgramBuilder extends ProgramBuilder
{
    public function addPublicInfo(bool $is_public, string $goal, string $difficulty, string $description, int $averageMins): self
    {
        $this->program->is_public = $is_public;
        $this->program->goal = null;
        $this->program->difficulty = null;
        $this->program->description = null;
        $this->program->average_time = null;

        return $this;
    }
}