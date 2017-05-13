<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Accounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            $table->string('user_name', 50);
            $table->unsignedTinyInteger('type');
            $table->unsignedBigInteger('balance');
            $table->timestamps();
        });

        Schema::create('transaction_flow', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('initiator_id');
            $table->string('initiator_name', 50);
            $table->unsignedTinyInteger('initiator_type');
            $table->unsignedInteger('recipient_id');
            $table->string('recipient_name', 50)->nullable();
            $table->unsignedTinyInteger('recipient_type');
            $table->unsignedTinyInteger('recharge_type');
            $table->unsignedTinyInteger('status');
            $table->unsignedBigInteger('num');
            $table->string('invitation_code')->nullable();
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
        //
    }
}
