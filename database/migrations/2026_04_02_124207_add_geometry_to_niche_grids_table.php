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
        // Columns already exist in base migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('niche_grids', function (Blueprint $table) {
            $table->dropColumn(['rotation', 'width_scale']);
        });
    }
};
