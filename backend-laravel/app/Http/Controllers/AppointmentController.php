<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function joinRoom($id)
    {
        // Pronađi pregled, ali dozvoli pristup SAMO lekaru ili pacijentu koji su vezani za taj pregled
       $appointment = Appointment::where('id', $id)
            ->where(function($query) {
                $query->where('doctor_id', auth()->id())
                      ->orWhere('patient_id', auth()->id());
            })->firstOrFail();

        
        // Određivanje uloge ulogovanog korisnika
        $role = (auth()->id() === $appointment->doctor_id) ? 'doctor' : 'patient';
        
        // Ako lekar ulazi, a pregled je tek zakazan, promeni status u "aktivan"
        if ($role === 'doctor' && $appointment->status === 'scheduled') {
            $appointment->update(['status' => 'active']);
            }
            
            return view('video-room', [
                'appointment' => $appointment,
                'roomName' => $appointment->room_name,
                'role' => $role
                ]);
                }
               

    public function finishRoom($id)
    {
        // Samo lekar može zvanično da završi pregled
        $appointment = Appointment::where('id', $id)->where('doctor_id', auth()->id())->firstOrFail();
        $appointment->update(['status' => 'finished']);

        return redirect()->route('dashboard')->with('success', 'Pregled je uspešno završen.');
    }
}