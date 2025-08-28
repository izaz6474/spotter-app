<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Explore Our Community') }}
        </h2>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Parent Flex Container --}}
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Left Section --}}
            <div class="flex-1 bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6">
                <div class="space-y-6">

                    {{-- Search + Tabs + Filter --}}
                    <form action="{{ route('home.submit') }}" method="POST" class="space-y-4">
                        @csrf

                        {{-- Hidden inputs --}}
                        <input type="hidden" name="selectedTab" id="SelectedTab" value="{{ $selectedTab ?? 1 }}">
                        <input type="hidden" name="selectedFilter" id="SelectedFilter" value="{{ $selectedFilter ?? 1 }}">
                        
                        {{-- Search Bar --}}
                        <div>
                            <input type="text" name="searchText" placeholder="Search programs..."
                                value="{{ $searchText ?? '' }}"
                                class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 
                                    bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 
                                    focus:ring-2 focus:ring-indigo-500 focus:outline-none 
                                    placeholder-gray-400 dark:placeholder-gray-500
                                    hover:border-indigo-400 hover:bg-white dark:hover:bg-gray-800 
                                    transition duration-200 ease-in-out"
                                onkeydown="if(event.key === 'Enter'){ 
                                    event.preventDefault(); 
                                    document.getElementById('SelectedFilter').value = 3; 
                                    this.form.submit(); 
                                }">
                        </div>


                        {{-- Tabs & Conditional Filter --}}
                        <div class="mt-4">
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-2">
                                <div class="flex gap-4">
                                    {{-- Tabs --}}
                                    <button type="submit" 
                                        onclick="document.getElementById('SelectedTab').value = 1;"
                                        class="px-3 py-2 text-sm font-medium transition {{ ($selectedTab ?? 1) == 1 
                                            ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-500' 
                                            : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                        Community
                                    </button>

                                    <button type="submit" 
                                        onclick="document.getElementById('SelectedTab').value = 2;
                                                 document.getElementById('SelectedFilter').value = 1;"
                                        class="px-3 py-2 text-sm font-medium transition {{ ($selectedTab ?? 1) == 2 
                                            ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-500' 
                                            : 'text-gray-400 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                        Your Programs
                                    </button>

                                    <button type="submit" 
                                        onclick="document.getElementById('SelectedTab').value = 3;
                                                 document.getElementById('SelectedFilter').value = 1;"
                                        class="px-3 py-2 text-sm font-medium transition {{ ($selectedTab ?? 1) == 3 
                                            ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-500' 
                                            : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                        Your Workouts
                                    </button>
                                </div>

                                {{-- Filter only for Community tab --}}
                                @if(($selectedTab ?? 1) == 1)
                                    <div>
                                        <select name="filter" 
                                            onchange="document.getElementById('SelectedFilter').value = this.value; this.form.submit();"
                                            class="w-32 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 
                                                bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 
                                                focus:ring-1 focus:ring-indigo-400 focus:border-indigo-400 
                                                focus:outline-none text-sm
                                                hover:bg-gray-100 dark:hover:bg-gray-800
                                                hover:border-gray-400 dark:hover:border-gray-600
                                                transition-colors duration-150">
                                            <option value="1" {{ ($selectedFilter ?? 1) == 1 ? 'selected' : '' }}>Sort: Popular</option>
                                            <option value="2" {{ ($selectedFilter ?? 1) == 2 ? 'selected' : '' }}>Sort: Latest</option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>

                    {{-- Tab Content --}}
                    <div class="mt-4 max-h-96 overflow-y-auto space-y-3">
                        @if(($selectedTab ?? 1) == 1)
                            {{-- Community Programs --}}
                            @forelse($communityPrograms as $program)
                                <div>
                                    <a href="{{ route('programs.show', $program->id) }}"
                                    class="block p-5 bg-white dark:bg-gray-900 rounded-2xl 
                                            border border-gray-200 dark:border-gray-700 
                                            hover:shadow-lg hover:border-indigo-400 dark:hover:border-indigo-500 
                                            transition-all duration-200">

                                        <!-- Flex container for name + difficulty -->
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-100">
                                                {{ $program->name }}
                                            </h4>

                                            @if($program->is_public)
                                                <!-- Difficulty Badge -->
                                                @php
                                                    $difficultyColors = [
                                                        'novice' => 'text-green-700 dark:text-green-300',
                                                        'intermediate' => 'text-yellow-700 dark:text-yellow-300',
                                                        'advanced' => 'text-red-700 dark:text-red-300',
                                                    ];
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                                            {{ $difficultyColors[strtolower($program->difficulty)] ?? 'text-gray-600 dark:text-gray-300' }}">
                                                    {{ ucfirst($program->difficulty) }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($program->is_public)
                                            <!-- Goal -->
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                Goal: {{ $program->goal }}
                                            </p>
                                        @endif

                                        <div class="flex justify-between items-center mt-1">
                                            <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                                Weeks: {{ $program->weeks }}
                                            </p>

                                            <span class="text-sm font-semibold mr-3 text-gray-600 dark:text-gray-400">
                                                {{ $program->users_count }} joined
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No community programs found.</p>
                            @endforelse
                        @elseif(($selectedTab ?? 1) == 2)
                            {{-- Your Programs --}}
                            @forelse($yourPrograms as $program)
                                <div>
                                    <a href="{{ route('programs.show', $program->id) }}"
                                    class="block p-5 bg-white dark:bg-gray-900 rounded-2xl 
                                            border border-gray-200 dark:border-gray-700 
                                            hover:shadow-lg hover:border-indigo-400 dark:hover:border-indigo-500 
                                            transition-all duration-200">

                                        <!-- Flex container for name + difficulty -->
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-100">
                                                {{ $program->name }}
                                            </h4>

                                            @if($program->is_public)
                                                <!-- Difficulty Badge -->
                                                @php
                                                    $difficultyColors = [
                                                        'novice' => 'text-green-700 dark:text-green-300',
                                                        'intermediate' => 'text-yellow-700 dark:text-yellow-300',
                                                        'advanced' => 'text-red-700 dark:text-red-300',
                                                    ];
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                                            {{ $difficultyColors[strtolower($program->difficulty)] ?? 'text-gray-600 dark:text-gray-300' }}">
                                                    {{ ucfirst($program->difficulty) }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($program->is_public)
                                            <!-- Goal -->
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                Goal: {{ $program->goal }}
                                            </p>
                                        @endif

                                        <!-- Weeks (always shown) -->
                                        <p class="mt-1 text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                            Weeks: {{ $program->weeks }}
                                        </p>
                                    </a>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">You have no programs yet.</p>
                            @endforelse
                        @elseif(($selectedTab ?? 1) == 3)
                            {{-- Your Workouts --}}
                            @forelse($yourWorkouts as $workout)
                                <div>
                                    <a href="{{ route('workouts.show', ['id' => $workout->id]) }}"
                                    class="block p-4 bg-gray-50 dark:bg-gray-900 rounded-xl 
                                            border border-gray-200 dark:border-gray-700 
                                            hover:shadow-md hover:border-indigo-400 dark:hover:border-indigo-500 transition">
                                        <h4 class="font-semibold text-gray-800 dark:text-gray-100">{{ $workout->name }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Created At: {{ $workout->created_at->format('d M, Y') }}
                                        </p>
                                    </a>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">You have no workouts yet.</p>
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Section --}}
            <div class="w-full lg:w-1/3 flex flex-col gap-6">
                {{-- Current Program --}}
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6">
                    <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">Your Current Program</h3>
                    @if($currentProgram)
                        <a href="{{ route('programs.show', $currentProgram->id) }}"
                                    class="block p-5 bg-white dark:bg-gray-900 rounded-2xl 
                                            border border-gray-200 dark:border-gray-700 
                                            hover:shadow-lg hover:border-indigo-400 dark:hover:border-indigo-500 
                                            transition-all duration-200">

                                        <!-- Flex container for name + difficulty -->
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-100">
                                                {{ $currentProgram->name }}
                                            </h4>

                                        </div>

                                        @if($currentProgram->is_public)
                                            <!-- Goal -->
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                Goal: {{ $currentProgram->goal }}
                                            </p>
                                        @endif

                                        <!-- Weeks (always shown) -->
                                        <p class="mt-1 text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                            Weeks: {{ $currentProgram->weeks }}
                                        </p>
                                    </a>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No current program.</p>
                    @endif
                </div>

                {{-- Top Recommended Program --}}
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6">
                    <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">Top Recommended Program</h3>
                    @if($topRecommendedProgram)
                        <a href="{{ route('programs.show', $topRecommendedProgram->id) }}"
                        class="block p-5 bg-white dark:bg-gray-900 rounded-2xl 
                                border border-gray-200 dark:border-gray-700 
                                hover:shadow-lg hover:border-indigo-400 dark:hover:border-indigo-500 
                                transition-all duration-200">

                            <!-- Flex container for name + difficulty -->
                            <div class="flex justify-between items-start">
                                <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-100">
                                    {{ $topRecommendedProgram->name }}
                                </h4>

                                @if($topRecommendedProgram->is_public)
                                    <!-- Difficulty Badge -->
                                    @php
                                        $difficultyColors = [
                                            'novice' => 'text-green-700 dark:text-green-300',
                                            'intermediate' => 'text-yellow-700 dark:text-yellow-300',
                                            'advanced' => 'text-red-700 dark:text-red-300',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                                                {{ $difficultyColors[strtolower($topRecommendedProgram->difficulty)] ?? 'text-gray-600 dark:text-gray-300' }}">
                                        {{ ucfirst($topRecommendedProgram->difficulty) }}
                                    </span>
                                @endif
                            </div>

                            @if($topRecommendedProgram->is_public)
                                <!-- Goal -->
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    Goal: {{ $topRecommendedProgram->goal }}
                                </p>
                            @endif

                            <!-- Weeks (always shown) -->
                            <p class="mt-1 text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                Weeks: {{ $topRecommendedProgram->weeks }}
                            </p>
                        </a>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No recommended program.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
