<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Builders\PublicProgramBuilder;
use App\Builders\PrivateProgramBuilder;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{

    public function create()
    {
        $programData = session('program_data', []);
        return view('create-program', compact('programData'));
    }

    public function show($id)
    {
        $program = Program::findOrFail($id);
        $workoutPivot = DB::table('program_workout')
            ->where('program_id', $id)
            ->select('workout_id', 'week_no', 'day_no')
            ->get();

        return view('show-program', compact('program', 'workoutPivot'));
    }

    public function createAdditional()
    {
        $programData = session('program_data', []); 
        return view('create-public-program', compact('programData'));
    }

    public function removeWorkout(Request $request)
    {
        $week = $request->input('week');
        $index = $request->input('index');

        $workouts = session('workouts', []);

        if (isset($workouts[$week][$index])) {
            unset($workouts[$week][$index]);
            $workouts[$week] = array_values($workouts[$week]);
            session(['workouts' => $workouts]);
        }

        return back();
    }

    public function addWorkoutView()
    {
        $workouts = session('workouts', []);
        $programData = session('program_data', []);
        return view('add-workout', compact('programData', 'workouts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'weeks' => 'required|integer|min:1|max:12',
            'is_public' => 'nullable|boolean',
        ]);

        $programData = session('program_data', []);
        session(['program_data' => array_merge($programData, $validated)]);


        if (request('is_public')) {
            return redirect()->route('programs.createAdditional');
        } else {
            return redirect()->route('programs.addWorkout');
        }

    }

    public function storeAdditional(Request $request)
    {
        $validated = $request->validate([
            'goal' => 'required|string|max:255',
            'difficulty' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'average_mins' => 'required|integer|min:1|max:300',
        ]);

        $programData = session('program_data', []);
        session(['program_data' => array_merge($programData, $validated)]);

        return redirect()->route('programs.addWorkout');
    }

    public function addWorkoutAndSave()
    {
        $programData = session('program_data', []);

        if (empty($programData)) {
        return redirect()->route('program.create')
            ->with('error', 'No program data found.');
        }

        $is_public = $programData['is_public'] ?? false;

        if ($is_public) {
            $Builder = new PublicProgramBuilder($programData);
        } else {
            $Builder = new PrivateProgramBuilder($programData);
        }

        $Builder
            ->buildProgram($programData['name'], $programData['weeks'])
            ->addPublicInfo(
                $is_public,
                $programData['goal'] ?? '',
                $programData['difficulty'] ?? '',
                $programData['description'] ?? '',
                $programData['average_mins'] ?? 0
            )->save();

        session()->forget(['program_data', 'workouts']);
    
        return redirect()->route('train');
    }

}
