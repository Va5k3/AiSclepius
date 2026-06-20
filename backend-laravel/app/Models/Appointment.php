<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; 

class Appointment extends Model
{
    protected $fillable = ['doctor_id', 'patient_id', 'room_name', 'scheduled_at', 'status']; 

   
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

   
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}