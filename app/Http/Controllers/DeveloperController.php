<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeveloperRequest;
use App\Http\Requests\UpdateDeveloperRequest;
use App\Models\Developer;
use App\Models\Game;
use Illuminate\Http\JsonResponse;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(Developer::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeveloperRequest $request): JsonResponse
    {
        $newDev = Developer::create($request);

        return new JsonResponse($newDev);
    }

    /**
     * Display the specified resource.
     */
    public function show(Developer $developer): JsonResponse
    {
        return new JsonResponse($developer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeveloperRequest $request, Developer$developer): JsonResponse
    {
        $developer->update($request->all());

        return new JsonResponse($developer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Developer $developer): JsonResponse
    {
        return new JsonResponse(['msg'=>$developer->delete()]);
    }
}
