<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class UserPasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        $channels = [];

        if ($notifiable->notify_password_reset_via_email) {
            $channels[] = 'mail';
        }

        if ($notifiable->notify_password_reset_via_telegram) {
            $channels[] = 'telegram';
        }


        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ваш пароль был сброшен')
            ->line('Ваш пароль был успешно сброшен.')
            ->line('Если вы не выполняли это действие, пожалуйста, немедленно свяжитесь с нашей службой поддержки.')
            ->line('Спасибо за использование нашего приложения!');
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram)
            ->line('Ваш пароль был успешно сброшен.')
            ->line('Если вы не выполняли это действие, пожалуйста, немедленно свяжитесь с нашей службой поддержки.')
            ->line('Спасибо за использование нашего приложения!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
