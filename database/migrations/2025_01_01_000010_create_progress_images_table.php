<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('progress_history_id')->constrained('progress_histories')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('type')->default('progress'); // before, after, progress
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_images');
    }
};
