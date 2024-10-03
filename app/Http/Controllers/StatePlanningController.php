<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatePlanning\StatePlanningCreateRequest;
use App\Http\Requests\StatePlanning\StatePlanningUpdateRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\StatePlanning;
use Request;

class StatePlanningController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:state-planning list', ['only' => ['index']]);
        $this->middleware('permission:state-planning show', ['only' => ['show']]);
        $this->middleware('permission:state-planning create', ['only' => ['store']]);
        $this->middleware('permission:state-planning edit', ['only' => ['update']]);
        $this->middleware('permission:state-planning delete', ['only' => ['destroy']]);
    }

    public function index(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(StatePlanning::orderBy('server_status_id')->get());
    }

    /**
     * @throws \Throwable
     */
    public function store(StatePlanningCreateRequest $request): ApiSuccessResponse
    {
        $statePlanning = new StatePlanning($request->validated());
        $statePlanning->saveOrFail();

        return new ApiSuccessResponse($statePlanning);
    }

    public function show(Request $request, int $id): ApiSuccessResponse
    {
        return new ApiSuccessResponse(StatePlanning::find($id));
    }

    public function update(StatePlanningUpdateRequest $request, int $id): ApiSuccessResponse
    {
        $statePlanning = StatePlanning::findOrFail($id);
        $statePlanning->update($request->validated());

        return new ApiSuccessResponse($statePlanning);
    }

    public function destroy(Request $request, int $id): ApiSuccessResponse
    {
        $statePlanning = StatePlanning::findOrFail($id);

        return new ApiSuccessResponse($statePlanning->delete());
    }
}
