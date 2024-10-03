<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
    public function handle(): void {}
}
