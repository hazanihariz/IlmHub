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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        $table->text('description');
        $table->dateTime('event_date');
        $table->string('location');
        $table->integer('capacity');
        // Your specific gender policy
        $table->enum('gender_policy', ['mixed', 'males_only', 'females_only', 'segregated']);
        $table->string('image_path')->nullable(); // For the poster
        
        // Foreign Key to Categories
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
