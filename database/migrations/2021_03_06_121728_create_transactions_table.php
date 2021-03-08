<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();
            $table->date('date');
            $table->integer('outlet_id');
            $table->foreign('outlet_id')
                ->references('id')
                ->on('outlets');
            $table->integer('cashier_id');
            $table->foreign('cashier_id')
                ->references('id')
                ->on('users');
            $table->integer('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers');
            $table->integer('product_id');
            $table->foreign('product_id')
                ->references('id')
                ->on('products');
            $table->integer('qty');
            $table->bigInteger('total_price');
            $table->enum('status', ['process', 'pickup', 'done'])->default('process');
            $table->enum('payment', ['pending', 'done'])->default('pending');
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
        Schema::dropIfExists('transactions');
    }
}
