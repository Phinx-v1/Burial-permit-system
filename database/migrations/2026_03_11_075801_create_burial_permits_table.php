<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('burial_permits', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number');
            $table->foreignId('deceased_id')->nullable()->constrained('deceased_persons')->onDelete('cascade');
            $table->string('applicant_name');
            $table->string('applicant_relationship');
            $table->string('applicant_contact');
            $table->string('applicant_address')->nullable();
            $table->date('issued_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'released', 'expired'])->default('pending');
            $table->text('remarks')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('burial_permits');
    }
};
