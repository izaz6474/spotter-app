<?php

namespace App\Loaders\Strategies;

use Illuminate\Database\Eloquent\Collection;

interface LoadStrategy
{
    public function load(): Collection;
}