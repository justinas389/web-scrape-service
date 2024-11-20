<?php
namespace App\Dto;

use App\Dto\SelectorData;
use App\Jobs\ProcessScrape;
use App\Enums\JobStatusEnum;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Redis;

class JobData extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $status = '',
        public string $url,
        public SelectorData $selectors,
        public array $data = [],
    ) {
        $this->id = $this->id ?? Redis::incr('job:id_counter');
        $this->status = $this->status ?? JobStatusEnum::PENDING->value;
    }

    /**
     * Save Job Data to Redis storage
     *
     * @return $this
     */
    public function save(): JobData
    {
        Redis::set('job:' . $this->id, $this->toJson());

        return $this;
    }

    /**
     * Add job to queue
     *
     * @return $this
     */
    public function dispach(): JobData
    {
        ProcessScrape::dispatch($this);

        return $this;
    }

    /**
     * Find job by id
     *
     * @return 
     */
    public static function find(int $id): ?JobData
    {
        return Redis::get('job:' . $id) ? JobData::from(Redis::get('job:' . $id)) : null;
    }

    /**
     * Find job by id
     *
     * @return 
     */
    public static function delete(int $id): bool
    {
        return Redis::get('job:' . $id) ? Redis::del('job:' . $id) : false;
    }
}