<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    
    protected $fillable = ['user_id', 'type', 'input_data', 'result'];

    protected $casts = [
        'input_data' => 'array'
    ];


}
