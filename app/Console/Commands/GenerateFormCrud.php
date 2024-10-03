<?php

namespace App\Console\Commands;

use App\Models\PermissionSection;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Psy\Readline\Hoa\ConsoleException;
use Spatie\Permission\Models\Permission;

class GenerateFormCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     *
     * @throws ConsoleException
     */
    public function handle()
    {

        $modelName = $this->ask('Введите название формы (CamelCase)');
        $permissionSectionTitle = $this->ask('Введите название секции (на русском)');
        $camelModelName = Str::camel($modelName);
        $kebabModelName = Str::kebab($modelName);
        $studlyModelName = Str::studly($modelName);
        $snakeModelName = Str::snake($modelName, '-');

        // region Создание модели
        $modelStub = File::get(resource_path('stubs/model.stub'));
        $modelFilePath = app_path("Models/{$modelName}.php");
        File::put($modelFilePath, str_replace('{{studlyModelName}}', $studlyModelName, $modelStub));
        $permissionSection = new PermissionSection(['title' => $permissionSectionTitle, 'sysname' => Str::snake($modelName, '-')]);
        $permissionSection->save();
        $permissions = [
            ['name' => Str::snake($snakeModelName).' list', 'title' => 'Получение списка '.$permissionSectionTitle],
            ['name' => Str::snake($snakeModelName).' show', 'title' => 'Получение '.$permissionSectionTitle],
            ['name' => Str::snake($snakeModelName).' create', 'title' => 'Создание '.$permissionSectionTitle],
            ['name' => Str::snake($snakeModelName).' edit', 'title' => 'Редактирование '.$permissionSectionTitle],
            ['name' => Str::snake($snakeModelName).' delete', 'title' => 'Удаление '.$permissionSectionTitle],
        ];
        foreach ($permissions as $permission) {
            $permission_ = new Permission($permission);
            $permission_->save();
        }
        // endregion

        // region Проверка наличия файла модели
        if (! File::exists($modelFilePath) || File::isDirectory($modelFilePath)) {
            $this->error('Model file not found.');
            File::delete("Models/{$modelName}.php");
            throw new ConsoleException('Модель не найдена');
        }
        //endregion

        // region Создание Фильтра
        $filterStub = File::get(resource_path('stubs/filter.stub'));
        $filterPath = app_path("Filters/{$studlyModelName}Filter.php");
        File::put($filterPath, str_replace('{{studlyModelName}}', $studlyModelName, $filterStub));
        // endregion

        $textToAdd = PHP_EOL.'    /**
    * @param  Builder  $builder
    * @param  QueryFilter  $filters
    * @return Builder
    */
    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }'.PHP_EOL;
        $fileContent = File::get($modelFilePath);
        $fileParts = explode('}', $fileContent, 2); // Разделение содержимого по фигурным скобкам класса
        if (strpos($fileParts[0], $textToAdd) !== false) {
            $this->info('The text is already present in the model class.');

            return;
        }
        $updatedContent = $fileParts[0].$textToAdd.'}'.$fileParts[1];
        File::put($modelFilePath, $updatedContent);

        $tableExists = $this->choice('Таблица существует в базе?', ['no', 'yes']) === 'yes';
        if (! $tableExists) {
            Artisan::call('make:migration', ['name' => 'create_'.Str::snake($modelName).'_table']);
        }

        // region Создание Файлов Валидации
        $createRequestStub = File::get(resource_path('stubs/create_request.stub'));
        File::makeDirectory(app_path("Http/Requests/{$studlyModelName}"));
        $createRequestPath = app_path("Http/Requests/{$studlyModelName}/{$studlyModelName}CreateRequest.php");
        File::put($createRequestPath, str_replace('{{studlyModelName}}', $studlyModelName, $createRequestStub));
        $updateRequestStub = File::get(resource_path('stubs/update_request.stub'));
        $updateRequestPath = app_path("Http/Requests/{$studlyModelName}/{$studlyModelName}UpdateRequest.php");
        File::put($updateRequestPath, str_replace('{{studlyModelName}}', $studlyModelName, $updateRequestStub));
        // endregion

        // region Создание Контроллеров
        $filterStub = File::get(resource_path('stubs/controller.stub'));
        $controllerData = ['{{studlyModelName}}' => $studlyModelName];
        $filterPath = app_path("Http/Controllers/{$studlyModelName}Controller.php");
        File::put($filterPath, str_replace(array_keys($controllerData), $controllerData, $filterStub));
        // endregion

        $routeContent = "Route::middleware(['jwt.auth'])->resource('{$snakeModelName}', \App\Http\Controllers\\{$studlyModelName}Controller::class);";

        $apiRoutesPath = base_path('routes/api.php');

        // region Проверка наличия маршрута, чтобы избежать дублирования
        if (File::exists($apiRoutesPath) && ! File::isDirectory($apiRoutesPath)) {
            $existingRoutes = File::get($apiRoutesPath);
            if (strpos($existingRoutes, $routeContent) !== false) {
                $this->info('The custom route already exists.');

                return;
            }
        }
        // endregion

        File::append($apiRoutesPath, PHP_EOL.$routeContent);

        /*$params = [];
        do {
            $param = $this->ask('Введите ');
            if (!empty($param)) {
                $params[] = $param;
            }
        } while (!empty($param));*/

        /*        $controllerStub = File::get(base_path('resources/stubs/controller.stub'));
                $vueStub = File::get(base_path('resources/stubs/vue.stub'));

                // Создание модели
                Artisan::call('make:model', ['name' => $modelName]);

                // Создание миграции
                Artisan::call('make:migration', ['name' => 'create_' . strtolower($modelName) . '_table']);

                // Создание контроллера
                $controllerStub = File::get(base_path('resources/stubs/controller.stub'));
                $controllerPath = app_path("Http/Controllers/{$modelName}Controller.php");
                File::put($controllerPath, str_replace('{{modelName}}', $modelName, $controllerStub));

                // Создание Vue компонента
                $vuePath = resource_path("js/components/{$modelName}Table.vue");
                File::put($vuePath, $vueStub);

                $this->info('CRUD generated successfully!');*/
    }
}
