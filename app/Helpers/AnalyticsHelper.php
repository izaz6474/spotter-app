<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class AnalyticsHelper
{
    public static function getWorkoutIdsFromSessions($sessions): array
    {
        // If collection, convert to array
        if (method_exists($sessions, 'pluck')) {
            return $sessions->pluck('workout_id')->toArray();
        }

        return array_column($sessions, 'workout_id');
    }

    public static function getMuscleCountsForWorkout(int $workoutId): array
    {
        return DB::table('workout_exercise as we')
            ->join('exercises as e', 'we.exercise_id', '=', 'e.id')
            ->where('we.workout_id', $workoutId)
            ->select('e.muscle_targeted', DB::raw('COUNT(*) as count'))
            ->groupBy('e.muscle_targeted')
            ->pluck('count', 'e.muscle_targeted')
            ->toArray();
    }

    public static function mergeMuscleCounts(array $allCounts): array
    {
        $merged = [];
        foreach ($allCounts as $counts) {
            foreach ($counts as $muscle => $count) {
                $merged[$muscle] = ($merged[$muscle] ?? 0) + $count;
            }
        }
        return $merged;
    }

    public static function calculateMusclePercentages(array $muscleCounts): array
    {
        $totalExercises = array_sum($muscleCounts);
        $percentages = [];
        foreach ($muscleCounts as $muscle => $count) {
            $percentages[$muscle] = $totalExercises ? round($count / $totalExercises * 100) : 0;
        }
        return $percentages;
    }

    public static function sumDurations($durations): float
    {
        $totalSeconds = 0;

        foreach ($durations as $item) {
            $duration = is_object($item) ? $item->duration : $item;

            $parts = explode(':', $duration);

            if (count($parts) === 2) {
                $hours = 0;
                $minutes = (int)$parts[0];
                $seconds = (int)$parts[1];
            } elseif (count($parts) === 3) {
                $hours = (int)$parts[0];
                $minutes = (int)$parts[1];
                $seconds = (int)$parts[2];
            } else {
                continue;
            }

            $totalSeconds += $hours * 3600 + $minutes * 60 + $seconds;
        }

        return round($totalSeconds / 3600, 2);
    }

    public static function sumLiftedWeight(array $workoutIds): int
    {
        if (empty($workoutIds)) {
            return 0;
        }

        return (int) DB::table('workout_exercise_sets')
                ->whereIn('workout_id', $workoutIds)
                ->sum('weight');
    }


}

