<?php

namespace App\Console\Commands\CI;

use App\Jobs\DictionaryCollector;
use Illuminate\Console\Command;

class DictionaryGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ci:dictionary_generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Команда для сбора справочников';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new DictionaryCollector());
    }
}
