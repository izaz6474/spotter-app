<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Builders\WorkoutBuilder;
use App\Builders\ProgramBuilder;
use App\Models\Workout;

class WorkoutController extends Controller
{
    public function create()
    {
        return view('create-workout');
    }

    public function show(int $id, Request $request)
    {
        $workout = Workout::findOrFail($id);
        $from = $request->input('from');
        //dd($from);
        return view('show-workout', compact('workout', 'from'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'week' => 'required|integer|min:1', 
            'inProgram' => 'required|boolean',

            'exercises' => 'required|array|min:1',
            'exercises.*.id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|array|min:1',
            'exercises.*.sets.*.kg' => 'nullable|numeric|min:0',
            'exercises.*.sets.*.reps' => 'nullable|integer|min:0',
        ]);

        $Builder = new WorkoutBuilder();

        if(!isset($validated['inProgram']) || !$validated['inProgram']) {
            $Builder
            ->setName($validated['name'])
            ->setNote($validated['note'] ?? null)
            ->setInProgram((bool) $validated['inProgram'])
            ->addExercises($validated['exercises'])
            ->save();
            return redirect()->route('train');

        } else {
            $week = $validated['week'];
            $workout = $Builder
            ->setName($validated['name'])
            ->setNote($validated['note'] ?? null)
            ->setInProgram((bool) $validated['inProgram'])
            ->addExercises($validated['exercises']);

            ProgramBuilder::addWorkout($week, $workout);

            return redirect()->route('programs.addWorkout', ['week' => $request->week]);
            
        }

    }

}
