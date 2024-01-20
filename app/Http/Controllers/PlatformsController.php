<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlatformsRequest;
use App\Http\Requests\UpdatePlatformsRequest;
use App\Models\Platform;
use Illuminate\Http\JsonResponse;

class PlatformsController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse(Platform::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlatformsRequest $request): JsonResponse
    {
        $newPlm = Platform::create($request);

        return new JsonResponse($newPlm);
    }

    /**
     * Display the specified resource.
     */
    public function show(Platform $platform): JsonResponse
    {
        return new JsonResponse($platform);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlatformsRequest $request, Platform $platform): JsonResponse
    {
        $platform->update($request->all());

        return new JsonResponse($platform);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Platform $plataform): JsonResponse
    {
        return new JsonResponse(['msg'=>$plataform->delete()]);
    }

    public function getGamesByPlatform($platformId)
    {
        $platform = Platform::findOrFail($platformId);
        $games = $platform->games;

        return response()->json($games);
    }

}
