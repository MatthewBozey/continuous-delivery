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
            $table->dropIndex('update_update_package_color_min_value_max_value_unique');
        });
    }
};
