<?php

namespace App\Jobs;

use App\Enums\JobStatusEnum;
use App\Services\CrawlerService;
use Crwlr\Crawler\Steps\Html;
use Crwlr\Crawler\Steps\Loading\Http;
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
    public function __construct(
        public int $id,
        public array $data
    ) {}

    /**
     * Execute the job.
     */
    public function handle(CrawlerService $crawler): void
    {
        $crawler->input($this->data['url'])
            ->addStep(Http::get())
            ->addStep(
                Html::each($this->data['selectors']['wrapper'])
                    ->extract($this->data['selectors']['map'])
            );

        foreach ($crawler->run() as $result) {
            $data[] = $result->toArray();
        }

        Redis::set('job:' .  $this->id . ':data', json_encode($data));
        Redis::set('job:' . $this->id . ':status', JobStatusEnum::FINISHED->value);
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'scrape_' . $this->id;
    }
}
