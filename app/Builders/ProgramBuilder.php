<?php

namespace App\Builders;

use App\Models\Program;
use Illuminate\Support\Facades\Auth;


abstract class ProgramBuilder
{
    protected Program $program;

    public function buildProgram(string $name, int $weeks): self
    {
        $this->program = new Program();
        $this->program->user_id = Auth::id();
        $this->program->name = $name;
        $this->program->weeks = $weeks;

        return $this;
    }

    abstract public function addPublicInfo(bool $is_public, string $goal, string $difficulty, string $description, int $averageMins): self;

    public static function addWorkout(int $week, $workout): void
    {
        $workouts = session('workouts', []);

        if (!isset($workouts[$week])) {
            $workouts[$week] = [];
        }

        $workouts[$week][] = $workout;

        session(['workouts' => $workouts]);
    }

    public function save(): void
    {
        $workouts = session('workouts', []);

        $this->program->save();

        foreach ($workouts as $weekNumber => $workoutsForWeek) {
            foreach ($workoutsForWeek as $index => $workout) {
                $workout_table = $workout->save(); 

                $this->program->workouts()->attach($workout_table->id, [
                    'week_no' => $weekNumber,
                    'day_no' => $index+1
                ]);
            }
        }
        
        session()->forget('workouts');
    }
}
