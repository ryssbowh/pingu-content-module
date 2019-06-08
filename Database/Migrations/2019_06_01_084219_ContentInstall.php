<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentInstall extends Migration
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
            $table->string('titleField');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('published');
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('content_type_id')->unsigned()->index();
            $table->foreign('content_type_id')->references('id')->on('content_types');
            $table->integer('creator_id')->unsigned()->index();
            $table->foreign('creator_id')->references('id')->on('users')->onCascade('delete');
            $table->timestamps();
        });

        Schema::create('fields_available', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('class');
            $table->timestamps();
        });

        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('instance');
            $table->boolean('deletable');
            $table->boolean('editable');
            $table->string('name');
            $table->string('machineName');
            $table->string('helper');
            $table->integer('content_type_id')->unsigned()->index();
            $table->foreign('content_type_id')->references('id')->on('content_types');
            $table->integer('weight');
            $table->timestamps();
        });

        // Schema::create('content_type_field', function(Blueprint $table){
        //     $table->increments('id');
        //     $table->integer('content_type_id')->unsigned()->index();
        //     $table->foreign('content_type_id')->references('id')->on('content_types');
        //     $table->integer('field_id')->unsigned()->index();
        //     $table->foreign('field_id')->references('id')->on('fields');
        // });

        Schema::create('field_booleans', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('default');
            $table->timestamps();
        });

        Schema::create('field_datetimes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('format');
            $table->string('default');
            $table->boolean('required');
            $table->timestamps();
        });

        Schema::create('field_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('default');
            $table->boolean('required');
            $table->timestamps();
        });

        // Schema::create('field_models', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('name');
        //     $table->boolean('required');
        //     $table->string('class');
        //     $table->string('model');
        //     $table->string('allowNoValue');
        //     $table->string('noValueLabel');
        //     $table->json('fields_text');
        //     $table->string('default');
        //     $table->string('helper');
        //     $table->timestamps();
        // });

        Schema::create('field_integers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('default');
            $table->boolean('required');
            $table->timestamps();
        });

        Schema::create('field_floats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('precision');
            $table->string('default');
            $table->timestamps();
        });

        Schema::create('field_texts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('default');
            $table->boolean('required');
            $table->timestamps();
        });

        Schema::create('field_text_longs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('default');
            $table->boolean('required');
            $table->timestamps();
        });

        Schema::create('field_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('default');
            $table->boolean('required');
            $table->timestamps();
        });

        Schema::create('field_values', function (Blueprint $table) {
            $table->increments('id');
            $table->text('value');
            $table->integer('field_id')->unsigned();
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
            $table->integer('content_id')->unsigned();
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
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
        Schema::dropIfExists('fields_values');
        Schema::dropIfExists('field_urls');
        Schema::dropIfExists('field_texts');
        Schema::dropIfExists('field_text_longs');
        Schema::dropIfExists('field_integers');
        Schema::dropIfExists('field_floats');
        Schema::dropIfExists('field_emails');
        Schema::dropIfExists('field_datetimes');
        Schema::dropIfExists('field_booleans');
        Schema::dropIfExists('fields_available');
        Schema::dropIfExists('field_values');
        Schema::dropIfExists('fields');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('content_types');
    }
}
