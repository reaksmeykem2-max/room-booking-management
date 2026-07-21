<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_schedules', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('day_of_week'); // 1=Monday, 7=Sunday
            $table->string('day_name');
            $table->time('start_time')->default('08:00:00');
            $table->time('end_time')->default('17:00:00');
            $table->boolean('is_working_day')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('working_schedules');
    }
};
