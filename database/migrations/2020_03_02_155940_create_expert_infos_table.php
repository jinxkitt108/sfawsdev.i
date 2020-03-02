<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('website')->nullable();
            $table->json('categories');
            $table->json('jobs');
            $table->string('work');
            $table->string('company_name');
            $table->string('position');
            $table->string('work_description')->nullable();
            $table->string('school');
            $table->string('degree');
            $table->string('trainings')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expert_infos');
    }
}
