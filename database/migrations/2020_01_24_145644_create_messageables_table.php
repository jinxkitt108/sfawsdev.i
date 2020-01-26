<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messageables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('message_id')->unsigned();
            $table->integer('sender_id')->unsigned();
            $table->integer('receiver_id')->unsigned();
            $table->boolean('read')->default(false);
            $table->boolean('sender_deleted')->default(false);
            $table->boolean('receiver_deleted')->default(false);
            $table->timestamps();

            $table->foreign('message_id')
                ->references('id')->on('messages');
            $table->foreign('sender_id')
                ->references('id')->on('users');
            $table->foreign('receiver_id')
                ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messageables');
    }
}
