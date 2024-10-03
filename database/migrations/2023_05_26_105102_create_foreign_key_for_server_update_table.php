<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeyForServerUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production.server_update', function (Blueprint $table) {
            $table->foreign('server_id')->references('server_id')->on('production.server');
            $table->foreign('update_package_id')->references('update_package_id')->on('update.update_package');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production.server_update', function (Blueprint $table) {
            //
        });
    }
}
