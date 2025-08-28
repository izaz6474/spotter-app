<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">
            View Program
        </h2>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Left Div: Program Overview --}}
            <div class="lg:w-1/3 bg-gray-700 dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900 p-6 flex flex-col justify-between h-[580px]">

                {{-- Content --}}
                <div class="space-y-4">
                    
                    <h1 class="text-2xl font-bold text-white truncate">{{ $program->name }}</h1>

                    <p class="text-sm text-gray-300">Created by {{ $program->user->name }}</p>

                    <p class="text-gray-200 max-h-80 overflow-y-auto pr-2">
                        {{ $program->description ?? 'No description' }}
                    </p>

                    @if($program->is_public)
                        <div class="flex flex-col space-y-2 mt-4 bg-gray-800 dark:bg-gray-700 p-3 rounded-xl">
                            <p class="text-gray-200 text-sm"><span class="font-semibold">Goal:</span> {{ $program->goal ?? 'N/A' }}</p>

                            <p class="text-sm text-gray-200">
                                <span class="font-semibold">Difficulty:</span>
                                @php
                                    $difficultyColors = [
                                        'novice' => 'text-green-400',
                                        'intermediate' => 'text-yellow-400',
                                        'advanced' => 'text-red-400',
                                    ];
                                @endphp
                                <span class="{{ $difficultyColors[strtolower($program->difficulty)] ?? 'text-gray-400' }}">
                                    {{ ucfirst($program->difficulty ?? 'N/A') }}
                                </span>
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-4 mt-6">
                    {{-- Back/Home Button --}}
                    <a href="{{ route('home') }}"
                    class="flex-1 flex items-center justify-center px-3 py-3 rounded-l border border-gray-500
                            text-gray-500 font-semibold hover:bg-gray-700 hover:text-gray-100 transition-all duration-200">
                        Home
                    </a>

                    {{-- Join Button --}}
                    <form method="POST" action="{{ route('program.join') }}" class="flex-[1.5]">
                        @csrf
                        <input type="hidden" name="program_id" value="{{ $program->id }}">
                        <button type="submit"
                            class="w-full flex items-center justify-center px-5 py-3 rounded-l border border-indigo-600
                                text-gray-100 bg-indigo-600 hover:bg-indigo-500 hover:border-indigo-500 font-semibold transition-all duration-200">
                            Join
                        </button>
                    </form>
                </div>
            </div>


            {{-- Right Div: Workout Cards --}}
            <div class="lg:w-2/3 flex flex-col bg-gray-800 dark:bg-gray-800 p-6 rounded-2xl h-[580px]">
                
                {{-- Container Title --}}
                <h2 class="text-2xl font-semibold text-gray-200 dark:text-white mb-4">Workouts</h2>

                {{-- Scrollable Cards Container --}}
                <div class="flex flex-col gap-4 overflow-y-auto h-full">
                    @foreach($workoutPivot as $item)
                        @php
                            $workout = \App\Models\Workout::find($item->workout_id);
                            $exerciseCount = $workout ? $workout->exercises()->count() : 0;
                            $highlightWeekDay = $item->week_no == 1 && $item->day_no == 1;
                        @endphp
                        @if($workout)
                            <a href="{{ route('workouts.show', ['id' => $workout->id, 'from' => $program->id]) }}">
                                <div class="bg-gray-900 dark:bg-gray-900 border border-gray-800 hover:border-indigo-500 rounded-xl p-5 shadow-md transition">
                                    
                                    {{-- Week & Day --}}
                                    <div class="text-sm font-semibold mb-1 text-indigo-400">
                                        Week {{ $item->week_no }} - Day {{ $item->day_no }}
                                    </div>

                                    {{-- Workout Name --}}
                                    <h3 class="text-gray-100 font-bold text-xl mb-1">{{ $workout->name }}</h3>

                                    {{-- Exercise Count --}}
                                    <p class="text-gray-400 text-sm">
                                        {{ $exerciseCount }} Exercise{{ $exerciseCount > 1 ? 's' : '' }}
                                    </p>

                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
