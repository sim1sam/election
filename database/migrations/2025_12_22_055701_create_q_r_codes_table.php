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
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->integer('size')->default(300);
            $table->string('format')->default('svg'); // svg, png, jpg
            $table->string('file_path')->nullable(); // Path to saved file
            $table->text('svg_content')->nullable(); // Store SVG content
            $table->string('title')->nullable(); // Optional title/description
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
