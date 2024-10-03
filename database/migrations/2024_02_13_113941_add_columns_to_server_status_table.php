<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToServerStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production.server_status', function (Blueprint $table) {
            $table->string('status_color')->nullable()->comment('Цвет состояния');
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
        Schema::table('production.server_status', function (Blueprint $table) {
            $table->dropColumn('status_color');
            $table->dropTimestamps();
            $table->dropSoftDeletes();
        });
    }
}
