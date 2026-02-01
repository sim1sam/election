<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Duplicate rule: নাম (Name) + ভোটার নম্বর (Voter Number) together must be unique.
     * Replaces previous (name, voter_serial_number) unique with (name, voter_number).
     */
    public function up(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropUnique('voters_name_voter_serial_number_unique');
        });

        Schema::table('voters', function (Blueprint $table) {
            $table->unique(['name', 'voter_number'], 'voters_name_voter_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropUnique('voters_name_voter_number_unique');
        });

        Schema::table('voters', function (Blueprint $table) {
            $table->unique(['name', 'voter_serial_number'], 'voters_name_voter_serial_number_unique');
        });
    }
};
