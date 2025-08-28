<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\AnalyticsHelper;

class AnalyticsHelperTest extends TestCase
{
    /** 
     * @test
     * @dataProvider mergeCountsProvider
     */
    public function it_merges_muscle_counts_correctly($input, $expected)
    {
        $this->assertEquals($expected, AnalyticsHelper::mergeMuscleCounts($input));
    }

    public static function mergeCountsProvider(): array
    {
        return [
            'simple merge' => [
                [
                    ['chest' => 2, 'back' => 3],
                    ['chest' => 1, 'arms' => 4],
                    ['legs' => 5, 'back' => 2]
                ],
                [
                    'chest' => 3,
                    'back' => 5,
                    'arms' => 4,
                    'legs' => 5
                ]
            ],
            'empty arrays' => [
                [],
                []
            ],
            'single array' => [
                [['chest' => 1]],
                ['chest' => 1]
            ]
        ];
    }

    /** 
     * @test
     * @dataProvider calculatePercentagesProvider
     */
    public function it_calculates_muscle_percentages_correctly($input, $expected)
    {
        $this->assertEquals($expected, AnalyticsHelper::calculateMusclePercentages($input));
    }

    public static function calculatePercentagesProvider(): array
    {
        return [
            'normal case' => [
                ['chest' => 3, 'back' => 5, 'arms' => 2],
                [
                    'chest' => round(3 / 10 * 100),
                    'back' => round(5 / 10 * 100),
                    'arms' => round(2 / 10 * 100)
                ]
            ],
            'empty input' => [
                [],
                []
            ],
            'zero total' => [
                ['chest' => 0, 'back' => 0],
                ['chest' => 0, 'back' => 0]
            ]
        ];
    }

    /** @test */
    public function it_calculates_total_hours_from_mixed_durations()
    {
        $durations = [
            '01:30:00', // 1.5 hours
            '00:45:30', // 0.7583 hours
            '55:20',    // 0.922 hours
        ];

        expect(AnalyticsHelper::sumDurations($durations))
            ->toBe(3.18); // 1.5 + 0.7583 + 0.922 ≈ 3.18
    }

    /** @test */
    public function it_handles_only_mm_ss_format()
    {
        $durations = [
            '30:00', // 0.5 hours
            '45:00', // 0.75 hours
        ];

        expect(AnalyticsHelper::sumDurations($durations))
            ->toBe(1.25); // 0.5 + 0.75
    }

    /** @test */
    public function it_handles_only_hh_mm_ss_format()
    {
        $durations = [
            '01:15:00', // 1.25 hours
            '02:30:30', // 2.5083 hours
        ];

        expect(AnalyticsHelper::sumDurations($durations))
            ->toBe(3.76); // 1.25 + 2.5083 ≈ 3.76
    }

}

