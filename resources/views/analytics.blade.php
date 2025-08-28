<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Analytics') }}
        </h2>
    </x-slot>

    @php
        $lifetime = $lifetime ?? ['workouts' => 0, 'hours' => 0, 'lifted' => 0];
        $weekly   = $weekly   ?? ['workouts' => 0, 'hours' => 0, 'lifted' => 0];
        $muscleData = $muscleData ?? null; 
        $scope = $scope ?? 'lifetime';
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- LEFT COLUMN --}}
                <div class="space-y-6">
                    {{-- Lifetime Record --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Lifetime Record</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Workouts</div>
                                    <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-50">
                                        {{ number_format($lifetime['workouts']) }}
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Hours</div>
                                    <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-50">
                                        {{ number_format($lifetime['hours'], 1) }}
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Lifted</div>
                                    <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-50">
                                        {{ number_format($lifetime['lifted']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Weekly Record --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Weekly Record</h3>
                                <span class="text-xs text-gray-500 dark:text-gray-400">This week</span>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Workouts</div>
                                    <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-50">
                                        {{ number_format($weekly['workouts']) }}
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Hours</div>
                                    <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-50">
                                        {{ number_format($weekly['hours'], 1) }}
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Lifted</div>
                                    <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-50">
                                        {{ number_format($weekly['lifted']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg max-w-[580px]">
                        <div class="p-6 pb-25">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Muscle Tracker</h3>
                                <form method="GET" action="{{ url()->current() }}">
                                    <label class="sr-only" for="scope">Scope</label>
                                    <select id="scope" name="scope"
                                            class="w-28 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm rounded-md pl-4 y-1 cursor-pointer hover:border-indigo-400 hover:shadow-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                                            onchange="this.form.submit()">
                                        <option value="recent" @selected($scope === 'recent')>Recent</option>
                                        <option value="lifetime" @selected($scope === 'lifetime')>Lifetime</option>
                                    </select>
                                </form>
                            </div>

                            {{-- Pie Chart / No Data --}}
                            @if($muscleData && count($muscleData))
                                <div class="flex justify-center">
                                    <canvas id="musclePie" class="w-72 h-72"></canvas>
                                </div>
                                <div id="muscleLegend" class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-2 pt-10"></div>
                            @else
                                <div class="text-center text-gray-500 dark:text-gray-400 mt-12">
                                    Not enough workout data
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Chart.js CDN --}}
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                @if($muscleData && count($muscleData))
                <script>
                    (function () {
                        const rawData = @json($muscleData);
                        const labels = Object.keys(rawData);
                        const data = Object.values(rawData);

                        const palette = ['#312e81','#3730a3','#4338ca','#4f46e5','#6366f1','#818cf8','#a5b4fc','#c7d2fe'];
                        const colors = labels.map((_, i) => palette[i % palette.length]);

                        const ctx = document.getElementById('musclePie').getContext('2d');
                        new Chart(ctx, {
                            type: 'pie',
                            data: { labels, datasets: [{ data, backgroundColor: colors, borderColor: 'transparent' }] },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return `${context.label}: ${context.parsed}%`;
                                            }
                                        }
                                    }
                                }
                            }
                        });

                        const legend = document.getElementById('muscleLegend');
                        legend.innerHTML = labels.map((label, i) => `
                            <div class="flex items-center space-x-2">
                                <span class="inline-block w-3 h-3 rounded" style="background:${colors[i]}"></span>
                                <span class="text-sm text-gray-700 dark:text-gray-200">${label}</span>
                                <span class="ml-auto text-sm text-gray-500 dark:text-gray-400">${data[i]}%</span>
                            </div>
                        `).join('');
                    })();
                </script>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
