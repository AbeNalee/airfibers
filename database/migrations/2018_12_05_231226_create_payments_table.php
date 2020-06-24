<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('phone_number');
            $table->string('package_id');
            $table->string('checkout_req_id')->nullable();
            $table->string('response_code')->nullable();
            $table->string('status')->nullable();
            $table->string('merchant_req_id')->nullable();
            $table->string('payment_method');
            $table->string('amount');
            $table->string('customer_id')->nullable();
            $table->string('client_mac')->nullable();
            $table->string('ap_mac')->nullable();
            $table->string('mpesa_id')->nullable();
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
