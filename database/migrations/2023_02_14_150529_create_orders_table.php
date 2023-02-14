<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id');
            $table->string('sub_total')->nullable();
            $table->string('vat')->nullable();
            $table->string('total')->nullable();
            $table->string('quantity')->nullable();
            $table->string('pay')->nullable();
            $table->string('due')->nullable();
            $table->string('paid_by')->nullable();
            $table->string('order_date')->nullable();
            $table->string('order_month')->nullable();
            $table->string('order_year')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
