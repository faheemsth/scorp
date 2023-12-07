<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('company_permission', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->nullable();  
            $table->bigInteger('permitted_company_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_permission');
    }
};
