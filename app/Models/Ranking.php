<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    /** @use HasFactory<\Database\Factories\RankingFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
