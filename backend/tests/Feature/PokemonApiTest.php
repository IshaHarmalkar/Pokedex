<?php

use function Pest\Laravel\getJson;

it('returns a paginated list of pokemons', function () {
    $response = getJson('/api/pokemon');

    $response->assertStatus(200)
        ->assertJsonStructure(['data', 'links']);
});

it('can get a single pokemon by ID', function () {
    $response = getJson('/api/pokemon/1');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'bulbasaur',
            'pokedex_number' => 1,
        ]);
});

it('can get a single pokemon by name', function () {
    $response = getJson('/api/pokemon/pikachu');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'pikachu',
            'pokedex_number' => 25,
        ]);
});

it('returns results for valid search', function () {
    $response = getJson('/api/pokemon/search?q=charizard');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'charizard',
        ]);
});

it('returns not found for invalid search', function () {
    $response = getJson('/api/pokemon/search?q=doesnotexist');

    $response->assertStatus(404)
        ->assertJson([
            'error' => 'No results found.',
        ]);
});

it('returns a random pokemon', function () {
    $response = getJson('/api/pokemon/random');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'name',
            'pokedex_number',
            'types',
            'stats',
        ]);
});

it('compares two pokemons', function () {
    $response = getJson('/api/pokemon/compare?first=bulbasaur&second=charmander');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'first',
            'second',
        ]);
});
