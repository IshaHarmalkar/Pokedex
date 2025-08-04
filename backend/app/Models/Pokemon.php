<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    //
    use HasFactory;

    protected $table = 'pokemon';

    protected $fillable = [
        'name',
        'pokedex_number',
        'types',
        'stats',
        'sprite_url',
        'official_artwork_url',
        'description',
        'height',
        'weight',
        'base_experience',
    ];

    protected $casts = [
        'types' => 'array',
        'stats' => 'array',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function getStat($statName)
    {
        return $this->stats[$statName] ?? 0;
    }

    public function hasType($type)
    {
        return in_array($type, $this->types);
    }

    public function getTotalStats()
    {
        return array_sum($this->stats);
    }

    public function evolutionLink()
    {
        return $this->hasOne(EvolutionChainPokemon::class, 'pokemon_id');
    }

    public function evolutionChain()
    {
        return $this->hasOneThrough(
            EvolutionChain::class,          // Final model
            EvolutionChainPokemon::class,   // Intermediate model
            'pokemon_id',                   // FK on EvolutionChainPokemon that points to Pokemon
            'id',                           // FK on EvolutionChain that EvolutionChainPokemon uses
            'id',                           // Local key on Pokemon (id)
            'evolution_chain_id'            // FK on EvolutionChainPokemon that points to EvolutionChain
        );
    }
}
