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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('weeks'); // 1 to 18
            $table->boolean('is_public')->default(false);
            
            // Only required if public
            $table->string('goal')->nullable(); // e.g. bodyweight, powerlifting
            $table->string('difficulty')->nullable(); // beginner, intermediate, advanced
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('average_time')->nullable(); // in minutes

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
