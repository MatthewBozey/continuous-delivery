<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production.server', function (Blueprint $table) {
            $table->boolean('update_required')->default(false);
            $table->softDeletes();
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('server', function (Blueprint $table) {
            $table->dropColumn('update_required');
            $table->dropSoftDeletes();
            $table->dropTimestamps();
        });
    }
}
