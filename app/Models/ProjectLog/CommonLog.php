<?php

namespace App\Models\ProjectLog;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class CommonLog extends Model
{
    use Prunable;

    protected $table = 'dbo.common_log';

    public $timestamps = false;

    protected $primaryKey = 'common_log_id';

    protected $fillable = ['log_level_id', 'project_log_id', 'project_log_stage_id', 'common_log_date', 'common_log_tag', 'common_log_message'];

    /**
     * @return CommonLog|Builder
     */
    public function prunable(): CommonLog|Builder
    {
        return static::where('common_log_date', '<', Carbon::today()->subDays(7));
    }

    protected $dates = ['common_log_date'];

    public function setCommonLogDateAttribute(): void
    {
        $this->attributes['common_log_date'] = Carbon::now()->timezone('Europe/Moscow');
    }


}
