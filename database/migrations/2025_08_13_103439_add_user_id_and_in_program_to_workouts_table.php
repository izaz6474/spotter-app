<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workouts', function (Blueprint $table) {
            // Add user_id foreign key if it doesn't exist
            if (!Schema::hasColumn('workouts', 'user_id')) {
                $table->foreignId('user_id')
                      ->constrained()
                      ->onDelete('cascade')
                      ->after('id');
            }

            // Add inProgram boolean
            if (!Schema::hasColumn('workouts', 'inProgram')) {
                $table->boolean('inProgram')
                      ->default(false)
                      ->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workouts', function (Blueprint $table) {
            //
        });
    }
};
