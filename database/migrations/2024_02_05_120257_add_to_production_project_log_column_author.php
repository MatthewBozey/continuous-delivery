<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToProductionProjectLogColumnAuthor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production.project_log', function (Blueprint $table) {
            $table->string('author')->nullable(true)->comment('Пользователь кто обновляет');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production.project_log', function (Blueprint $table) {
            $table->dropColumn('author');
        });
    }
}
