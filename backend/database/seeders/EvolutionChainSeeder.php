<?php

namespace Database\Seeders;

use App\Models\EvolutionChain;
use App\Models\EvolutionChainPokemon;
use App\Models\Pokemon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EvolutionChainSeeder extends Seeder
{
    private string $baseUrl = 'https://pokeapi.co/api/v2/';
    // private int $maxChains = 500;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skipped = [];

        $this->command->info('Seeding evolution chains only for Pokemons..');

        $pokemonList = Pokemon::select('id', 'name', 'pokedex_number')->get();
        $seenChainUrls = [];

        foreach ($pokemonList as $pokemon) {
            try {
                $speciesResponse = Http::get($this->baseUrl."pokemon-species/{$pokemon->pokedex_number}");

                if (! $speciesResponse->successful()) {
                    $this->command->warn("Species not found for {$pokemon->name}");

                    continue;
                }

                $speciesData = $speciesResponse->json();
                $chainUrl = $speciesData['evolution_chain']['url'];

                if (in_array($chainUrl, $seenChainUrls)) {

                    // Already exists
                    continue;
                }

                $seenChainUrls[] = $chainUrl;
                $chainId = $this->extractIdFromUrl($chainUrl);

                $chainResponse = Http::get($chainUrl);
                if (! $chainResponse->successful()) {
                    $this->command->warn("Evolution chain not found for {$pokemon->name}");

                    continue;
                }

                $chainData = $chainResponse->json();

                $evolutionChain = EvolutionChain::create([
                    'name' => 'Chain #'.$chainId,
                ]);

                $this->processChain($chainData['chain'], $evolutionChain->id, null, 1, $skipped);

                $this->command->info("Seeded evolution chain for {$pokemon->name}");

                usleep(250000); // 250mx interval

            } catch (\Throwable $e) {
                Log::error("failed for {$pokemon->name}: ".$e->getMessage());
                $this->command->error("Error with {$pokemon->name}");

            }

        }

        $this->command->info('Finished seeding evolution chain for local Pokemon.');
        $totalChains = EvolutionChain::count();
        $this->command->info("Total evolution chains in DB: $totalChains");

        if (! empty($skipped)) {
            $this->command->warn('Skipped Pokemon: '.implode(', ', $skipped));
        }
    }

    private function processChain(array $chainNode, int $chainId, ?int $evolvesFromId = null, int $order = 1, array &$skipped = []): void
    {
        $name = $chainNode['species']['name'];

        $pokemon = Pokemon::where('name', $name)->first();

        if (! $pokemon) {
            $this->command->warn("Skipping unkown Pokemon: {$name}");
            $skipped[] = $name;

            return;
        }

        $details = $chainNode['evolution_details'][0] ?? [];

        EvolutionChainPokemon::updateOrCreate([
            'evolution_chain_id' => $chainId,
            'pokemon_id' => $pokemon->id,
        ], [
            'evolves_from_id' => $evolvesFromId,
            'evolution_order' => $order,
            'min_level' => $details['min_level'] ?? null,
            'item' => $details['item']['name'] ?? null,
            'notes' => $details['trigger']['name'] ?? null,
        ]);

        foreach ($chainNode['evolves_to'] as $nextNode) {
            $this->processChain($nextNode, $chainId, $pokemon->id, $order + 1, $skipped);
        }
    }

    private function extractIdFromUrl(string $url): int
    {
        $parts = explode('/', rtrim($url, '/'));

        return (int) end($parts);
    }
}
