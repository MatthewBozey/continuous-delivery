<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production.update_error', function (Blueprint $table) {
            $table->id('update_error_id');
            $table->unsignedBigInteger('server_update_id')->nullable(false);
            $table->text('error_message')->nullable(false);
            $table->timestamps();

            $table->foreign('server_update_id')->references('server_update_id')->on('production.server_update');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production.update_error');
    }
}
