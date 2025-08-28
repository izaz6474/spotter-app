<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Continue Your Training') }}
        </h2>
    </x-slot>

    {@if($programName)
        {{-- First Section - Program Info Card --}}
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 rounded-2xl my-6">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 flex justify-between items-start">
                        
                        {{-- Left Side - Program Details --}}
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ $programName }}</h3>
                            <p class="text-m text-gray-400 my-1">
                                Week {{ $week ?? '-' }} • Day {{ $day ?? '-' }}  |  {{ $workout->name ?? '-' }}
                            </p>
                        </div>

                        {{-- Middle - Progress Bar --}}
                        {{-- Right Side - Options Menu --}}
                        <div class="relative inline-block text-left">
                            <button id="optionsBtn" type="button" aria-haspopup="true" aria-expanded="false"
                                class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none"
                                onclick="toggleDropdown(event)">
                                <!-- Horizontal dots icon -->
                                <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="6" cy="12" r="1.5" />
                                    <circle cx="12" cy="12" r="1.5" />
                                    <circle cx="18" cy="12" r="1.5" />
                                </svg>
                            </button>

                            <div id="optionsMenu" class="hidden absolute right-0 mt-2 w-44 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md shadow-lg z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    {{ __('Restart Program') }}
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    {{ __('Leave Program') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Table Section --}}
                    <div class="px-6 py-4">
                        <div class="max-h-36 overflow-y-auto custom-scrollbar">
                            <table class="min-w-full text-left text-sm">
                                <thead class="sticky top-0 bg-white dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400">Exercise</th>
                                        <th class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400">Sets</th>
                                        <th class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400">Top Set</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exercises ?? [] as $exercise)
                                        <tr>
                                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $exercise->name }}</td>
                                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $exercise->sets }}</td>
                                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $exercise->top_set }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Bottom Button --}}
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('workout-sessions.start', $workout->id ?? 0) }}">
                            @csrf
                            <input type="hidden" name="train" value="1">
                            <button type="submit" 
                                    class="bg-indigo-700 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded-l w-full">
                                Start Workout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- No ongoing program message --}}
        <div class="py-10 max-w-5xl mx-auto sm:px-6 lg:px-8 text-center">
            <div class="bg-gray-100 dark:bg-gray-800 shadow-lg sm:rounded-lg p-6">
                <p class="text-gray-500 dark:text-gray-400 mt-2">You currently have no active program. Join a program or Create a new program to begin training.</p>
            </div>
        </div>
    @endif


    {{-- Second Section - Start Empty Workout Button --}}
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('workout-controller.create', ['inProgram' => false]) }}">
                <button
                    class="w-full py-4 px-4 rounded-lg border-2 border-dashed border-gray-500 text-gray-500 font-semibold
                        bg-transparent
                        hover:border-indigo-600 hover:text-indigo-600
                        transition-colors duration-200"
                >
                    Start Empty Workout
                </button>
        </div>
    </div>


    {{-- Third Section - Create Program Button --}}
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 rounded-2xl">
            <a href="{{ route('program-controller.create') }}">
                <button
                    class="w-full py-4 px-4 rounded-lg border-2 border-dashed border-gray-500 text-gray-500 font-semibold
                        bg-transparent
                        hover:border-indigo-600 hover:text-indigo-600
                        transition-colors duration-200"
                >
                    Create New Program
                </button>
        </div>
    </div>

    <script>
        function toggleDropdown(event) {
            event.stopPropagation();
            const menu = document.getElementById('optionsMenu');
            const btn = document.getElementById('optionsBtn');
            const isHidden = menu.classList.contains('hidden');

            if (isHidden) {
                menu.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
            } else {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            }
        }
        
    </script>

</x-app-layout>
