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
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('show_date');
            $table->time('show_time');
            $table->decimal('price_first_class', 8, 2)->default(100.00);
            $table->decimal('price_second_class', 8, 2)->default(70.00);
            $table->decimal('price_standard', 8, 2)->default(50.00);
            $table->integer('total_seats')->default(50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shows');
    }
};
