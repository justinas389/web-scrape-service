<?php

namespace App\Jobs;

use App\Enums\JobStatusEnum;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;

class ProcessScrape implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Redis::hset("job:$this->data['id']", 'status', JobStatusEnum::PENDING->value);

        
    
        Redis::hset("job:$this->data['id']", 'status', JobStatusEnum::FINISHED->value);
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return "scrape_$this->data['id']";
    }
}
