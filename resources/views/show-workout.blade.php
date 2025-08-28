<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">
            View Workout
        </h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 space-y-6">

            {{-- Workout Title + Buttons --}}
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                    {{ $workout->name }}
                </h3>

                <div class="flex space-x-3 items-stretch">
                    {{-- Go Back Button --}}
                    <a href="{{ request()->has('from') ? route('programs.show', $from) : route('home') }}"
                    class="flex-1 flex items-center justify-center px-4 py-2 rounded-lg border border-gray-500 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Go Back
                    </a>

                    @if (!isset($from))
                        <form method="POST" action="{{ route('workout-sessions.start', $workout->id) }}" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition">
                                Start
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="space-y-1">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Note: {{ $workout->note }}
                </p>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    No. of exercises: {{ $workout->exercises->count() }}
                </p>
            </div>
            
            {{-- Exercises --}}
            @if($workout->exercises && $workout->exercises->count())
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200">Exercises</h4>

                    {{-- Scrollable container --}}
                    <div class="max-h-96 overflow-y-auto pr-2 space-y-6">
                        @foreach($workout->exercises as $index => $exercise)
                            <div class="bg-gray-900 rounded-lg shadow-sm p-4 space-y-3">
                                {{-- Exercise Header --}}
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-semibold text-indigo-400">
                                        {{ $index + 1 }}. {{ $exercise->name }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        {{ $exercise->setsForWorkout($workout->id)->count() }} sets
                                    </span>
                                </div>

                                {{-- Sets Table --}}
                                @php
                                    $sets = $exercise->setsForWorkout($workout->id);
                                @endphp

                                @if($sets->count())
                                    <table class="w-full text-gray-300 text-sm border-collapse">
                                        <thead>
                                            <tr class="text-gray-400 text-xs uppercase tracking-wide">
                                                <th class="py-2 px-4 text-center">Set</th>
                                                <th class="py-2 px-4 text-center">kg</th>
                                                <th class="py-2 px-4 text-center">Reps</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sets as $set)
                                                <tr class="">
                                                    <td class="py-2 px-4 text-center">{{ $set->set_index }}</td>
                                                    <td class="py-2 px-4 text-center">{{ $set->weight }}</td>
                                                    <td class="py-2 px-4 text-center">{{ $set->no_of_reps }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-gray-500 text-sm">No sets logged yet.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="mt-4 text-gray-500 dark:text-gray-400">No exercises added yet.</p>
            @endif

        </div>
    </div>
</x-app-layout>
