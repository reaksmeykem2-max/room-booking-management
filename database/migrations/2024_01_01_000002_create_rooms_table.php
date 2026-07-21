<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('floor')->nullable();
            $table->integer('capacity')->default(10);
            $table->text('description')->nullable();
            $table->text('facilities')->nullable(); // JSON: projector, whiteboard, etc.
            $table->boolean('requires_approval')->default(false);
            $table->time('available_from')->default('08:00:00');
            $table->time('available_until')->default('17:00:00');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
