<?php

namespace App\Console\Commands;

use App\Service\TelegramBotManager;
use App\Service\TelegramService;
use DefStudio\Telegraph\Exceptions\TelegramUpdatesException;
use Illuminate\Console\Command;

class PollTelegramUpdates extends Command
{
    /** @var string */
    protected $signature = 'telegram:poll-updates';

    /**
     * @var string
     */
    protected $description = '';

    protected TelegramBotManager $telegramBotManager;

    public function __construct()
    {
        parent::__construct();
        $this->telegramBotManager = new TelegramBotManager();
    }

    /**
     * @throws TelegramUpdatesException
     */
    public function handle()
    {
        $telegraphBot = $this->telegramBotManager->getBot();
        if ($telegraphBot) {
            app(TelegramService::class)->handleUpdates($telegraphBot);
        }
    }
}
