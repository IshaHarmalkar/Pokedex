<?php

use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create test Pokemon data
    Pokemon::factory()->create([
        'name' => 'pikachu',
        'pokedex_number' => 25,
        'types' => ['electric'],
        'stats' => [
            'hp' => 35,
            'attack' => 55,
            'defense' => 40,
            'special_attack' => 50,
            'special_defense' => 50,
            'speed' => 90,
        ],
        'height' => 0.4,
        'weight' => 6.0,
        'base_experience' => 112,
    ]);

    Pokemon::factory()->create([
        'name' => 'charizard',
        'pokedex_number' => 6,
        'types' => ['fire', 'flying'],
        'stats' => [
            'hp' => 78,
            'attack' => 84,
            'defense' => 78,
            'special_attack' => 109,
            'special_defense' => 85,
            'speed' => 100,
        ],
        'height' => 1.7,
        'weight' => 90.5,
        'base_experience' => 267,
    ]);

    Pokemon::factory()->create([
        'name' => 'bulbasaur',
        'pokedex_number' => 1,
        'types' => ['grass', 'poison'],
        'stats' => [
            'hp' => 45,
            'attack' => 49,
            'defense' => 49,
            'special_attack' => 65,
            'special_defense' => 65,
            'speed' => 45,
        ],
        'height' => 0.7,
        'weight' => 6.9,
        'base_experience' => 64,
    ]);
});

describe('Pokemon Index', function () {
    it('returns paginated pokemon list with default pagination', function () {
        $response = $this->getJson('/api/pokemon');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'pokedex_number',
                        'types',
                        'stats',
                        'height',
                        'weight',
                        'base_experience',
                    ],
                ],
                'current_page',
                'per_page',
                'total',
            ])
            ->assertJsonPath('per_page', 20);
    });

    it('returns paginated pokemon list with custom per_page parameter', function () {
        $response = $this->getJson('/api/pokemon?per_page=5');

        $response->assertStatus(200)
            ->assertJsonPath('per_page', 5);
    });

    it('returns all created pokemon in the list', function () {
        $response = $this->getJson('/api/pokemon');

        $response->assertStatus(200)
            ->assertJsonPath('total', 3);
    });
});

describe('Pokemon Show', function () {
    it('returns pokemon by pokedex number', function () {
        $response = $this->getJson('/api/pokemon/25');

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'pikachu',
                'pokedex_number' => 25,
                'types' => ['electric'],
            ]);
    });

    it('returns pokemon by name', function () {
        $response = $this->getJson('/api/pokemon/charizard');

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'charizard',
                'pokedex_number' => 6,
                'types' => ['fire', 'flying'],
            ]);
    });

    it('is case insensitive for pokemon names', function () {
        $response = $this->getJson('/api/pokemon/PIKACHU');

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'pikachu',
                'pokedex_number' => 25,
            ]);
    });

    it('returns 404 when pokemon not found', function () {
        $response = $this->getJson('/api/pokemon/999');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Pokemon not found',
            ]);
    });

    it('returns 404 for non-existent pokemon name', function () {
        $response = $this->getJson('/api/pokemon/nonexistent');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Pokemon not found',
            ]);
    });
});

describe('Pokemon Search', function () {
    it('searches pokemon by name', function () {
        $response = $this->getJson('/api/pokemon/search?q=pika');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'name' => 'pikachu',
                    'pokedex_number' => 25,
                ],
            ]);
    });

    it('searches pokemon by pokedex number', function () {
        $response = $this->getJson('/api/pokemon/search?q=6');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'name' => 'charizard',
                    'pokedex_number' => 6,
                ],
            ]);
    });

    it('is case insensitive for name search', function () {
        $response = $this->getJson('/api/pokemon/search?q=CHAR');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'name' => 'charizard',
                ],
            ]);
    });

    it('returns multiple results when matching multiple pokemon', function () {
        // Create additional pokemon with 'a' in the name
        Pokemon::factory()->create(['name' => 'abra', 'pokedex_number' => 63]);

        $response = $this->getJson('/api/pokemon/search?q=a');

        $response->assertStatus(200);

        $data = $response->json();
        expect(count($data))->toBeGreaterThan(1);
    });

    it('limits results to 10 pokemon', function () {
        // Create 15 pokemon with similar names
        for ($i = 1; $i <= 15; $i++) {
            Pokemon::factory()->create([
                'name' => "test{$i}",
                'pokedex_number' => 100 + $i,
            ]);
        }

        $response = $this->getJson('/api/pokemon/search?q=test');

        $response->assertStatus(200);
        expect(count($response->json()))->toBeLessThanOrEqual(10);
    });

    it('returns 400 when search query is missing', function () {
        $response = $this->getJson('/api/pokemon/search');

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'Missing search query',
            ]);
    });

    it('returns 400 when search query is empty', function () {
        $response = $this->getJson('/api/pokemon/search?q=');

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'Missing search query',
            ]);
    });

    it('returns 404 when no results found', function () {
        $response = $this->getJson('/api/pokemon/search?q=nonexistentpokemon');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'No results found.',
            ]);
    });
});

describe('Pokemon Random', function () {
    it('returns a random pokemon', function () {
        $response = $this->getJson('/api/pokemon/random');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'pokedex_number',
                'types',
                'stats',
                'height',
                'weight',
                'base_experience',
            ]);

        // Verify it's one of our test Pokemon
        $pokemon = $response->json();
        expect($pokemon['name'])->toBeIn(['pikachu', 'charizard', 'bulbasaur']);
    });

    it('returns different pokemon on subsequent calls', function () {
        // This test has a small chance of failing if random returns same pokemon twice
        // But with 3 pokemon, probability is low enough for testing
        $responses = [];

        for ($i = 0; $i < 10; $i++) {
            $response = $this->getJson('/api/pokemon/random');
            $responses[] = $response->json()['name'];
        }

        // At least 2 different pokemon should appear in 10 calls
        $unique = array_unique($responses);
        expect(count($unique))->toBeGreaterThan(1);
    });

    it('returns 404 when no pokemon exist in database', function () {
        // Clear all pokemon
        Pokemon::query()->delete();

        $response = $this->getJson('/api/pokemon/random');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'No Pokemon found',
                'debug_count' => 0,
            ]);
    });
});

describe('Pokemon Compare', function () {
    it('compares two pokemon by name', function () {
        $response = $this->getJson('/api/pokemon/compare?first=pikachu&second=charizard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'first' => [
                    'id',
                    'name',
                    'pokedex_number',
                    'types',
                    'stats',
                ],
                'second' => [
                    'id',
                    'name',
                    'pokedex_number',
                    'types',
                    'stats',
                ],
            ]);

        $data = $response->json();
        expect($data['first']['name'])->toBe('pikachu');
        expect($data['second']['name'])->toBe('charizard');
    });

    it('compares two pokemon by pokedex number', function () {
        $response = $this->getJson('/api/pokemon/compare?first=25&second=6');

        $response->assertStatus(200);

        $data = $response->json();
        expect($data['first']['pokedex_number'])->toBe(25);
        expect($data['second']['pokedex_number'])->toBe(6);
    });

    it('compares pokemon by mixed identifiers', function () {
        $response = $this->getJson('/api/pokemon/compare?first=pikachu&second=6');

        $response->assertStatus(200);

        $data = $response->json();
        expect($data['first']['name'])->toBe('pikachu');
        expect($data['second']['name'])->toBe('charizard');
    });

    it('returns 400 when first parameter is missing', function () {
        $response = $this->getJson('/api/pokemon/compare?second=charizard');

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'Both first and second Pokemon must be specified.',
            ]);
    });

    it('returns 400 when second parameter is missing', function () {
        $response = $this->getJson('/api/pokemon/compare?first=pikachu');

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'Both first and second Pokemon must be specified.',
            ]);
    });

    it('returns 400 when both parameters are missing', function () {
        $response = $this->getJson('/api/pokemon/compare');

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'Both first and second Pokemon must be specified.',
            ]);
    });

    it('returns 404 when first pokemon not found', function () {
        $response = $this->getJson('/api/pokemon/compare?first=nonexistent&second=pikachu');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'One or both Pokemon not found.',
            ]);
    });

    it('returns 404 when second pokemon not found', function () {
        $response = $this->getJson('/api/pokemon/compare?first=pikachu&second=nonexistent');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'One or both Pokemon not found.',
            ]);
    });

    it('returns 404 when both pokemon not found', function () {
        $response = $this->getJson('/api/pokemon/compare?first=nonexistent1&second=nonexistent2');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'One or both Pokemon not found.',
            ]);
    });

    // Note: The current compare() method has a bug where comparing the same pokemon
    // might work unexpectedly. This test documents the current behavior:
    it('handles comparing same pokemon name', function () {
        $response = $this->getJson('/api/pokemon/compare?first=pikachu&second=pikachu');

        // Current implementation will return 404 because it finds only 1 pokemon
        // but expects exactly 2 different records
        $response->assertStatus(404)
            ->assertJson([
                'error' => 'One or both Pokemon not found.',
            ]);
    });
});

describe('Edge Cases', function () {
    it('handles pokemon with special characters in search', function () {
        // Create a pokemon with special name
        Pokemon::factory()->create([
            'name' => 'mr-mime',
            'pokedex_number' => 122,
        ]);

        $response = $this->getJson('/api/pokemon/search?q=mr-mime');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    });

    it('handles very large pokedex numbers', function () {
        Pokemon::factory()->create([
            'name' => 'largemon',
            'pokedex_number' => 99999,
        ]);

        $response = $this->getJson('/api/pokemon/99999');

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'largemon',
                'pokedex_number' => 99999,
            ]);
    });

    it('handles pokemon with null/empty stats gracefully', function () {
        Pokemon::factory()->create([
            'name' => 'nullmon',
            'pokedex_number' => 9999,
            'stats' => null,
        ]);

        $response = $this->getJson('/api/pokemon/nullmon');

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'nullmon',
                'stats' => null,
            ]);
    });
});
