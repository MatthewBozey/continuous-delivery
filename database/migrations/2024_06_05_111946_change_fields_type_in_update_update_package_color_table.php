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
        Schema::table('update.update_package_color', function (Blueprint $table) {
            $table->float('min_value')->change();
            $table->float('max_value')->change();
            $table->unique(['min_value', 'max_value']);
        });
    }
};
