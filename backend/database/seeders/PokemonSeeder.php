<?php

namespace Database\Seeders;

use App\Models\Pokemon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class PokemonSeeder extends Seeder
{
    private string $baseUrl = 'https://pokeapi.co/api/v2/';
    // private int $total = 50;

    public function run(): void
    {
        $chunkSize = 100;
        $offset = 0;
        $failedIds = [];

        $this->command->info('Seeding Pokemon data from PokeAPi...');

        while (true) {

            $listResponse = Http::get($this->baseUrl.'pokemon', [
                'limit' => $chunkSize,
                'offset' => $offset,
            ]);

            if (! $listResponse->successful()) {
                $this->command->error("Failed to fetch Pokemon list at offset $offset.");
                break;
            }

            $pokemonList = $listResponse->json()['results'];

            if (empty($pokemonList)) {
                break;
            }

            foreach ($pokemonList as $pokemonData) {
                $pokemonId = $this->extractIdFromUrl($pokemonData['url']);

                // fetch main data
                $detailResponse = Http::get($this->baseUrl."pokemon/{$pokemonId}");
                if (! $detailResponse->successful()) {
                    $this->command->warn("Skipped Pokemon ID $pokemonId (details not found)");

                    continue;
                }
                $details = $detailResponse->json();

                // Fetch species data
                try {
                    $speciesResponse = Http::get($this->baseUrl."pokemon-species/{$pokemonId}");
                    $species = $speciesResponse->successful() ? $speciesResponse->json() : null;

                } catch (\Throwable $e) {
                    $species = null;
                    $this->command->warn("Failed to fetch species data for pokemon ID {$pokemonId}: ".$e->getMessage());
                    \Log::error("Species fetch failed for ID {$pokemonId}: ".$e->getMessage());

                }

                // process data
                $pokemon = $this->transformPokemonData($details, $species);

                // Save to db
                try {
                    Pokemon::updateOrCreate(['pokedex_number' => $pokemon['pokedex_number']], $pokemon);

                    $this->command->info("Seeded Pokemon : {$pokemon['name']}");

                } catch (\Throwable $e) {
                    $failedIds[] = $pokemonId;
                    $this->command->warn("Failed to seed Pokemon ID {$pokemonId}: ".$e->getMessage());
                    \Log::error("Seeder failed for ID {$pokemonId}: ".$e->getMessage());

                    continue;

                }

            }

            $offset += $chunkSize;
            usleep(300000);

        }

        if (! empty($failedIds)) {
            file_put_contents(storage_path('logs/failed_pokemon_ids.json'), json_encode($failedIds, JSON_PRETTY_PRINT));
            $this->command->warn('Failed IDs saved to logs/failed_pokemon_ids.json');

            $this->command->warn('Seeder completed with failures. Skipped IDs: '.implode(', ', $failedIds));
        } else {
            $this->command->info('Seeding completed with no errors.');

        }
    }

    private function extractIdFromUrl(string $url): int
    {
        $parts = explode('/', rtrim($url, '/'));

        return (int) end($parts);
    }

    private function transformStats(array $stats): array
    {
        $transformed = [];

        foreach ($stats as $stat) {
            $statName = $stat['stat']['name'];
            $statvalue = $stat['base_stat'];

            $transformedName = match ($statName) {
                'hp' => 'hp',
                'attack' => 'attack',
                'defense' => 'defense',
                'special-attack' => 'special_attack',
                'special-defense' => 'special_defense',
                'speed' => 'speed',
                default => $statName
            };

            $transformed[$transformedName] = $statvalue;
        }

        return $transformed;
    }

    private function transformPokemonData(array $data, ?array $species = null): array
    {
        $sprites = $data['sprites'];
        $spriteUrl = $sprites['front_default'] ?? null;
        $officialArtwork = $sprites['other']['official-artwork']['front_default'] ?? null;
        $description = $this->extractEnglishDescription($species);

        $types = array_map(fn ($t) => $t['type']['name'], $data['types']);

        $stats = $this->transformStats($data['stats']);

        // $stats = [];
        // foreach($data['stats'] as $stat){
        //     $key = str_replace('-', '_', $stat['stat']['name']);
        //     $stats[$key] = $stat['base_stat'];
        // }

        return [
            'name' => $data['name'],
            'pokedex_number' => $data['id'],
            'types' => $types,
            'stats' => $stats,
            'sprite_url' => $spriteUrl,
            'official_artwork_url' => $officialArtwork,
            'description' => $description,
            'height' => $data['height'] / 10,
            'weight' => $data['weight'] / 10,
            'base_experience' => $data['base_experience'],

        ];
    }

    private function extractEnglishDescription(?array $species): ?string
    {
        if (! $species || ! isset($species['flavor_text_entries'])) {
            return null;
        }

        foreach ($species['flavor_text_entries'] as $entry) {
            if ($entry['language']['name'] == 'en') {
                return preg_replace('/\s+/', ' ', str_replace(["\n", "\f", "\r"], ' ', $entry['flavor_text']));
            }
        }

        return null;

    }
}
