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
        Schema::create('dbo.project_server', function (Blueprint $table) {
            $table->id();
            $table->integer('project')->comment('ID проекта')->nullable(false);
            $table->integer('server')->comment('ID сервера')->nullable(false);
            $table->foreign('project')->references('project_id')->on('dbo.project')->onDelete('cascade');
            $table->foreign('server')->references('server_id')->on('production.server')->onDelete('cascade');
            $table->unique([
                'project',
                'server',
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
        Schema::dropIfExists('dbo.project_server');
    }
};
