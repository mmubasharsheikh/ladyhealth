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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('doctor_id')->constrained('users');

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('managed_by')->constrained('users');
            $table->enum('managed_by_role', ['receptionist', 'pa', 'doctor']);

            $table->timestamp('appointment_time');
            $table->enum('status', ['scheduled', 'cancelled', 'completed'])
                ->default('scheduled');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
