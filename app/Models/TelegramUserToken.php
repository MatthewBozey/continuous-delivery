<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramUserToken extends Model
{
    protected $table = 'system.telegram_user_token';
    protected function casts(): array
    {
        return ['token' => 'string'];
    }

    protected $fillable = ['id', 'token'];


}
