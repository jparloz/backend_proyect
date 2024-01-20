<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = '';

        if ($request->has('name')) {
            $query = $request->get('name');
        }

        $game = Game::where('name', 'ilike', '%' . $query . '%')->paginate();
        $review = Review::where('name', 'ilike', '%' . $query . '%')->paginate();

        $results = [
            'game' => $game,
            'review' => $review
        ];

        return new JsonResponse($results);
    }

}
