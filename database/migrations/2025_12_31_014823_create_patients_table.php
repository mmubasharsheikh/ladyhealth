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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique(); // 1 user → 0..1 patient

            $table->boolean('is_anonymous_default')->default(true);

            $table->string('age_range')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('city')->nullable();

            $table->enum('trust_level', ['basic', 'verified', 'trusted'])
                ->default('basic');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
