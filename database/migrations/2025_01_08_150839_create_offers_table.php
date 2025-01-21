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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("store_id")->constrained();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('available')->default(false);
            $table->float("cash_back")->nullable();
            $table->text('title');
            $table->foreignId("category_id")->constrained();






        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
