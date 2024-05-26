<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('terminalRef');
            $table->float('amount');
            $table->boolean('status')->default(0);
            $table->string('currency');
            $table->string('language');
            $table->float('amountRef');
            $table->integer('transactionNo');
            $table->integer('orderRef');
            $table->string('message');
            $table->boolean('is_success');
            $table->string('token');
            $table->date('paidDate');
            $table->unsignedBigInteger('subscription_id');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('payment_statuses')->onDelete('cascade');
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
        Schema::dropIfExists('payments');
    }
}
