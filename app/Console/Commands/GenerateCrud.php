<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use MongoDB\Driver\Exception\CommandException;

class GenerateCrud extends Command
{
    protected $signature = 'app:generate-crud {name}';

    protected $databaseTable;

    protected $modelName;

    protected $primary;

    protected $table;

    protected string $controllerName;

    protected string $permissionPrefix;

    private mixed $permissionSectionTitle;

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->modelName = $this->argument('name');
        $this->databaseTable = $this->ask('Введите название таблицы с схемой');
        if (! $this->databaseTable || $this->databaseTable === null) {
            throw new CommandException('Некорректно введено название таблицы');
        }
        $this->primary = $this->ask('Введите первичный ключ');
        if (! $this->primary || is_null($this->primary)) {
            throw new CommandException('Некорректно введен первичный ключ');
        }
        $this->permissionSectionTitle = $this->ask('Введите название на русском языке');
        $this->permissionPrefix = Str::snake($this->modelName);
        $this->generateFilter();
        \Artisan::call("make:model {$this->modelName} -mcR --api");
        $this->replaceModelContent();
        $this->controllerName = $this->parseControllerName();
        $this->replaceControllerContent();
        $this->generateVueStorage();
        $this->generateVueFile();
        $this->generatePermission();
    }

    private function parseControllerName(): string
    {
        $controllerName = Str::studly($this->modelName).'Controller';

        return "Http\\Controllers\\{$controllerName}";
    }

    private function replaceControllerContent(): void
    {
        $filePath = app_path(str_replace('\\', '/', $this->controllerName).'.php');
        $content = file_get_contents(base_path('stubs/controller.custom.stub'));
        $modelClass = $this->qualifyModel($this->modelName);
        $replace = [
            '{{ permissionPrefix }}' => $this->permissionPrefix,
            '{{permissionPrefix}}' => $this->permissionPrefix,
            '{{ rootNamespace }}' => base_path(),
            '{{rootNamespace}}' => base_path(),
            '{{ storeRequest }}' => "Store{$this->modelName}Request",
            '{{storeRequest}}' => "Store{$this->modelName}Request",
            '{{ updateRequest }}' => "Update{$this->modelName}Request",
            '{{updateRequest}}' => "Update{$this->modelName}Request",
            '{{ model }}' => class_basename($modelClass),
            '{{model}}' => class_basename($modelClass),
            '{{ orderBy }}' => $this->primary ?: 'id',
            '{{orderBy}}' => $this->primary ?: 'id',
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
            '{{modelVariable}}' => lcfirst(class_basename($modelClass)),
            '{{ class }}' => class_basename($modelClass.'Controller'),
            '{{class}}' => class_basename($modelClass.'Controller'),
            '{{ namespace }}' => str_replace('/', '\\', 'App/Http/Controllers'),
            '{{namespace}}' => str_replace('/', '\\', 'App/Http/Controllers'),

        ];
        $content = str_replace(array_keys($replace), array_values($replace), $content);

        file_put_contents($filePath, $content);
    }

    private function replaceModelContent(): void
    {
        $filePath = app_path("/Models/{$this->modelName}.php");
        $content = file_get_contents(base_path('stubs/model.custom.stub'));
        $modelClass = $this->qualifyModel($this->modelName);
        $replace = [
            '{{ class }}' => class_basename($modelClass),
            '{{class}}' => class_basename($modelClass),
            '{{ namespace }}' => str_replace('/', '\\', 'App/Models'),
            '{{ table }}' => $this->databaseTable,
            '{{table}}' => $this->databaseTable,
            '{{ primary }}' => $this->primary,
            '{{primary}}' => $this->primary,
            '{{ model }}' => $this->modelName,
            '{{model}}' => $this->modelName,

        ];
        $content = str_replace(array_keys($replace), array_values($replace), $content);

        file_put_contents($filePath, $content);
    }

    private function qualifyModel(string $model): string
    {
        $model = ltrim($model, '\\/');
        $model = str_replace('/', '\\', $model);
        $rootNamespace = $this->getLaravel()
            ->getNamespace();
        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return is_dir(app_path('Models')) ? $rootNamespace.'Models\\'.$model : $rootNamespace.$model;
    }

    /**
     * @throws FileNotFoundException
     */
    private function generateFilter(): void
    {
        $content = \File::get(base_path('stubs/').'filter.stub');
        $modelClass = $this->qualifyModel($this->modelName);
        $replace = [
            '{{ class }}' => class_basename($modelClass.'Filter'),
            '{{class}}' => class_basename($modelClass.'Filter'),
        ];
        $content = str_replace(array_keys($replace), array_values($replace), $content);

        \File::put(app_path('Filters/')."{$this->modelName}Filter.php", $content);
    }

    private function generateVueFile(): void
    {
        $content = file_get_contents(base_path('stubs/vue.crud.stub'));
        $replace = [
            '{{ model }}' => lcfirst(Str::camel($this->modelName)),
            '{{model}}' => lcfirst(Str::camel($this->modelName)),
            '{{ model_camel }}' => ucfirst(Str::camel($this->modelName)),
            '{{model_camel}}' => ucfirst(Str::camel($this->modelName)),
            '{{ permissionName }}' => $this->permissionPrefix,
            '{{permissionName}}' => $this->permissionPrefix,
            '{{ apiName }}' => Str::lower(Str::kebab($this->modelName)),
            '{{apiName}}' => Str::lower(Str::kebab($this->modelName)),
            '{{ primary }}' => $this->primary,
            '{{primary}}' => $this->primary,
        ];
        $content = str_replace(array_keys($replace), array_values($replace), $content);
        \File::put(resource_path('/js/pages/')."{$this->modelName}.vue", $content);
    }

    private function generateVueStorage(): void
    {
        $content = file_get_contents(base_path('stubs/vue.storage.stub'));
        $replace = [
            '{{ model }}' => Str::camel($this->modelName),
            '{{model}}' => Str::camel($this->modelName),
            '{{ model_camel }}' => ucfirst(Str::camel($this->modelName)),
            '{{model_camel}}' => ucfirst(Str::camel($this->modelName)),
            '{{ primary }}' => $this->primary,
            '{{primary}}' => $this->primary,
        ];
        $content = str_replace(array_keys($replace), array_values($replace), $content);
        \File::put(resource_path('/js/store/modules/').Str::snake($this->modelName).'.js', $content);
    }

    private function generatePermission(): void
    {
        $content = file_get_contents(base_path('stubs/permission.stub'));
        $replace = [
            '{{ model }}' => ucfirst(Str::camel($this->modelName)),
            '{{model}}' => ucfirst(Str::camel($this->modelName)),
            '{{ permission }}' => $this->permissionPrefix,
            '{{permission}}' => $this->permissionPrefix,
            '{{ section_title }}' => $this->permissionSectionTitle,
            '{{section_title}}' => $this->permissionSectionTitle,
            '{{ model_title }}' => $this->permissionSectionTitle,
            '{{model_title}}' => $this->permissionSectionTitle,
            '{{ section_sysname }}' => Str::snake($this->modelName),
            '{{section_sysname}}' => Str::snake($this->modelName),
        ];
        $content = str_replace(array_keys($replace), array_values($replace), $content);
        \File::put(base_path('/database/seeders/')."{$this->modelName}PermissionSeeder.php", $content);
    }
}
