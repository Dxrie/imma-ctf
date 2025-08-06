<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('score')->default(0);
            $table->string('invite_code')->nullable();
            $table->foreignId('captain_id')->unique()->constrained('users');
            $table->timestamps();
        });

        Schema::create('teams_member', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->unique()->constrained('users');
            $table->foreignId('team_id')->constrained('teams');
            $table->enum('role', ['captain', 'member']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
