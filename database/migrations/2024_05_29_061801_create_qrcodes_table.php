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
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_code');
            $table->foreignId('parent_product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->date('date_generated');
            $table->unsignedBigInteger('product_unit_id');
            $table->string('status')->default('pending'); // Add the status column here
            $table->timestamps();

            // Set up foreign key constraint to reference unit_id in products table
            $table->foreign('product_unit_id')->references('unit_id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qrcodes');
    }
};
