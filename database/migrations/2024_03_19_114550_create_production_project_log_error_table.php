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
        Schema::create('production.project_log_server_error', function (Blueprint $table) {
            $table->id();
            $table->integer('project_log_server')->nullable(false)->comment('ID обновления на сервере');
            $table->text('error_message')->nullable(false)->comment('Текст ошибки');
            $table->foreign('project_log_server')->on('production.project_log_server')->references('project_log_server_id')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_project_log_error');
    }
};
