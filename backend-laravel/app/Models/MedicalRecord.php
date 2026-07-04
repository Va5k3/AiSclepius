<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    // Dozvoljavamo upis u ove kolone (rešava Mass Assignment grešku)
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'notes',
        'medications'
    ];

    // Automatski pretvara niz lekova iz Angulara u JSON za bazu i obrnuto
    protected $casts = [
        'medications' => 'array'
    ];
}