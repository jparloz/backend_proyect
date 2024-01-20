<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Game;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameController extends Controller
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

        $games = Game::where('name', 'ilike', '%' . $query . '%')
            ->orderBy('created_at', $order)
            ->paginate();

        return new JsonResponse($games);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameRequest $request): JsonResponse
    {
        $newGam = Game::create($request);

        return new JsonResponse($newGam);
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game): JsonResponse
    {
        $game->load('platforms', 'genres', 'developer', 'requirement', 'comments');

        $ratings = $game->comments->pluck('rating')->filter();

        $ourRating = $ratings->isNotEmpty() ? round($ratings->avg(), 2) : null;

        $game->setAttribute('our_rating', $ourRating);

        return new JsonResponse($game);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game): JsonResponse
    {
        $game->update($request->all());

        return new JsonResponse($game);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game): JsonResponse
    {
        return new JsonResponse(['msg'=>$game->delete()]);
    }
    public function getRandomGames(): JsonResponse
    {
        $games = Game::with('developer', 'requirement')->inRandomOrder()->limit(5)->get();

        return new JsonResponse($games);
    }

    public function getCommentsWithUser(Request $request, Game $game): JsonResponse
    {
        $comments = $game->comments()
            ->orderBy('created_at', 'asc')
            ->with('user:id,name')
            ->get()
            ->each(function ($comment) {
                $comment->makeHidden('game_id', 'user_id');
            });

        return new JsonResponse($comments);
    }

    public function getTopRatedGames():JsonResponse
    {
        $topRatedGames = Game::with(['developer','comments.user'])
            ->select('games.*')
            ->selectRaw('COALESCE(AVG(comments.rating), 0) as average_rating')
            ->leftJoin('comments', 'games.id', '=', 'comments.game_id')
            ->leftJoin('developers', 'games.developer_id', '=', 'developers.id')
            ->groupBy('games.id')
            ->orderByDesc('rating')
            ->limit(10)
            ->get(['games.*', 'developers.name as developer_name']);

        return new JsonResponse($topRatedGames);
    }

    public function getGamesByGenre($genreId): JsonResponse
    {
        $genre = Genre::findOrFail($genreId);

        $games = $genre->games()
            ->with(['developer', 'requirement'])
            ->get();

        $games->each(function ($game) {
            $game->makeHidden('developer_id', 'requirement_id');
        });

        return new JsonResponse($games);
    }
    public function searchGame(Request $request): JsonResponse
    {
        $query = '';
        $order = 'ASC';

        if ($request->has('name')) {
            $query = $request->get('name');
        }

        $games = Game::where('name', 'ilike', '%' . $query . '%')
            ->orderBy('created_at', $order)
            ->get();

        return new JsonResponse($games);
    }
}
