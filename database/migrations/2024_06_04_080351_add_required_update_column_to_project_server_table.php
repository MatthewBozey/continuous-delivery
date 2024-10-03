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
        Schema::table('dbo.project_server', static function (Blueprint $table) {
            $table->boolean('required_update')->default(false)->comment('Обновления обязательны')->after('server');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dbo.project_server', function (Blueprint $table) {
            $table->dropColumn('required_update');
        });
    }
};
