<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServerUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production.server_update', function (Blueprint $table) {
            $table->id('server_update_id');
            $table->integer('user_id')->comment('Уникальный идентификатор пользователя')->nullable(false);
            $table->dateTime('start_date')->comment('Дата и время начала обновления')->nullable()->default('curdate()');
            $table->integer('server_id')->comment('Уникальный идентификатор сервера')->nullable(false);
            $table->dateTime('end_date')->comment('Дата и время окончания обновления')->nullable();
            $table->integer('update_status_id')->nullable();
            $table->integer('update_package_id')->nullable(false)->comment('Уникальный идентификатор пакета обновления');
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
        Schema::dropIfExists('production.server_update');
    }
}
