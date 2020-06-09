<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLesionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('analyse');
            $table->string('original_image')->nullable();
            $table->string('checked_image')->nullable();
            $table->string('classified_image')->nullable();
            $table->string('accuracy')->nullable();
            $table->string('sensitivity')->nullable();
            $table->string('specificity')->nullable();
            $table->string('dice')->nullable();
            $table->text('description');
            $table->timestamps();

            $table->foreign('analyse')->references('id')->on('analyses')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesions');
    }
}
