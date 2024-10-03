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
        Schema::table('system.user', function (Blueprint $table) {
            $table->after('patronymic', function (Blueprint $field) {
                $field->bigInteger('telegram')->nullable()->comment('ID телеграмм чата');
                $field->boolean('notify_password_reset_via_email')->default(true)->comment('Уведомлять о сбросе пароля на электронную почту');
                $field->boolean('notify_password_reset_via_telegram')->default(false)->comment('Уведомлять о сбросе пароля в телеграмм');
            });
        });
    }
};
