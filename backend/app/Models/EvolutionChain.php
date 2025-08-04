<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvolutionChain extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function stages()
    {
        return $this->hasMany(EvolutionChainPokemon::class)->orderBy('evolution_order');
    }
}
