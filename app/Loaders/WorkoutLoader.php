<?php

namespace App\Loaders;

class WorkoutLoader
{
    protected $strategy;

    public function __construct($strategy)
    {
        $this->strategy = $strategy;
    }

    public function load()
    {
        return $this->strategy->load();
    }

}
