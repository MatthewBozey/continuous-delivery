<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('production.project_log_package', function (Blueprint $table) {
            $table->id();
            $table->integer('project_log')->nullable(false)->comment('ID обновления');
            $table->integer('update_package')->nullable(false)->comment('ID конфигурационного пакета');
            $table->integer('project_log_server')->nullable(false)->comment('ID сервера');
            $table->foreign('project_log')->on('production.project_log')->references('project_log_id')->onDelete('cascade');
            $table->foreign('update_package')->on('update.update_package')->references('update_package_id')->onDelete('cascade');
            //            $table->foreign('project_log_server')->on('production.project_log_server')->references('project_log_server_id')->onDelete('cascade');
            $table->unique([
                'project_log',
                'update_package',
                'project_log_server',
            ]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production.project_log_package');
    }
};
