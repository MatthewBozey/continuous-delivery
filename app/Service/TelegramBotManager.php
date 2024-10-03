<?php

namespace App\Service;

use DefStudio\Telegraph\Models\TelegraphBot;

class TelegramBotManager
{
    protected $bot;

    public function __construct()
    {
        /*        $this->bot = TelegraphBot::first();

                if (! $this->bot) {
                    $this->bot = $this->createBot();
                }*/
    }

    protected function createBot(): ?TelegraphBot
    {
        if (env('TELEGRAM_BOT_TOKEN')) {
            return new TelegraphBot(['token' => env('TELEGRAM_BOT_TOKEN'), 'name' => 'DevOps Bot']);
        } else {
            return null;
        }
    }

    public function getBot(): ?TelegraphBot
    {
        return $this->bot;
    }
}
