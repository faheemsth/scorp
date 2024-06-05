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
        Schema::table('goal_trackings', function (Blueprint $table) {
            $table->integer('region_id');
            $table->integer('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goal_trackings', function (Blueprint $table) {
            $table->dropColumn('region_id');
            $table->dropColumn('brand_id');
        });
    }
};