<?php

namespace App\Jobs;

use App\Models\ProductionProjectLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UpdateServerRefactor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const ProjectConfigUpdateId = 70;

    const FailureStatusId = 3;

    const SuccessStatusId = 4;

    private Collection $updatePackages;

    private ProductionProjectLog $productionProjectLog;

    private Collection $servers;

    /**
     * Create a new job instance.
     */
    public function __construct(Collection $updatePackages, ProductionProjectLog $productionProjectLog)
    {
        $this->updatePackages = $updatePackages;
        $this->productionProjectLog = $productionProjectLog;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {}
}
