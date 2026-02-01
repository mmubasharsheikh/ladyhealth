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
        Schema::create('clinic_doctor_contracts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('clinic_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |-----------------------------
            | PER-APPOINTMENT BILLING
            |-----------------------------
            */
            $table->enum('appointment_billing_type', [
                'none',             // no per-appointment fee
                'percentage',       // % per appointment
                'fixed_per_visit',  // fixed amount per visit
                'hybrid'            // future
            ])->default('none');

            // Meaning depends on appointment_billing_type
            $table->decimal('appointment_billing_value', 10, 2)->nullable();

            /*
            |-----------------------------
            | TIME-BASED SUBSCRIPTION
            |-----------------------------
            */
            $table->enum('subscription_type', [
                'none',
                'weekly',
                'monthly',
                'yearly'
            ])->default('none');

            $table->decimal('subscription_amount', 10, 2)->nullable();

            /*
            |-----------------------------
            | CONTRACT META
            |-----------------------------
            */
            $table->date('starts_at');
            $table->date('ends_at')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['clinic_id', 'doctor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_commission_rules');
    }
};
