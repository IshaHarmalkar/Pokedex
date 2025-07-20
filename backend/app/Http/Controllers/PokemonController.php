<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $pokemons = Pokemon::paginate($perPage);

        return response()->json($pokemons);
    }

    public function show(string $identifier)
    {
        $pokemon = Pokemon::where('pokedex_number', $identifier)
            ->orWhere('name', strtolower($identifier))
            ->first();

        if (! $pokemon) {
            return response()->json(['error' => 'Pokemon not found'], 404);
        }

        return response()->json($pokemon);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (! $query) {
            return response()->json(['error' => 'Missing search query'], 400);
        }

        $pokemons = Pokemon::where('name', 'like', "%{$query}%")
            ->orWhere('pokedex_number', $query)
            ->limit(10)
            ->get();

        if ($pokemons->isEmpty()) {
            return response()->json(['error' => 'No results found.'], 404);
        }

        return response()->json($pokemons);
    }


    public function random()
    {
        $pokemon = Pokemon::inRandomOrder()->first();

        if(! $pokemon){
            return response()->json(['error' => 'No Pokemon found'], 404);
        }

        return response()->json($pokemon);
    }

    public function compare(Request $request)
    {
        $first = $request->query('first');
        $second = $request->query('second');

        if(!$first || !$second)
        {
            return response()->json(['error' => 'Both first and second Pokemon must be specified.'], 400);
        }

        $pokemons = Pokemon::where(function($query) use ($first, $second){
            $query->where('name', strtolower($first))
            ->orWhere('pokedex_number', $first)
            ->orWhere('name', strtolower($second))
            ->orWhere('pokedex_number', $second);
        })->get();

        if($pokemons->count() !== 2){
            return response()->json(['error' => 'One or both Pokemon not found.'], 404);

        }

        return response()->json([
            'first' => $pokemons->first(),
            'second' =>$pokemons->last()
        ]);
    }
}
