<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('running_session_participations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('running_session_id')->constrained('running_sessions')->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['user_id','running_session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('running_session_participations');
    }
};
