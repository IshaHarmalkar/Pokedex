<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PokemonController extends Controller
{
    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 20);

        $pokemons = Pokemon::select(['id', 'name', 'sprite_url', 'official_artwork_url', 'types', 'pokedex_number'])->paginate($perPage);

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

        $pokemons = Pokemon::whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($query).'%'])
            ->orWhere('pokedex_number', is_numeric($query) ? (int) $query : -1) // safely cast only if numeric
            ->limit(10)
            ->get();

        if ($pokemons->isEmpty()) {
            return response()->json(['error' => 'No results found.'], 404);
        }

        return response()->json($pokemons);
    }

    public function random()
    {
        $count = Pokemon::count();
        Log::info('Pokemon count: '.$count);

        $pokemon = Pokemon::inRandomOrder()->first();
        Log::info('Pokemon found: '.($pokemon ? 'YES' : 'NO'));

        if (! $pokemon) {
            return response()->json([
                'error' => 'No Pokemon found',
                'debug_count' => $count,
            ], 404);
        }

        return response()->json($pokemon);
    }

    public function compare(Request $request)
    {
        $first = $request->query('first');
        $second = $request->query('second');

        if (! $first || ! $second) {
            return response()->json(['error' => 'Both first and second Pokemon must be specified.'], 400);
        }

        $pokemons = Pokemon::where(function ($query) use ($first, $second) {
            $query->where('name', strtolower($first))
                ->orWhere('pokedex_number', $first)
                ->orWhere('name', strtolower($second))
                ->orWhere('pokedex_number', $second);
        })->get();

        if ($pokemons->count() !== 2) {
            return response()->json(['error' => 'One or both Pokemon not found.'], 404);

        }

        return response()->json([
            'first' => $pokemons->first(),
            'second' => $pokemons->last(),
        ]);
    }

    public function byType(string $type, Request $request)
    {
        $limit = $request->input('limit', 20);
        $lastId = $request->input('last_id', 0);

        $pokemons = Pokemon::whereJsonContains('types', strtolower($type))
            ->where('id', '>', $lastId)
            ->orderBy('id')
            ->select(['id', 'name', 'sprite_url', 'official_artwork_url', 'types', 'pokedex_number'])
            ->limit($limit)
            ->get();

        return response()->json([

            'data' => $pokemons,
            'next_last_id' => $pokemons->last()?->id,
            'has_more' => $pokemons->count() === (int) $limit,
        ]);
    }
}
