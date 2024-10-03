<?php

namespace App\Models\ProjectLog;

use Carbon\Carbon;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class ProjectLog extends Model
{
    use Prunable;

    protected $table = 'dbo.project_log';

    protected $primaryKey = 'project_log_id';

    public $timestamps = false;

    protected $casts = [
        'project_log_id' => 'int',
        'project_config_id' => 'int',
        'project_schedule_id' => 'int',
        'log_level_id' => 'int',
        'project_log_name' => 'string',
        'project_log_start' => 'datetime',
        'project_log_finish' => 'datetime',
        'project_log_status_id' => 'int',
        'project_log_percent' => 'int',
        'project_log_is_release' => 'bool',
    ];

    protected $fillable = [
        'project_config_id',
        'project_schedule_id',
        'log_level_id',
        'project_log_name',
        'project_log_start',
        'project_log_finish',
        'project_log_status_id',
        'project_log_percent',
        'project_log_is_release',
    ];

    public function broadcastChannel(): array
    {
        return [new PrivateChannel('ProjectLog')];
    }

    /**
     * @return Builder|ProjectLog
     */
    public function prunable(): Builder|ProjectLog
    {
        return static::where(function (Builder $query) {
            $query->where('project_log_start', '<', Carbon::today()->subMonth());
            $query->orWhere(function (Builder $subQuery) {
                $subQuery->where(function (Builder $typeQuery) {
                    $typeQuery->where('project_config_id', 72)
                        ->orWhere('project_config_id', 2)
                        ->orWhere('project_config_id', 71);
                })->where('project_log_start', '<', Carbon::today()->subDays(3));
            });
        });
    }


}
