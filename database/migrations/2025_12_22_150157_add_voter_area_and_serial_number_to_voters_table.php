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
            $table->string('voter_area_number')->nullable()->after('ward_number')->comment('ভোটার এলাকার নম্বর');
            $table->string('voter_serial_number')->nullable()->after('voter_area_number')->comment('ভোটার সিরিয়াল নম্বর');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropColumn(['voter_area_number', 'voter_serial_number']);
        });
    }
};
