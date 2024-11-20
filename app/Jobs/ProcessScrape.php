<?php

namespace App\Jobs;

use App\Dto\JobData;
use App\Enums\JobStatusEnum;
use App\Services\CrawlerService;
use Crwlr\Crawler\Steps\Html;
use Crwlr\Crawler\Steps\Loading\Http;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;

class ProcessScrape implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public JobData $jobData) {}

    /**
     * Execute the job.
     */
    public function handle(CrawlerService $crawler): void
    {
        $crawler->input($this->jobData->url)
            ->addStep(Http::get())
            ->addStep(
                Html::each($this->jobData->selectors->wrapper)
                    ->extract($this->jobData->selectors->map)
            );

        foreach ($crawler->run() as $result) {
            $data[] = $result->toArray();
        }

        if (!empty($data)) {
            $this->jobData->data = $data;
        }

        $this->jobData->status = JobStatusEnum::FINISHED->value;

        $this->jobData->save();
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'scrape_' . $this->jobData->id;
    }
}
