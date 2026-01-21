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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // WHO performed the action
            $table->foreignId('actor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Actor role at the time of action
            $table->string('actor_role');
            // admin | doctor | patient | receptionist | pa | clinic_admin

            // WHAT action was performed
            $table->string('action');
            // create_post, update_post, delete_post
            // approve_doctor, reject_doctor
            // book_appointment, cancel_appointment
            // report_abuse, ban_user, etc

            // ON WHAT entity
            $table->string('target_type')->nullable();
            // post | comment | user | doctor | appointment | clinic

            $table->unsignedBigInteger('target_id')->nullable();

            // Extra context (reason, old/new values, notes)
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['actor_id', 'actor_role']);
            $table->index(['target_type', 'target_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
