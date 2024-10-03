<?php

namespace App\Providers;

use App\Service\TelegramBotManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! App::isProduction()) {
            DB::listen(static fn ($query) => logger(Str::replaceArray('?', $query->bindings, $query->sql)));
        }

        $this->app->singleton(TelegramBotManager::class, function ($app) {
            return new TelegramBotManager();
        });
    }
}
