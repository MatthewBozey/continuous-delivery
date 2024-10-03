<?php

namespace App\Jobs;

use App\Events\UpdatingProductionProjectLog;
use App\Models\ProductionProjectLog;
use App\Models\ProductionProjectLogScript;
use App\Models\ProductionProjectLogScriptError;
use App\Models\ProductionProjectLogServer;
use App\Models\ProductionProjectLogServerError;
use App\Models\UpdateScript;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class UpdateServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const ProjectConfigUpdateId = 70;

    const FailureStatusId = 3;

    const SuccessStatusId = 4;

    private Collection $servers;

    private Collection $updatePackages;

    private ProductionProjectLog $productionProjectLog;

    /**
     * Create a new job instance.
     */
    public function __construct(
        Collection $servers,
        Collection $updatePackages,
        ProductionProjectLog $productionProjectLog,
    ) {
        $this->servers = $servers;
        $this->updatePackages = $updatePackages;
        $this->productionProjectLog = $productionProjectLog;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

    }
}
