<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Models\Pokemon;
use App\Models\EvolutionChain;
use App\Models\EvolutionChainPokemon;

class MissingPokemonSeeder extends Seeder
{

    private string $baseUrl = 'https://pokeapi.co/api/v2/';

    private array $missingPokemonNames = [
        'deoxys', 'giratina', 'shaymin', 'wormadam', 'basculin', 'darmanitan',
        'tornadus', 'thundurus', 'landorus', 'keldeo', 'meloetta', 'meowstic',
        'aegislash', 'pumpkaboo', 'zygarde', 'oricorio', 'lycanroc', 'wishiwashi',
        'minior', 'mimikyu', 'toxtricity', 'eiscue', 'indeedee', 'morpeko',
        'urshifu', 'enamorus', 'oinkologne', 'maushold', 'squawkabilly', 'palafin', 'tatsugiri'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $this->command->info("Seeding missing Pokemon and their evolutions..");

        foreach($this->missingPokemonNames as $name)
        {
            try{
                $response = Http::get($this->baseUrl . "pokemon/{$name}");

                if(!$response->successful())
                {
                    $this->command->warn("Failed to fetch base data for {$name}");
                    continue;
                }

                $data = $response->json();

                $pokemon = Pokemon::updateOrCreate([
                    'name' => $data['name']
                ],
                [
                    'pokedex_number' => $data['id'],
                    'sprite_url' => $data['sprites']['front_default'],
                    'height' => $data['height'],
                    'weight' => $data['weight'],
                ]);


                $this->command->info("Seeded {$pokemon->name}");

                 $speciesResponse = Http::get($this->baseUrl . "pokemon-species/{$data['id']}");
                if (!$speciesResponse->successful()) {
                    $this->command->warn("Species not found for {$name}");
                    continue;
                }

                $speciesData = $speciesResponse->json();
                $chainUrl = $speciesData['evolution_chain']['url'];
                $chainId = $this->extractIdFromUrl($chainUrl);

                $chainResponse = Http::get($chainUrl);
                if(!$chainResponse->successful()){
                    $this->command->warn("Evolution chain not found for {$name}");
                    continue;
                }

                $chainData = $chainResponse->json();

                $evolutionChain = EvolutionChain::updateOrCreate([
                    'name' => 'Chain #' .$chainId,
                ]);

                $this->processChain($chainData['chain'], $evolutionChain->id);

                usleep(250000);

               }

            catch(\Throwable $e)
            {
                Log::error("Failed for {$name} : " . $e->getMessage());
                $this->command->error("Error while processing {$name}");
            }
        }

        $this->command->info("Completed seeding missing Pokemon and their evolutions.");





    }

    private function processChain(array $chainNode, int $chainId, ?int $evolvesFromId = null, int $order = 1):void{


        $name = $chainNode['species']['name'];
        $pokemon =  Pokemon::where('name', $name)->first();

        if(!$pokemon){
            $this->command->warn("SKipping unknown Pokemon in chian: {$name}");
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

        foreach($chainNode['evolves_to'] as $nextNode) {
            $this->processChain($nextNode, $chainId, $pokemon->id, $order + 1);
        }

    }



    private function extractIdFromUrl(string $url): int{
        return (int) collect(explode('/', rtrim($url, '/')))->last();
    }
}
