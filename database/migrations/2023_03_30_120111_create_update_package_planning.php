<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatePackagePlanning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('production.state_planning', function (Blueprint $table) {
            $table->id('state_id');
            $table->string('state_title')->nullable(false)->comment('Название состояния');
            $table->string('state_code')->nullable(false)->comment('Системное название');
            $table->string('state_color')->nullable()->comment('Цвет состояния');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('production.package_planning', function (Blueprint $table) {
            $table->id();
            $table->integer('update_package_id')->comment('Уникальный идентификатор конфигурационного пакета обязательно');
            $table->date('planned_date')->nullable()->comment('Запланированная дата пакета');
            $table->integer('user_id')->comment('Пользователь, который запланировал');
            $table->integer('server_id')->nullable(false)->comment('Уникальный идентификатор сервера ');
            $table->integer('state_id')->nullable(false)->comment('Идентификатор состояния');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production.package_planning');
    }
}
