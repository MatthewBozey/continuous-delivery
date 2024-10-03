<?php

namespace App\Models\ProjectLog;

use Illuminate\Database\Eloquent\Model;

class ProjectLogProblem extends Model
{
    protected $table = 'dbo.project_log_problem';

    protected $primaryKey = 'log_problem_id';

    public $timestamps = false;

    protected $fillable = [
        'project_log_id',
        'project_stage_id',
        'problem_type_id',
        'problem_name',
        'object_type',
        'object_line',
        'object_name',
        'problem_text',
    ];
}
