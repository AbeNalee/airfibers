<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpesas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trans_type');
            $table->string('tran_id');
            $table->string('trans_time');
            $table->string('trans_amount');
            $table->string('bill_ref')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('account_bal')->nullable();
            $table->string('third_party')->nullable();
            $table->string('msisdn');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
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
        Schema::dropIfExists('mpesas');
    }
}
