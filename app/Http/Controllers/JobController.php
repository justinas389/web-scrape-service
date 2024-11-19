<?php

namespace App\Http\Controllers;

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
        foreach ($request->validated()['scrape'] as $scrapeJob) {
            $id = Redis::incr('job:id_counter');
            $scrapeJob['id'] = $id;

            Redis::set('job:' . $id, json_encode($scrapeJob));
            ProcessScrape::dispatch(id: $id, data: $scrapeJob);
        }

        return $this->sendSuccess([
            'job' => json_decode(Redis::get('job:' . $id)),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        $id = $request->route('job');
        $job = Redis::get('job:' . $id);

        if (is_null($job)) {
            return $this->sendError(result: [], message: 'Job not found', status: 404);
        }

        $status = Redis::get('job:' . $id . ':status');
        $data = Redis::get('job:' . $id . ':data');

        return $this->sendSuccess([
            'job' => json_decode($job),
            'status' => $status,
            'data' => json_decode($data),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        $id = $request->route('job');
        $job = Redis::get('job:' . $id);

        if (is_null($job)) {
            return $this->sendError(result: [], message: 'Job not found', status: 404);
        }

        Redis::del('job:' . $id);
        Redis::del('job:' . $id . ':data');
        Redis::del('job:' . $id . ':status');

        return $this->sendSuccess('Job has been deleted');
    }
}
