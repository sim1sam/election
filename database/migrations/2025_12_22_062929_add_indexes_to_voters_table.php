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
        Schema::table('voters', function (Blueprint $table) {
            // Add indexes for faster searches
            $table->index('ward_number', 'idx_ward_number');
            $table->index('date_of_birth', 'idx_date_of_birth');
            $table->index(['ward_number', 'date_of_birth'], 'idx_ward_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropIndex('idx_ward_number');
            $table->dropIndex('idx_date_of_birth');
            $table->dropIndex('idx_ward_date');
        });
    }
};

