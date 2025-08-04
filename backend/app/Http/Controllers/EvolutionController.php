<?php

namespace App\Http\Controllers;

use App\Models\EvolutionChain;
use App\Models\EvolutionChainPokemon;
use App\Models\Pokemon;

class EvolutionController extends Controller
{
    public function getEvolutionChain($nameOrId)
    {

        $normalized = is_numeric($nameOrId) ? $nameOrId : $this->normalizeName($nameOrId);

        $pokemon = Pokemon::where('name', $normalized)
            ->orWhere('id', $nameOrId)
            ->first();

        if (! $pokemon) {
            return response()->json(['message' => 'Pokemon not found'], 404);
        }

        $evolutionStage = EvolutionChainPokemon::where('pokemon_id', $pokemon->id)->first();

        if (! $evolutionStage) {
            return response()->json(['message' => 'No evolution chain found for this Pokemon'], 404);
        }

        $evolutionChain = EvolutionChain::with(['stages.pokemon'])->find($evolutionStage->evolution_chain_id);

        $chain = $evolutionChain->stages->map(function ($stage) {
            return [
                'name' => $stage->pokemon->name,
                'evolvves_from' => optional($stage->evolvesFrom)->name,
                'min_level' => $stage->min_level,
                'item' => $stage->item,
                'notes' => $stage->notes,
                'order' => $stage->evolution_order,
            ];
        });

        return response()->json([
            'chain_name' => $evolutionChain->name,
            'evolution_chain' => $chain,
        ]);
    }

    private function normalizeName(string $name): string
    {
        $validHyphenatedNames = [
            'ho-oh', 'mr-mime', 'mime-jr', 'type-null', 'tapu-koko', 'tapu-lele',
            'tapu-bulu', 'tapu-fini', 'great-tusk', 'iron-treads', 'iron-valiant',
            'iron-bundle', 'sandy-shocks', 'slither-wing', 'roaring-moon',
            'flutter-mane', 'brute-bonnet', 'scream-tail', 'raging-bolt',
        ];

        if (in_array($name, $validHyphenatedNames)) {
            return $name;
        }

        return explode('-', $name)[0];

    }
}
