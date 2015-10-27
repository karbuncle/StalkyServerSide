<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    
    protected $table = 'ratings';
    const RATING_COLUMN_PREFICES = 'rating_';
    
}