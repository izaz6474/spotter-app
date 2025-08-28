<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Training History') }}
        </h2>
    </x-slot>

    <div class="py-10 max-w-5xl mx-auto sm:px-6 lg:px-8">

        {{-- Unified History Container --}}
        <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex flex-col max-h-[580px]">

            {{-- Header: Title + Month Picker --}}
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                    Workouts in {{ \Carbon\Carbon::parse($selectedMonth ?? now())->format('F Y') }}
                </h3>

                <form method="GET" action="{{ route('history') }}" class="flex items-center gap-2">
                    <input 
                        type="month" 
                        name="month" 
                        value="{{ $selectedMonth ?? now()->format('Y-m') }}"
                        class="w-11 px-3 py-1.5 mr-2 rounded-lg dark:bg-gray-200 hover:bg-gray-100 dark:text-indigo-900 hover:text-indigo-600 text-sm"
                        onchange="this.form.submit()"
                    />
                </form>
            </div>

            {{-- Scrollable Cards Section --}}
            <div class="flex-1 overflow-y-auto space-y-5 pr-1 custom-scrollbar">

                @forelse($records as $index => $record)
                    <div class="bg-gray-900 dark:bg-gray-900 rounded-xl shadow-md hover:shadow-lg transition p-5">

                        {{-- Workout Title & Program Info --}}
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-100 dark:text-white">
                                {{ $record['workout']->name }}
                                @if($record['workout']->inProgram)
                                    @php
                                        $programWorkout = DB::table('program_workout')
                                            ->where('workout_id', $record['workout']->id)
                                            ->first();
                                        $program = DB::table('programs')
                                            ->where('id', $programWorkout->program_id)
                                            ->first();
                                    @endphp
                                    <span class="text-lg font-bold text-gray-100 dark:text-white">
                                         | {{ $program->name }} | Week {{ $programWorkout->week_no }} - Day {{ $programWorkout->day_no }}
                                    </span>
                                @endif
                            </h3>
                            <span class="text-gray-400 text-sm">Finished on: {{ \Carbon\Carbon::parse($record['completed_at'])->format('d M, Y H:i') }}</span>
                        </div>

                        {{-- Duration & Exercise Count --}}
                        <div class="flex justify-between text-gray-300 text-sm mb-3">
                            <span class="text-gray-400 text-m">Exercises: {{ $record['exercises']->count() }}</span>
                            <span>Duration: {{ $record['duration'] }}</span>
                        </div>

                        {{-- Show Details Toggle --}}
                        <button 
                            onclick="
                                const details = document.getElementById('details-{{ $index }}');
                                details.classList.toggle('hidden');
                                this.innerText = details.classList.contains('hidden') ? 'Show Details' : 'Hide Details';
                            " 
                            class="text-indigo-500 dark:text-indigo-400 text-sm font-medium underline mb-3 hover:text-indigo-600 transition"
                        >
                            Show Details
                        </button>

                        {{-- Exercise Details Table --}}
                        <div id="details-{{ $index }}" class="hidden overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead>
            <tr>
                <th class="py-1 px-2 text-left text-gray-400 font-medium">Exercise</th>
                <th class="py-1 px-2 text-center text-gray-400 font-medium">Sets</th>
                <th class="py-1 px-2 text-center text-gray-400 font-medium">Top Set</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record['exercises'] as $exercise)
                <tr>
                    <td class="py-1 px-2 text-left text-gray-100">{{ $exercise->name }}</td>
                    <td class="py-1 px-2 text-center text-gray-100">{{ $exercise->sets }}</td>
                    <td class="py-1 px-2 text-center text-gray-100">{{ $exercise->top_set }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

                    </div>
                @empty
                    <p class="text-gray-700 dark:text-gray-300 text-center py-10">
                        No workouts found for this month.
                    </p>
                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>
