<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenitie extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'language_id',
        'icon',
        'title',
    ];
}
