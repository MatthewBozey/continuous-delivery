<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system.permission_section', static function (Blueprint $table) {
            $table->bigIncrements('permission_section_id');
            $table->string('title')->unique()->comment('Название секции');
            $table->string('sysname')->unique()->comment('Системное имя секции');
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
        Schema::dropIfExists('permission_sections');
    }
}
