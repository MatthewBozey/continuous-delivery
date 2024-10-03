<?php

namespace App\Jobs;

use App\Models\ProjectLog\CommonLog;
use App\Models\ProjectLog\ProjectLog;
use App\Models\ProjectLog\ProjectLogProblem;
use App\Models\ProjectLog\ProjectLogStage;
use App\Models\UpdatePackage;
use App\Models\UpdateScript;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MetadataCollector implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SCRIPT_TYPE = 2;

    const PACKAGE_TYPE = 1;

    const PROJECT_ID = 1;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

    }

    private function logCommon($projectLogId, $projectLogStageId, $message, $logLevel = 2)
    {

    }
}
