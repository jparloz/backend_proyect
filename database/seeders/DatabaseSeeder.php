<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\Requirement;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $next = 'https://api.rawg.io/api/games?key=4c6a9a1df3534669b59f43972d9aa42b';
        while ($next)
        {
            $result = \Http::get($next)->json();
            $games = $result["results"];
            foreach ($games as $game)
            {
                $gameResult = \Http::get("https://api.rawg.io/api/games/".$game['id']."?key=4c6a9a1df3534669b59f43972d9aa42b")->json();

                $newGame = Game::create(['name' => $gameResult["name"],
                                        'slug'=> $gameResult["slug"],
                                        'release'=>$gameResult["released"],
                                        'background_image'=>$gameResult["background_image"],
                                        'rating'=>$gameResult["rating"],
                                        'age_rating'=>$gameResult["esrb_rating"]["name"] ?? null,
                                        'playtime'=>$gameResult["playtime"],
                                        "description"=>$gameResult["description_raw"],
                                        'meta_rating'=>$gameResult["metacritic"]] ?? null);

                //Comentario para el commit
                $developers = $gameResult["developers"];
                $genres = $gameResult["genres"];
                $platforms = $gameResult["platforms"];

                foreach ($developers as $developer) //Relacion de developers con juegos
                {
                    $bdDev = Genre::where('name', $developer["name"])->first();
                    if (!$bdDev)
                    {
                        $bdDev = Developer::create(['name' => $developer["name"],
                                                    'slug'=>$developer["slug"]]);

                    }
                    $bdDev->games()->save($newGame);
                }
                foreach ($genres as $genre) //Relacion de generos con juegos
                {
                    $bdGenre = Genre::where('name', $genre["name"])->first();
                    if (!$bdGenre)
                    {
                        $bdGenre = Genre::create(['name' => $genre["name"],
                                                'slug'=>$genre["slug"]]);

                    }
                    $newGame->genres()->attach($bdGenre->id);
                }

                foreach ($platforms as $platform)//Relacion de plataformas con juegos
                {
                    $bdPlatform = Platform::where('name', $platform['platform']['name'])->first();
                    if (!$bdPlatform)
                    {
                        $bdPlatform = Platform::create(['name' => $platform['platform']['name'],
                                                        'slug'=>$platform['platform']['slug']]);

                    }
                    if($platform["platform"]["id"] == 4){ //Relacion de requerimientos con juegos
                        $requirements = $platform['requirements'];
                        if(!empty($requirements) && count($requirements)==2){
                            $bdRequirement = Requirement::create(['minimum'=> $requirements['minimum'],
                                                                'recommended'=>$requirements['recommended']]);
                            $bdRequirement->games()->save($newGame);
                        }
                    }
                    $newGame->platforms()->attach($bdPlatform->id);
                }
            }
            $next = $result['next'];
        }
    }
}
