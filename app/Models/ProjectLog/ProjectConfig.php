<?php

namespace App\Models\ProjectLog;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProjectLog\ProjectConfig
 *
 * @property int $project_config_id
 * @property int $project_id
 * @property string $project_config_name
 * @property string $project_config_title
 * @property string|null $dependency_project_config_ids
 * @property int|null $project_config_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereDependencyProjectConfigIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereProjectConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereProjectConfigName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereProjectConfigTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereProjectConfigTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectConfig whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProjectConfig extends Model
{

    protected $table = 'dbo.project_config';

    protected $primaryKey = 'project_config_id';
}
