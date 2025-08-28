<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AnalyticsHelper;

class AnalyticsController extends Controller
{
    public function load(Request $request)
    {
        $userId = Auth::id();

        $scope = $request->input('period', 'recent');

        // sessions for lifetime calculation
        $allSessions = DB::table('workout_sessions')
                ->where('user_id', $userId)
                ->select('duration', 'workout_id')
                ->get();

        
        $lifetimeWorkoutIds = AnalyticsHelper::getWorkoutIdsFromSessions($allSessions);

        $lifetime = [
            'workouts' => $allSessions->count(),

            'hours' => AnalyticsHelper::sumDurations($allSessions),

            'lifted' => AnalyticsHelper::sumLiftedWeight($lifetimeWorkoutIds),
        ];

        // Weekly record (last 7 days)
        $weeklySessions = DB::table('workout_sessions')
                ->where('user_id', $userId)
                ->whereRaw('start_time >= NOW() - INTERVAL 7 DAY')
                ->select('duration', 'workout_id')
                ->get();

        $weeklyWorkoutIds = AnalyticsHelper::getWorkoutIdsFromSessions($weeklySessions);

        $weekly = [
            'workouts' => $weeklySessions->count(),

            'hours' => AnalyticsHelper::sumDurations($weeklySessions),

            'lifted' => AnalyticsHelper::sumLiftedWeight($weeklyWorkoutIds),
        ];

        // Muscle tracker
        if ($scope === 'recent') {
            // last 10 workouts
            $sessions = DB::table('workout_sessions')
                ->where('user_id', $userId)
                ->orderByDesc('end_time')
                ->limit(10)
                ->select('workout_id')
                ->get();
        } else {
            // lifetime: all sessions
            $sessions = DB::table('workout_sessions')
                ->where('user_id', $userId)
                ->select('workout_id')
                ->get();
        }

        if ($sessions->isEmpty()) {
            $muscleData = null; // Not enough data
        } else {
            $workoutIds = AnalyticsHelper::getWorkoutIdsFromSessions($sessions);

            $muscleCountsPerWorkout = [];
            foreach ($workoutIds as $workoutId) {
                $muscleCountsPerWorkout[] = AnalyticsHelper::getMuscleCountsForWorkout($workoutId);
            }

            $mergedCounts = AnalyticsHelper::mergeMuscleCounts($muscleCountsPerWorkout);

            $totalExercises = array_sum($mergedCounts);
            $muscleData = AnalyticsHelper::calculateMusclePercentages($mergedCounts);
        }

        return view('analytics', compact(
            'lifetime', 'weekly', 'muscleData', 'scope'
        ));
    }
}
