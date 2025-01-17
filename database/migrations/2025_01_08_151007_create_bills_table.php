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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text("mid")->nullable();
            $table->float("points")->nullable();
            $table->float("amount")->nullable();
            $table->text("purchasedOn");
            $table->text("nameOnBill");
            $table->text("rawBill");
            $table->enum("status", ["initiated","shared","confirmed","captured"])->default("initiated");
            $table->foreignId("user_id")->constrained();
            $table->foreignId("transaction_id")->nullable()->constrained();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
