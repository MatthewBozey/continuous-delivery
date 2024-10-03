<?php

namespace App\Service;

use App\Models\TelegramUserToken;
use App\Models\User;
use DefStudio\Telegraph\DTO\TelegramUpdate;
use DefStudio\Telegraph\Exceptions\TelegramUpdatesException;
use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Facades\Telegraph;

class TelegramService
{
    protected $lastUpdateId;

    protected $bot;

    public function __construct()
    {
        /*        $telegramBotManager = new TelegramBotManager();
                $this->bot = $telegramBotManager->getBot();
                // Загрузка последнего обработанного update_id из хранилища (например, база данных или файл)
                $this->lastUpdateId = cache('telegram_last_update_id', 0);*/
    }

    /**
     * @throws TelegramUpdatesException
     */
    public function handleUpdates()
    {
        // Получаем обновления
        $telegraphBot = $this->bot;
        if ($telegraphBot) {
            $updates = $telegraphBot->updates();

            foreach ($updates as $update) {
                $updateId = $update->id();
                if ($updateId > $this->lastUpdateId) { // Проверяем, если update_id больше последнего обработанного, значит это новое обновление
                    logger()->info($update);
                    $this->processUpdate($update); // Обрабатываем обновление
                    $this->lastUpdateId = $updateId; // Обновляем последний update_id
                    cache(['telegram_last_update_id' => $this->lastUpdateId]); // Сохраняем последний обработанный update_id
                }
            }
        }
    }

    /**
     * @throws TelegraphException
     */
    protected function processUpdate(TelegramUpdate $update): void
    {
        if ($update->message()->text() !== null) {
            $bot = Telegraph::bot($this->bot);
            $text = $update->message()->text();
            $chatId = $update->message()->chat()->id();
            $chat = $bot->chat($chatId);
            if (str_starts_with($text, '/start')) {
                $params = explode(' ', $text);
                if (! isset($params[1])) {
                    $chat->chatAction('typing');
                    $chat->message('Это бот для работы CI | CD проектов КРОН и КРОН-ТМ')->send();
                } else {
                    $token = $params[1];
                    if (TelegramUserToken::where('token', $token)->doesntExist()) {
                        $message = $chat->message('Токен с которым вы обращаетесь ошибочный. Повторите попытку привязки в системе.')->send()->telegraphMessageId();
                        $chat->pinMessage($message);
                    } else {
                        $userData = json_decode(base64_decode($params[1]));
                        if (! isset($userData['user_id']) && ! isset($userData['expired_time'])) {
                            $chat->message('Ошибка в преобразовании токена.  Повторите попытку привязки в системе.');
                        }

                        $user = User::find($userData);
                        if (! $user) {
                            $message = $chat->message('Не удалось найти пользователя зарегистрированного в системе. Повторите попытку привязки в системе.')->send()->telegraphMessageId();
                            $chat->pinMessage($message);
                        }
                        $user->update(['telegram' => $chatId]);
                        TelegramUserToken::where('token', $token)->delete();
                        $message = $chat->message('Вы успешно привязали свой аккаунт к системе.')->send()->telegraphMessageId();
                        $chat->pinMessage($message);
                    }
                }
                logger()->info('Параметры: '.implode(', ', $params));
            }
        }
    }
}
