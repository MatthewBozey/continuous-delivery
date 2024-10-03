<?php

namespace App\Models\ProjectLog;

use Illuminate\Database\Eloquent\Model;

class ProjectStage extends Model
{
    protected $table = 'dbo.project_stage';

    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'stage_name',
        'stage_timeout',
        'stage_class',
        'stage_source_code',
        'is_deleted',
    ];

    protected $casts = [
        'project_id' => 'integer',
        'stage_name' => 'string',
        'stage_timeout' => 'integer',
        'stage_class' => 'string',
        'stage_source_code' => 'string',
        'is_deleted' => 'boolean',
    ];
}
