<?php

// ============================================================
// UPDATED MIGRATION
// File: database/migrations/0001_01_01_000000_create_users_table.php
//
// Deceased fields kept (matching 1986 logbook + Image 1):
//   No. → id (auto)
//   Name of Deceased → last_name, first_name, middle_name
//   Date of Death → date_of_death
//   Date Applied → created_at (auto)
//   Date Expired → computed (created_at + 5 years), no column needed
//   Requesting Party → stored on burial_permits.requestor_name
//   Address → stored on burial_permits.requestor_address
//   Kind of Burial → kind_of_burial
//   Amount → stored on burial_permits.amount_paid
//   Balance → stored on burial_permits.balance
//   Contact No. → stored on burial_permits.requestor_contact
//
// REMOVED from deceased_persons: nationality, age, sex,
//   civil_status, religion, date_of_birth, place_of_death,
//   cause_of_death, age_at_death
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── employees (referenced by users) ──────────────────────
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number')->unique();
            $table->timestamps();
        });

        // ── users ─────────────────────────────────────────────────
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number')->nullable();
            $table->string('name');
            $table->string('rank')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->string('email')->unique();
            $table->string('contact_number')->nullable();
            $table->string('address')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('employee_number')
                ->references('employee_number')
                ->on('employees')
                ->onDelete('cascade');
        });

        // ── password reset tokens ─────────────────────────────────
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ── sessions ──────────────────────────────────────────────
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('employees');
    }
};
