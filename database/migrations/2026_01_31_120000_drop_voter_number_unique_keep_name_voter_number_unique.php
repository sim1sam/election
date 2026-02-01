<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Duplicate rule: only (name + voter_number) together must be unique.
     * Same voter_number with different name is allowed.
     */
    public function up(): void
    {
        try {
            Schema::table('voters', function (Blueprint $table) {
                $table->dropUnique(['voter_number']);
            });
        } catch (\Throwable $e) {
            // voter_number unique may not exist (e.g. already dropped), skip
        }
    }

    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->unique('voter_number');
        });
    }
};
