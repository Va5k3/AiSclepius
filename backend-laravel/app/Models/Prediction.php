<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    
    protected $fillable = ['user_id', 'type', 'input_data','shap_values', 'result']; 

    protected $casts = [ 
        'input_data' => 'array',
        'shap_values' => 'array'
    ];


}
