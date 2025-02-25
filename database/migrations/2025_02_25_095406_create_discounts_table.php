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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('percentage', 10, 2)->nullable();
            $table->decimal('min_total', 10, 2)->nullable();
            $table->enum('discount_applicable_type', ['general', 'associated_general', 'associated_limitation']);
            $table->time('active_from')->useCurrent();
            $table->time('active_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
