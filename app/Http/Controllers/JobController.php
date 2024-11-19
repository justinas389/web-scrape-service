<?php

namespace App\Http\Controllers;

use App\Enums\JobStatusEnum;
use App\Http\Requests\StoreJobRequest;
use App\Jobs\ProcessScrape;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class JobController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request): JsonResponse
    {   
        $jobs = [];

        foreach ($request->validated()['scrape'] as $scrapeJob) {
            $job = [
                'id' => Redis::incr('job:id_counter'),
                'url' => $scrapeJob['url'],
                'selectors' => $scrapeJob['selectors'],
                'status' => JobStatusEnum::PENDING->value
            ];

            Redis::set('job:' . $job['id'], json_encode($job));

            ProcessScrape::dispatch($job);

            $jobs[] = $job;
        }

        return $this->sendSuccess($jobs);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        $job = Redis::get('job:' . $request->route('job'));

        if (is_null($job)) {
            return $this->sendError(result: [], message: 'Job not found', status: 404);
        }

        return $this->sendSuccess(json_decode(Redis::get('job:' . $request->route('job'))));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        $job = Redis::get('job:' . $request->route('job'));

        if (is_null($job)) {
            return $this->sendError(result: [], message: 'Job not found', status: 404);
        }

        Redis::del('job:' . $request->route('job'));

        return $this->sendSuccess('Job has been deleted');
    }
}
