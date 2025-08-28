<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Workout') }}
        </h2>
    </x-slot>

    @php
        // Get from URL (passed via route in Add Workout view)
        $week = request()->get('week', 1); // default to 1 if not present
        $inProgram = filter_var(request()->get('inProgram', false), FILTER_VALIDATE_BOOLEAN);
    @endphp

    <form
        action="{{ route('workouts.store') }}"
        method="POST"
        x-data="workoutForm()"
        @submit.prevent="submitWorkout"
        class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 my-10 space-y-4"
    >
        @csrf

        <!-- Hidden inputs to send with the form -->
        <input type="hidden" name="week" value="{{ $week }}">
        <input type="hidden" name="inProgram" value="{{ $inProgram ? '1' : '0' }}">

        <!-- Workout Name -->
        <div>
            <label class="block text-xl font-bold text-gray-700 dark:text-gray-300">
                <input type="text" name="name" value="{{ now()->format('M d') }} Workout"
                    class="block w-full rounded-md dark:bg-gray-800 dark:text-gray-100 p-1 text-lg
                    border border-gray-800"
                >
            </label>
        </div>

        <!-- Notes -->
        <div>
            <textarea
                name="note"
                rows="1"
                placeholder="Optional Notes..."
                class="block w-full rounded-md dark:bg-gray-700 dark:text-gray-100 p-1 text-xs resize-none
                border border-gray-600 hover:border-gray-400 focus:border-indigo-500 focus:ring-1
                focus:ring-indigo-500 transition-colors duration-200"
            ></textarea>
        </div>

        <!-- Exercises Container -->
        <div class="max-h-72 overflow-y-auto custom-scrollbar space-y-4">
            <template x-for="(exercise, index) in exercises" :key="exercise._localId">
                <div class="bg-gray-800 rounded-lg p-3 shadow-sm text-gray-300">

                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-base font-semibold text-indigo-500 flex items-center">
                            <span x-text="index + 1"></span>

                            <!-- Exercise Selection Button -->
                            <button
                                type="button"
                                @click="openExerciseModal(exercise)"
                                class="ml-2 px-2 py-1 text-gray-300 rounded-md text-l bg-transparent hover:border-indigo-500 hover:text-indigo-500 transition"
                                x-text="exercise.name || 'Select Exercise'"
                            ></button>
                        </h3>

                        <!-- Discard Exercise -->
                        <button
                            @click="exercises.splice(index, 1)"
                            type="button"
                            title="Discard Exercise"
                            class="px-3 py-1 text-gray-400 border border-gray-400 rounded hover:text-red-700 hover:border-red-700 bg-transparent transition-colors duration-200 text-sm font-medium"
                        >
                            Discard
                        </button>
                    </div>

                    <!-- Hidden Input: send exercise ID to backend -->
                    <input type="hidden" :name="'exercises['+index+'][id]'" :value="exercise.id">

                    <!-- Sets Table -->
                    <table class="w-full text-gray-300 text-sm border-collapse">
                        <thead>
                            <tr>
                                <th class="py-1 px-4 text-center">Set</th>
                                <th class="py-1 px-4 text-center">kg</th>
                                <th class="py-1 px-4 text-center">Reps</th>
                                <th class="w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(set, setIndex) in exercise.sets" :key="set.id">
                                <tr class="border-b border-gray-800">
                                    <td class="py-1 px-4 text-center" x-text="setIndex + 1"></td>
                                    <td class="py-1 px-4 text-center">
                                        <input type="number" min="0" step="0.1" x-model="set.kg"
                                            :name="'exercises['+index+'][sets]['+setIndex+'][kg]'"
                                            class="bg-transparent rounded-md px- py-1 w-14 text-gray-300 text-center 
                                            border border-gray-600 hover:border-gray-400 focus:border-indigo-500 focus:ring-1
                                            focus:ring-indigo-500 transition-colors duration-200" />
                                    </td>
                                    <td class="py-1 px-4 text-center">
                                        <input type="number" min="0" x-model="set.reps"
                                            :name="'exercises['+index+'][sets]['+setIndex+'][reps]'"
                                            class="bg-transparent rounded-md px-2 py-1 w-14 text-gray-300 text-center
                                            border border-gray-600 hover:border-gray-400 focus:border-indigo-500 focus:ring-1
                                            focus:ring-indigo-500 transition-colors duration-200" />
                                    </td>
                                    <td class="text-center">
                                        <button
                                            @click.prevent="removeSet(exercise, set.id)"
                                            class="group inline-flex items-center justify-center p-1 rounded hover:text-red-600"
                                            type="button"
                                            title="Remove Set"
                                            x-show="exercise.sets.length > 1"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-500 group-hover:text-red-700 transition-colors duration-200"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7L5 7M10 11v6M14 11v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <!-- Add Set Button -->
                    <button
                        @click.prevent="addSet(exercise)"
                        class="w-full mt-2 bg-gray-700 hover:bg-gray-600 text-gray-200 text-sm font-medium py-1 rounded-md transition"
                        type="button"
                    >
                        + Add Set
                    </button>
                </div>
            </template>
        </div>

        <!-- Add Exercise Button -->
        <button
            @click.prevent="addExercise()"
            type="button"
            class="w-full py-3 px-3 rounded-lg border-2 border-dashed border-gray-500 text-gray-600 font-semibold
                bg-transparent hover:border-indigo-600 hover:text-indigo-600 transition-colors duration-200"
        >
            + Add Exercise
        </button>

        <!-- Action Buttons -->
        <div class="flex justify-between pt-4">
            <a
                href="{{ $inProgram 
                    ? route('programs.addWorkout', ['week' => $week]) 
                    : route('train') }}"
                class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                    text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                Go Back
            </a>
            <button type="submit"
                class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-semibold 
                    hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Save Workout
            </button>
        </div>

        <!-- Modal -->
        <div x-show="showExerciseModal" style="display: none"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            @click.self="closeExerciseModal">
            <div class="bg-gray-800 rounded-lg shadow-lg p-4 w-96">
                <input type="text" placeholder="Search exercises..."
                    x-model="exerciseSearch"
                    class="w-full mb-3 px-3 py-2 rounded-md bg-gray-700 text-white text-sm 
                    border border-gray-600 hover:border-gray-400 focus:border-indigo-500 focus:ring-1
                    focus:ring-indigo-500 transition-colors duration-200" />

                <div class="max-h-60 overflow-y-auto custom-scrollbar">
                    <template x-for="exercise in filteredExercises" :key="exercise.id">
                        <button
                            type="button"
                            @click="selectExercise(exercise)"
                            class="block w-full text-left px-3 py-2 rounded hover:bg-gray-700 text-gray-300 text-sm"
                            x-text="exercise.name">
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </form>

    <script>
        function workoutForm() {
            return {
                exercises: [],
                allExercises: [],
                selectedExerciseFor: null,
                showExerciseModal: false,
                exerciseSearch: '',
                nextLocalId: 1, 

                async fetchExercises() {
                    const params = new URLSearchParams();
                    if (this.exerciseSearch) {
                        params.append('search', this.exerciseSearch);
                    }
                    const res = await fetch('/exercises?' + params.toString());
                    this.allExercises = await res.json();
                },

                get filteredExercises() {
                    return this.allExercises.filter(e =>
                        e.name.toLowerCase().includes(this.exerciseSearch.toLowerCase())
                    );
                },

                addExercise() {
                    this.exercises.push({
                        _localId: this.nextLocalId++, 
                        id: null,                     
                        name: '',
                        sets: [{ id: 1, kg: '', reps: '' }]
                    });
                },

                addSet(exercise) {
                    exercise.sets.push({
                        id: exercise.sets.length + 1,
                        kg: '',
                        reps: ''
                    });
                },

                removeSet(exercise, setId) {
                    exercise.sets = exercise.sets.filter(s => s.id !== setId);
                    exercise.sets.forEach((s, i) => s.id = i + 1);
                },

                openExerciseModal(exercise) {
                    this.selectedExerciseFor = exercise;
                    this.showExerciseModal = true;
                },

                closeExerciseModal() {
                    this.showExerciseModal = false;
                    this.exerciseSearch = '';
                },

                selectExercise(ex) {
                    this.selectedExerciseFor.name = ex.name; 
                    this.selectedExerciseFor.id = ex.id;     
                    this.closeExerciseModal();
                },

                submitWorkout() {
                    if (this.exercises.length === 0) {
                        alert("You must add at least one exercise.");
                        return;
                    }

                    const unselected = this.exercises.filter(e => !e.id);
                    if (unselected.length > 0) {
                        alert("Select a valid exercise");
                        return;
                    }

                    $el = this.$el;
                    $el.submit();
                },

                init() {
                    this.fetchExercises();
                }
            };
        }
    </script>
</x-app-layout>
