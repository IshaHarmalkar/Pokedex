<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'base_experience'
    ];

    protected $casts = [
        'types' => 'array',
        'stats' => 'array',
        'height' => 'decimal:2',
        'weight' => 'decimal:2'
    ];

    public function getStat($statName)
    {
        return $this->stats[$statName] ?? 0;
    }

    public function hasType($type)
    {
        return in_array($type, $this_>types);
    }

    public function getTotalStats()
    {
        return array_sum($this->stats);
    }

}
