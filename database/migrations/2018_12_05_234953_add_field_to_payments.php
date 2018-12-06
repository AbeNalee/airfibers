<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
          $table->string('status')->default('NEW');
          $table->string('tracking_id')->nullable();
          $table->string('merchant_ref')->nullable();
          $table->integer('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('tracking_id');
            $table->dropColumn('merchant_ref');
            $table->dropColumn('amount');
        });
    }
}
