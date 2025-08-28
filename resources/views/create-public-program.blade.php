<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Public Overview') }}
        </h2>
    </x-slot>

    <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl px-8 pt-3 pb-8 my-10">
        

        <form action="{{ route('programs.storeAdditional') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Goal -->
            <div>
                <label for="goal" class="block text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Goal
                </label>
                <input
                    type="text"
                    id="goal"
                    name="goal"
                    required
                    value="{{ old('goal', $programData['goal'] ?? '') }}"
                    autocomplete="off"
                    class="mt-2 block w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 
                        shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-base 
                        dark:bg-gray-700 dark:text-gray-100 p-3 hover:border-gray-200 transition-colors duration-200"
                    placeholder="e.g., Build strength, Lose weight, Improve endurance"
                >
            </div>

            <!-- Difficulty -->
            <div>
                <label for="difficulty" class="block text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Difficulty
                </label>
                <select
                    id="difficulty"
                    name="difficulty"
                    required
                    class="mt-2 block w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 
                        shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-base 
                        dark:bg-gray-700 dark:text-gray-100 p-3 hover:border-gray-200 transition-colors duration-200"
                >
                    <option value="">Select difficulty</option>
                    <option value="novice" {{ old('difficulty', $programData['difficulty'] ?? '') === 'novice' ? 'selected' : '' }}>Novice</option>
                    <option value="intermediate" {{ old('difficulty', $programData['difficulty'] ?? '') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="elite" {{ old('difficulty', $programData['difficulty'] ?? '') === 'elite' ? 'selected' : '' }}>Elite</option>
                </select>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Description
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="2"
                    required
                    class="mt-2 block w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 
                        shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-base 
                        dark:bg-gray-700 dark:text-gray-100 p-3 hover:border-gray-200 transition-colors duration-200"
                    placeholder="Describe your program in detail..."
                >{{ old('description', $programData['description'] ?? '') }}</textarea>
            </div>

            <!-- Average Workout Duration -->
            <div>
                <label for="average_mins" class="block text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Average Workout Duration (minutes)
                </label>
                <input
                    type="number"
                    id="average_mins"
                    name="average_mins"
                    min="1"
                    value="{{ old('average_mins', $programData['average_mins'] ?? '') }}"
                    required
                    class="mt-2 block w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 
                        shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-base 
                        dark:bg-gray-700 dark:text-gray-100 p-3 hover:border-gray-200 transition-colors duration-200"
                    placeholder="e.g., 45"
                >
            </div>

            <!-- Buttons -->
            <div class="flex justify-between pt-4">
                <a
                    href="{{ route('program-controller.create') }}"
                    class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                        text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                    Go Back
                </a>
                <button
                    type="submit"
                    class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-semibold 
                        hover:bg-indigo-700 focus:outline-none focus:ring-2 
                        focus:ring-indigo-500"
                >
                    Next
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
