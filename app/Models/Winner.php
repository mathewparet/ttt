<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    /** @use HasFactory<\Database\Factories\WinnerFactory> */
    use HasFactory;

    protected $fillable = ['name', 'grid_size', 'duration', 'matrix'];

    protected $casts = [
        'matrix' => 'array',
    ];
}
