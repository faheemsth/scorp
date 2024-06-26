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
        Schema::create('emailsendlogs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['0', '1', '2', '3', '4', '5'])->default('0');
            $table->integer('refrance_id')->nullable();
            $table->longText('subject')->nullable();
            $table->longText('content')->nullable();
            $table->longText('email')->nullable();
            $table->enum('status', ['true', 'false'])->default('true');
            $table->longText('sent_log')->nullable();
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
        Schema::dropIfExists('emailsendlogs');
    }
};
