<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Workouts to Program') }}
        </h2>
    </x-slot>

    @php
        $weeks = $programData['weeks'] ?? 1;
        $is_public = $programData['is_public'] ?? false;
    @endphp

    <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 my-10">

        <!-- Program Summary -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    {{ $programData['name'] ?? 'Program Name' }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ $weeks }} week{{ $weeks > 1 ? 's' : '' }} program
                </p>
            </div>

            <!-- Save Button -->
            <a href="{{ route('program.Save') }}"
                class="my-4 px-6 py-2 rounded-lg bg-indigo-600 text-white font-semibold 
                hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Save & Continue
            </a>
        </div>

        <!-- Tabs -->
        <div x-data="{ tab: {{ request('week', 1) }} }">
            <div class="flex space-x-2 border-b border-gray-300 dark:border-gray-700 mb-4">
                @for ($i = 1; $i <= $weeks; $i++)
                    <button
                        class="px-4 py-2 rounded-t-lg text-sm font-semibold focus:outline-none"
                        :class="tab === {{ $i }} 
                            ? 'bg-indigo-600 text-white' 
                            : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'"
                        @click="tab = {{ $i }}"
                    >
                        Week {{ $i }}
                    </button>
                @endfor
            </div>

            <!-- Week Content -->
            @for ($i = 1; $i <= $weeks; $i++)
                <div x-show="tab === {{ $i }}">

                    <!-- Workout Preview (scrollable list) -->
                    <div class="max-h-64 overflow-y-auto custom-scrollbar mb-4 pr-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($workouts[$i] ?? [] as $workout)
                                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4">

                                    <div class="flex justify-between items-center">
                                        <div>
                                            <!-- Week + Day Highlight -->
                                            <span class="text-gray-500 dark:text-gray-400 text-xl font-semibold">
                                                Week {{ $i }} · Day {{ $loop->iteration }}
                                            </span>
                                        </div>

                                        <!-- Delete Button -->
                                        <form 
                                            method="POST" 
                                            action="{{ route('program.removeWorkout') }}" 
                                            class="inline-block ml-2"
                                        >
                                            @csrf
                                            <input type="hidden" name="week" value="{{ $i }}">
                                            <input type="hidden" name="index" value="{{ $loop->index }}">

                                            <button
                                                type="submit"
                                                title="Drop Workout"
                                                class="px-3 py-1 text-gray-400 hover:text-red-700 bg-transparent transition-colors duration-200 text-sm font-medium"
                                            >
                                                Drop
                                            </button>
                                        </form>
                                    </div>


                                    <!-- Workout Name -->
                                    <h4 class="font-semibold text-gray-800 dark:text-gray-200 mt-2">
                                        {{ $workout->getName() ?? 'Untitled Workout' }}
                                    </h4>

                                    <!-- Workout Note -->
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                                        {{ $workout->getNote() ?? 'No notes' }}
                                    </p>

                                    <!-- Exercises count -->
                                    <p class="text-gray-500 dark:text-gray-300 text-sm mt-2">
                                        Exercises: {{ count($workout->getExercises()) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <!-- Add Workout Button -->
                    <div class="py-6">
                        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                            <a href="{{ route('workout-controller.create', ['week' => $i, 'inProgram' => true]) }}">
                                <button
                                    class="w-full py-2 px-4 rounded-lg border-2 border-dashed border-gray-500 text-gray-600 font-semibold
                                        bg-transparent
                                        hover:border-indigo-600 hover:text-indigo-600
                                        transition-colors duration-200"
                                >
                                    + Add Workout Day
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</x-app-layout>
