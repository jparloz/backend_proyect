<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = '';
        $order = 'ASC';

        if ($request->has('name')) {
            $query = $request->get('name');
        }

        $review = Review::where('name', 'ilike', '%' . $query . '%')
            ->orderBy('created_at', $order)
            ->paginate();

        return new JsonResponse($review);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $newRvw = Review::create($request);

        return new JsonResponse($newRvw);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review): JsonResponse
    {
        return new JsonResponse($review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $review->update($request->all());

        return new JsonResponse($review);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): JsonResponse
    {
        return new JsonResponse(['msg'=>$review->delete()]);
    }

    public function getUserReviews($userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        $reviews = Review::where('user_id', $user->id)->get();

        return new JsonResponse($reviews);
    }

    public function getGameReviews($gameId): JsonResponse
    {
        $reviews = Review::where('game_id', $gameId)->inRandomOrder()->limit(5)->get();

        return new JsonResponse($reviews);
    }

    public function getRandomReviews(): JsonResponse
    {
        $reviews = Review::inRandomOrder()->limit(5)->get(['image', 'review']);

        return new JsonResponse($reviews);
    }
}
