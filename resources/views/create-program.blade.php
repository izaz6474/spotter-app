<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Program') }}
        </h2>
    </x-slot>

    <div class="py-10 max-w-lg mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 my-10">

        <form action="{{ route('programs.store') }}" method="POST" class="space-y-6">
            @csrf
            <!-- Program Name -->
            <div>
                <label for="name" class="block text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Program Name
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $programData['name'] ?? '') }}"
                    required
                    autocomplete="off"
                    class="mt-2 block w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 
                        shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-base 
                        dark:bg-gray-700 dark:text-gray-100 p-3 hover:border-gray-200 transition-colors duration-200"
                    placeholder="e.g., 12-Week Strength Plan"
                >
            </div>

            <!-- Number of Weeks -->
            <div>
                <label for="weeks" class="block text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Number of Weeks
                </label>
                <input
                    type="number"
                    id="weeks"
                    name="weeks"
                    min="1"
                    max="12"
                    value="{{ old('weeks', $programData['weeks'] ?? '') }}"
                    autocomplete="off"
                    required
                    class="mt-2 block w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 
                        shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-base 
                        dark:bg-gray-700 dark:text-gray-100 p-3 hover:border-gray-200 transition-colors duration-200"
                    placeholder="1 - 12"
                >
            </div>

            <!-- Public Checkbox -->
            <div class="flex items-center">
                <label class="flex items-center space-x-2 cursor-pointer group">
                    <input 
                        type="checkbox" 
                        name="is_public"
                        value="1"
                        {{ old('is_public', $programData['is_public'] ?? false) ? 'checked' : '' }}
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded 
                            focus:ring-indigo-500 focus:outline-none focus:ring-0
                             dark:bg-gray-700 dark:border-gray-600
                            group-hover:border-gray-100 group-transition-colors duration-200"
                    />
                    <span class="text-gray-700 dark:text-gray-300 group-hover:text-gray-100 group-transition-colors duration-200">
                        visible for community
                    </span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between pt-4">
                <a
                    href="{{ route('train') }}"
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