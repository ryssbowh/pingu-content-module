<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M2019_08_09_180040000180_Install extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('machineName');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->nullable();
            $table->integer('content_type_id')->unsigned()->index();
            $table->foreign('content_type_id')->references('id')->on('content_types');
            $table->integer('creator_id')->unsigned()->index();
            $table->foreign('creator_id')->references('id')->on('users')->onCascade('delete');
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
        Schema::dropIfExists('contents');
        Schema::dropIfExists('content_types');
    }
}
