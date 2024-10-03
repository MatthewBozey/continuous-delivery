<?php

namespace App\Jobs;

use App\Helpers\DictionaryCollectorHelper;
use App\Models\DictionaryCheckData;
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

class DictionaryCollector implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $packageTypeId;

    private $projectId;

    private $databaseName;

    private $refDatabase;

    /**
     * Create a new job instance.
     */
    public function __construct(int $packageType, int $projectId, string $databaseName, string $refDatabase)
    {
        $this->packageTypeId = $packageType;
        $this->projectId = $projectId;
        $this->databaseName = $databaseName;
        $this->refDatabase = $refDatabase;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

    }
}
