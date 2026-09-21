<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('facility_id')->nullable()->constrained('facilities')->nullOnDelete();
            $table->string('priority')->default('sedang');
            $table->string('status')->default('menunggu_verifikasi');
            $table->string('location_detail')->nullable();
            $table->text('description');
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamps();

            $table->index('status');
            $table->index('priority');
            $table->index('reported_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
