<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_marketings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('type')->nullable();
            $table->text('email_content')->nullable();
            $table->text('tag')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('email_marketings');
    }
};
