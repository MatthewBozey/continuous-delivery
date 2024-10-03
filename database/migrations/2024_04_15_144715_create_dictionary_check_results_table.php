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
        Schema::create('update.dictionary_check_results', function (Blueprint $table) {
            $table->id();
            $table->integer('update_script')
                ->nullable(false)
                ->comment('ID скрипта');
            $table->integer('server')
                ->nullable(false)
                ->comment('ID сервера');
            $table->string('author')
                ->nullable(false)
                ->comment('Пользователь выполняющий проверку');
            $table->boolean('check_result')
                ->nullable(false)
                ->comment('Результат проверки');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('update.dictionary_check_results');
    }
};
