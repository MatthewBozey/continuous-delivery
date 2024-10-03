<?php

namespace Database\Seeders;

use App\Models\StatePlanning;
use Illuminate\Database\Seeder;

class StatePlanningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            [
                'state_title' => 'Создано',
                'state_code' => 'CREATED',
                'state_color' => '#5cb85c', // зеленый цвет для состояния "Создано"
            ],
            [
                'state_title' => 'Запланировано',
                'state_code' => 'SCHEDULED',
                'state_color' => '#f0ad4e', // оранжевый цвет для состояния "Запланировано"
            ],
            [
                'state_title' => 'В работе',
                'state_code' => 'IN_PROGRESS',
                'state_color' => '#337ab7', // синий цвет для состояния "В работе"
            ],
            [
                'state_title' => 'Завершено',
                'state_code' => 'COMPLETED',
                'state_color' => '#5cb85c', // зеленый цвет для состояния "Завершено"
            ],
            [
                'state_title' => 'Не удалось',
                'state_code' => 'FAILED',
                'state_color' => '#d9534f', // красный цвет для состояния "Не удалось"
            ],
            [
                'state_title' => 'Отменено',
                'state_code' => 'CANCELED',
                'state_color' => '#d9534f', // красный цвет для состояния "Отменено"
            ],
            [
                'state_title' => 'Ожидает утверждения',
                'state_code' => 'AWAITING_APPROVAL',
                'state_color' => '#f0ad4e', // оранжевый цвет для состояния "Ожидает утверждения"
            ],
            [
                'state_title' => 'Приостановлено',
                'state_code' => 'PAUSED',
                'state_color' => '#5bc0de', // голубой цвет для состояния "Приостановлено"
            ],
        ];

        foreach ($states as $state) {
            $s = new StatePlanning($state);
            $s->save();
        }
    }
}
