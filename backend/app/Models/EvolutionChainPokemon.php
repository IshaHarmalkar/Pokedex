<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvolutionChainPokemon extends Model
{
    use HasFactory;

    protected $table = 'evolution_chain_pokemon';

    protected $fillable = [
        'evolution_chain_id',
        'pokemon_id',
        'evolves_from_id',
        'evolution_order',
        'min_level',
        'item',
        'notes',

    ];

    public function evolutionChain()
    {
        return $this->belongsTo(EvolutionChain::class);
    }

    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class);
    }

    public function evolvesFrom()
    {
        return $this->belongsTo(Pokemon::class, 'evolves_from_id');
    }
}
