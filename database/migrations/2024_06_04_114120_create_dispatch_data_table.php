<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispatchDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatch_data', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->decimal('amount', 8, 2);
            $table->integer('quantity');
            $table->string('item');
            $table->string('client_name');
            $table->string('client_city');
            $table->date('date');
            $table->string('phone');
            $table->string('status');
            $table->string('code');
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
        Schema::dropIfExists('dispatch_data');
    }
}
