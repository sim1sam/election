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
        Schema::create('home_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_title')->default('🇧🇩 নির্বাচন তথ্য');
            $table->string('countdown_title')->default('⏰ তথ্য প্রকাশের তারিখ পর্যন্ত অবশিষ্ট সময়');
            $table->string('countdown_message')->default('📋 তথ্য প্রকাশের অপেক্ষায়...');
            $table->string('waiting_title')->default('⏳ তথ্য প্রকাশের অপেক্ষায়');
            $table->text('waiting_message_1')->nullable();
            $table->text('waiting_message_2')->nullable();
            $table->string('election_info_title')->default('নির্বাচনী এলাকা তথ্য');
            $table->string('area_name')->default('ঢাকা-১');
            $table->string('election_center')->default('১০');
            $table->string('total_voters')->default('৫০,০০০');
            $table->string('voters_section_title')->default('সকল ভোটার তালিকা');
            $table->string('total_voters_label')->default('মোট ভোটার সংখ্যা');
            $table->dateTime('countdown_target_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_page_settings');
    }
};
