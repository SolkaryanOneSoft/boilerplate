<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class OptimizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function handle(): void
    {
        $optimizerChain = OptimizerChainFactory::create();
        $optimizerChain->optimize(storage_path('app/public/' . $this->path));
    }
}
