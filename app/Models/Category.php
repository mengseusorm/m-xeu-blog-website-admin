<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string', 
    ];
    protected $fillable = [
        'name',
        'slug',
        'description', 
    ];
}
