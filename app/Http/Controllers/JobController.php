<?php

namespace App\Http\Controllers;

use App\Dto\JobData;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\StoreJobRequest;
use Illuminate\Support\Collection;

class JobController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request): JsonResponse
    {   
        $jobs = JobData::collect($request->validated()['scrape'], Collection::class);

        $jobs->each(function  (JobData $job) {
            $job->save()->dispach();
        });

        return $this->sendSuccess($jobs);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        $job = JobData::find($request->route('job'));

        if (is_null($job)) {
            return $this->sendError(result: [], message: 'Job not found', status: 404);
        }

        return $this->sendSuccess($job);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {

        $deleted = JobData::delete($request->route('job'));

        if (! $deleted) {
            return $this->sendError(result: [], message: 'Job not found', status: 404);
        }

        return $this->sendSuccess('Job has been deleted');
    }
}
