<?php

namespace App\Http\Controllers;

use App\Enums\JobStatusEnum;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Jobs\ProcessScrape;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

class JobController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request)
    {   
        foreach ($request->validated() as $scrapeJob) {

            $id = Redis::incr('job:id_counter');
            $key = 'job:' . $id;

            Redis::hset($key, 'id', $id);
            Redis::hset($key, 'url', $scrapeJob['url']);
            Redis::hset($key, 'status', JobStatusEnum::PENDING->value);
            Redis::hset($key, 'selectors', json_encode($scrapeJob['selectors']));

            $scrapeJob['id'] = $id;

            ProcessScrape::dispatch($scrapeJob);
        }

        return ['message' => 'success'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id = $request->route('job');

        return Redis::hgetall('job:' . $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->route('job');

        Redis::del('job:' . $id);

        return true;
    }
}
