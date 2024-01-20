<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenresRequest;
use App\Http\Requests\UpdateGenresRequest;
use App\Models\Developer;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;

class GenresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(Genre::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGenresRequest $request): JsonResponse
    {
        $newGen= Genre::create($request);

        return new JsonResponse($newGen);
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genres): JsonResponse
    {
        return new JsonResponse($genres);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGenresRequest $request, Genre $genres): JsonResponse
    {
        $genres->update($request->all());

        return new JsonResponse($genres);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genres): JsonResponse
    {
        return new JsonResponse(['msg'=>$genres->delete()]);
    }
}
