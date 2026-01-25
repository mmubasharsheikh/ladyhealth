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
        Schema::create('doctor_location_availabilities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doctor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('clinic_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->json('weekly_schedule');
            /*
              {
                "mon": [{"from":"09:00","to":"13:00"}],
                "tue": [{"from":"17:00","to":"21:00"}]
              }
            */

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['doctor_id', 'clinic_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_location_availabilities');
    }
};
