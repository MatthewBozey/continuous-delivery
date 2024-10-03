<?php

namespace App\Models\ProjectLog;

use Illuminate\Database\Eloquent\Model;

class ProjectLogStage extends Model
{
    protected $table = 'dbo.project_log_stage';

    public $timestamps = false;

    protected $primaryKey = 'project_log_stage_id';

    protected $fillable = [
        'project_log_id',
        'project_stage_id',
        'stage_status_id',
        'project_log_stage_date_begin',
        'project_log_stage_date_end',
        'project_log_stage_message',
    ];

    protected $casts = [
        'project_log_id' => 'integer',
        'project_stage_id' => 'integer',
        'stage_status_id' => 'integer',
        'project_log_stage_date_begin' => 'datetime',
        'project_log_stage_date_end' => 'datetime',
        'project_log_stage_message' => 'string',
    ];
}
