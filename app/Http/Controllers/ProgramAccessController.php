<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgramAccessController extends Controller
{
    
    public function join(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
        ]);

        $userId = Auth::id();
        $programId = $request->program_id;

        DB::table('program_access')->updateOrInsert(
            ['user_id' => $userId],
            ['program_id' => $programId, 'at_week' => 1, 'at_day' => 1, 'created_at' => now(), 'updated_at' => now()]
        );

        return redirect('train');
    }

    public function leave(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
        ]);

        $userId = Auth::id();
        $programId = $request->program_id;

        DB::table('program_access')
            ->where('user_id', $userId)
            ->where('program_id', $programId)
            ->delete();

        return redirect('train');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
        ]);

        $userId = Auth::id();
        $programId = $request->program_id;

        DB::table('program_access')
            ->where('user_id', $userId)
            ->where('program_id', $programId)
            ->update([
                'at_week' => 1,
                'at_day' => 1,
                'updated_at' => now(),
            ]);

        return redirect('train');
    }

    public function progress()
    {

        $userId = Auth::id();

        $programAccess = $currentProgram = DB::table('program_access')
            ->where('user_id', $userId)
            ->select('program_id', 'at_week', 'at_day')
            ->first();

        $programId = $programAccess->program_id;
        $currentWeek = $programAccess->at_week;
        $currentDay  = $programAccess->at_day;

        $case = 3; // default case

        $nextDayExists = DB::table('program_workout')
            ->where('program_id', $programId)
            ->where('week_no', $currentWeek)
            ->where('day_no', $currentDay + 1)
            ->exists();

        if ($nextDayExists) {
            $case = 1;
        } else {
            
            $nextWeekExists = DB::table('program_workout')
                ->where('program_id', $programId)
                ->where('week_no', $currentWeek + 1)
                ->where('day_no', 1)
                ->exists();

            if ($nextWeekExists) {
                $case = 2;
            }
        }

        if ($case === 1) {
            DB::table('program_access')
                ->where('user_id', $userId)
                ->where('program_id', $programId)
                ->update([
                    'at_day' => $currentDay + 1,
                    'updated_at' => now(),
                ]);
        } elseif ($case === 2) {
            DB::table('program_access')
                ->where('user_id', $userId)
                ->where('program_id', $programId)
                ->update([
                    'at_week' => $currentWeek + 1,
                    'at_day' => 1,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('program_access')
                ->where('user_id', $userId)
                ->where('program_id', $programId)
                ->delete();
        }

        return redirect('train');
    }

}
