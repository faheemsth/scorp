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
        //
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', '50')->nullable();
            $table->integer('university_id')->nullable();
            $table->integer('courselevel_id')->nullable();
            $table->integer('courseduration_id')->nullable();
            $table->string('currency')->nullable();
            $table->string('fee')->nullable();
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
        //
    }
};
