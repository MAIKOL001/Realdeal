<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSheetOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheet_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->decimal('amount', 10, 2);
            $table->integer('quantity');
            $table->string('item');
            $table->string('client_name');
            $table->string('client_city');
            $table->date('date');
            $table->string('phone');
            $table->string('agent')->nullable();
            $table->string('status')->nullable();
            $table->string('code')->nullable();
            $table->string('email')->nullable();
            $table->string('invoice_code')->nullable();
            $table->string('sheet_id');
            $table->string('sheet_name');
            // Add more fields as needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sheet_orders');
    }
}
