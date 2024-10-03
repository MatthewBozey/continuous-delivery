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
        Schema::create('update.update_package_color', function (Blueprint $table) {
            $table->id();
            $table->integer('min_value')->nullable(false)->comment('Минимальное значение');
            $table->integer('max_value')->nullable(false)->comment('Максимальное значение');
            $table->string('color')->nullable(false)->comment('Цвет');
            $table->string('author')->nullable()->comment('Автор');
            $table->unique(['min_value', 'max_value']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('update.update_package_color');
    }
};
