<?php

namespace App\Notifications;

use Closure;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    private $token;

    /** @var Closure|null */
    private static $createUrlCallback;

    /** @var Closure|null */
    private static $toMailCallback;

    /**
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return $this->buildMailMessage($this->resetUrl($notifiable));
    }

    /**
     * @param  string  $url
     */
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage())
            ->subject(Lang::get('Уведомление о сбросе пароля'))
            ->line(
                Lang::get('Вы получаете это электронное письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.')
            )
            ->action(Lang::get('Сбросить пароль'), $url)
            ->line(
                Lang::get(
                    'Срок действия этой ссылки для сброса пароля истечет через :count минут.',
                    ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]
                )
            )
            ->line(Lang::get('Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.'));
    }

    /**
     * @param  mixed  $notifiable
     */
    protected function resetUrl($notifiable): string
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(
            route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)
        );
    }

    public static function createUrlUsing(Closure $callback): void
    {
        static::$createUrlCallback = $callback;
    }

    public static function toMailUsing(Closure $callback): void
    {
        static::$toMailCallback = $callback;
    }
}
