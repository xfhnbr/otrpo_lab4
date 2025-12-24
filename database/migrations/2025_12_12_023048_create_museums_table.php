<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('museums', function (Blueprint $table) {
            $table->id();
            $table->string('name_ru');
            $table->string('name_original');
            $table->text('description');
            $table->text('detailed_description')->nullable();
            $table->string('address');
            $table->string('working_hours');
            $table->decimal('ticket_price', 8, 2);
            $table->string('website_url')->nullable();
            $table->string('image_filename');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('museums');
    }
};