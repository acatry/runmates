<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('running_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');         
            $table->string('city')->nullable(); 
            $table->string('zipcode', 12)->nullable();
            $table->dateTime('start_at')->index();

            
            $table->decimal('distance_km_min', 5, 2)->nullable();
            $table->decimal('distance_km_max', 5, 2)->nullable();
            $table->decimal('pace_min_per_km_min', 4, 2)->nullable(); 
            $table->decimal('pace_min_per_km_max', 4, 2)->nullable();
            $table->unsignedInteger('duration_min')->nullable();
            $table->unsignedInteger('duration_max')->nullable();

            $table->unsignedInteger('max_participants')->nullable();
            $table->enum('visibility', ['public','private'])->default('public');
            $table->enum('status', ['draft','published','cancelled'])->default('published');

            $table->timestamps();

            $table->index(['city','zipcode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('running_sessions');
    }
};
