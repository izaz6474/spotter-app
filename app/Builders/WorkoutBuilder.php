<?php

namespace App\Builders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Workout;

class WorkoutBuilder
{
    protected Workout $workout;
    protected array $exercises = [];

    public function __construct()
    {
        $this->workout = new Workout();
    }

    public function setName(string $name)
    {
        $this->workout->name = $name; 
        return $this;
    }

    public function setNote(?string $note)
    {
        $this->workout->note = $note; 
        return $this;
    }

    public function setInProgram(bool $inProgram)
    {
        $this->workout->inProgram = $inProgram; 
        return $this;
    }

    public function addExercises(array $exercises)
    {
        $this->exercises = $exercises; 
        return $this;
    }

    public function getName(): ?string
    {
        return $this->workout->name;
    }

    public function getNote(): ?string
    {
        return $this->workout->note;
    }

    public function getExercises(): array
    {
        return $this->exercises;
    }

    public function save(): Workout
    {
        return DB::transaction(function () {

            $this->workout->user_id = Auth::id() ?? 1; 

            $this->workout->save();

            foreach ($this->exercises as $i => $exercise) {
                $this->workout->exercises()->attach($exercise['id'], [
                    'exercise_index' => $i + 1,
                ]);

                foreach ($exercise['sets'] as $j => $set) {
                    DB::table('workout_exercise_sets')->insert([
                        'workout_id' => $this->workout->id,
                        'exercise_id' => $exercise['id'],
                        'set_index'  => $j + 1,
                        'weight'     => $set['kg'] ?? 0,
                        'no_of_reps' => $set['reps'] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return $this->workout;
        });
    }
}
