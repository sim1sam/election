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
        Schema::create('voters', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name')->comment('নাম');
            $table->string('voter_number')->unique()->comment('ভোটার নম্বর');
            $table->string('father_name')->nullable()->comment('পিতা');
            $table->string('mother_name')->nullable()->comment('মাতা');
            $table->string('occupation')->nullable()->comment('পেশা');
            $table->text('address')->nullable()->comment('ঠিকানা');
            $table->string('polling_center_name')->nullable()->comment('ভোট কেন্দ্রের নাম');
            $table->string('ward_number')->nullable()->comment('ওয়ার্ড নম্বর');
            $table->date('date_of_birth')->nullable()->comment('জন্ম তারিখ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
