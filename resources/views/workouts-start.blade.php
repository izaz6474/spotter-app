<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">
            Start Workout
        </h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 space-y-6">

            {{-- Workout Title + Buttons --}}
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                    {{ $workout->name }}
                </h3>

                <div class="flex space-x-3">
                    {{-- Discard Button --}}
                    <form method="POST" action="{{ route('workout-sessions.discard') }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 rounded-lg border border-gray-500 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            Discard
                        </button>
                    </form>

                    {{-- Finish Button --}}
                    <form method="POST" action="{{ route('workout-sessions.finish') }}" id="finishForm">
                        @csrf
                        {{-- Hidden input to store current timer --}}
                        <input type="hidden" name="elapsed_time" id="elapsedTime" value="{{ gmdate('i:s', $activeSession->getDuration() ?? 0) }}">
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition">
                            Finish
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex justify-between items-start mb-4">
                {{-- Left: Note + No. of exercises --}}
                <div class="space-y-1">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Note: {{ $workout->note }}
                    </p>

                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        No. of exercises: {{ $workout->exercises->count() }}
                    </p>
                </div>

                {{-- Right: Timer + Pause/Resume --}}
                <div class="flex flex-col items-center">
                    <div class="flex items-center space-x-4">
                        {{-- Timer --}}
                        <div id="timerDisplay" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ gmdate('i:s', $activeSession->getDuration() ?? 0) }}
                        </div>

                        <form id="pauseResumeForm" method="POST" 
                            action="{{ $activeSession->isPaused() ? route('workout-sessions.resume') : route('workout-sessions.pause') }}">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-gray-700 text-white hover:bg-gray-600 transition">
                                {{ $activeSession->isPaused() ? 'Resume' : 'Pause' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Exercises --}}
            @if($workout->exercises->count())
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200">Exercises</h4>
                    <div class="max-h-96 overflow-y-auto pr-2 space-y-6">
                        @foreach($workout->exercises as $index => $exercise)
                            <div class="bg-gray-900 rounded-lg shadow-sm p-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-semibold text-indigo-400">
                                        {{ $index + 1 }}. {{ $exercise->name }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        {{ $exercise->setsForWorkout($workout->id)->count() }} sets
                                    </span>
                                </div>

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

    {{-- Timer Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let duration = {{ $activeSession->getDuration() ?? 0 }};
            let isPaused = {{ $activeSession->isPaused() ? 'true' : 'false' }};
            const timerDisplay = document.getElementById('timerDisplay');
            const elapsedInput = document.getElementById('elapsedTime');
            let lastUpdate = Date.now();

            function formatTime(seconds) {
                const mins = Math.floor(seconds / 60);
                const secs = seconds % 60;
                return `${String(mins).padStart(2,'0')}:${String(secs).padStart(2,'0')}`;
            }

            // Update timer every second and update hidden input
            setInterval(() => {
                const now = Date.now();
                const diffSeconds = Math.floor((now - lastUpdate) / 1000);

                if (!isPaused && diffSeconds > 0) {
                    duration += diffSeconds;
                    lastUpdate = now;
                    const formatted = formatTime(duration);
                    timerDisplay.textContent = formatted;
                    elapsedInput.value = formatted; // update hidden input
                } else if (isPaused) {
                    lastUpdate = now; // reset while paused
                }
            }, 1000);

            // Handle pause/resume toggle visually
            const pauseResumeForm = document.getElementById('pauseResumeForm');
            pauseResumeForm.addEventListener('submit', (e) => {
                isPaused = !isPaused;
            });
        });
    </script>

    {{-- Redirect if active session exists for a different workout --}}
    @if($activeSession && $activeSession->getID() != $workout->id)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if(confirm("⚠ You already have an active workout session!\nDo you want to switch to it?")) {
                    window.location.href = "{{ route('workout-sessions.start', $activeSession->getID()) }}";
                }
            });
        </script>
    @endif
</x-app-layout>
