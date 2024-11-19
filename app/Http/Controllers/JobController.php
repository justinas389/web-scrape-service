<?php

namespace App\Http\Controllers;

use App\Enums\JobStatusEnum;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Jobs\ProcessScrape;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

class JobController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request): JsonResponse
    {   
        foreach ($request->validated()['scrape'] as $scrapeJob) {

            $id = Redis::incr('job:id_counter');

            $key = 'job:' . $id;

            Redis::hset($key, 'id', $id);
            Redis::hset($key, 'url', $scrapeJob['url']);
            Redis::hset($key, 'status', JobStatusEnum::PENDING->value);
            Redis::hset($key, 'selectors', json_encode($scrapeJob['selectors']));

            $scrapeJob['id'] = $id;

            ProcessScrape::dispatch($scrapeJob);
        }

        return response()->json(['message' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        $id = $request->route('job');

        return response()->json([
            'job' => [
                'id' => Redis::hget('job:' . $id, 'id'),
                'url' => Redis::hget('job:' . $id, 'url'),
                'status' => Redis::hget('job:' . $id, 'status'),
                'selectors' => json_decode(Redis::hget('job:' . $id, 'selectors')),
            ],
            'data' => array_map(function ($item) {
                return json_decode($item, true); // Decode JSON into an associative array
            }, Redis::lrange('job:' . $id . ':data', 0, -1))
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        $id = $request->route('job');

        Redis::del('job:' . $id);

        return response()->json(true);
    }
}
