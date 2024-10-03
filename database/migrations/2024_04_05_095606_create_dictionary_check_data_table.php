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
        Schema::create('update.dictionary_check_data', function (Blueprint $table) {
            $table->id();
            $table->integer('update_script')
                ->nullable(false)
                ->comment('ID скрипта');
            $table->string('primary_key')
                ->nullable(false)
                ->comment('Название первичного ключа');
            $table->json('data_fields')
                ->nullable(false)
                ->comment('Все поля с значениями');
            $table->text('sql_query')
                ->nullable(false)
                ->comment('Sql запрос');
            $table->foreign('update_script')
                ->references('update_script_id')
                ->on('update.update_script')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictionary_check_data');
    }
};
