<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Category extends Model
{
    use HasFactory, BlameableTrait;

    protected $fillable = [
        'name'
    ];
}
