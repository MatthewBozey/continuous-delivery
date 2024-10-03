<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionProjectLogScriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production.project_log_script', function (Blueprint $table) {
            $table->id();
            $table->integer('project_log_server');
            $table->integer('update_package');
            $table->integer('update_script');
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('project_log_server')->on('production.project_log_server')->references('project_log_server_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production.project_log_script');
    }
}
