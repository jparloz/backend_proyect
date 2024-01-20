<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequirementRequest;
use App\Http\Requests\UpdateRequirementRequest;
use App\Models\Requirement;
use Illuminate\Http\JsonResponse;

class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(Requirement::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequirementRequest $request): JsonResponse
    {
        $newReq = Requirement::create($request);

        return new JsonResponse($newReq);
    }

    /**
     * Display the specified resource.
     */
    public function show(Requirement $requirement): JsonResponse
    {
        return new JsonResponse($requirement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequirementRequest $request, Requirement $requirement): JsonResponse
    {
        $requirement->update($request->all());

        return new JsonResponse($requirement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requirement $requirement): JsonResponse
    {
        return new JsonResponse(['msg'=>$requirement->delete()]);
    }
}
