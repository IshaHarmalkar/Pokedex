<?php

use App\Http\Controllers\EvolutionController;
use App\Http\Controllers\PokemonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pokemon', [PokemonController::class, 'index']);

// the user can pass id or name

Route::get('/pokemon/search', [PokemonController::class, 'search']);

Route::get('/pokemon/random', [PokemonController::class, 'random']);

Route::get('/pokemon/compare', [PokemonController::class, 'compare']);

Route::get('/pokemon/{identifier}', [PokemonController::class, 'show']);

Route::get('/pokemon/type/{type}', [PokemonController::class, 'byType']);

Route::get('/pokemon/evolution/{nameOrId}', [EvolutionController::class, 'getEvolutionChain']);
