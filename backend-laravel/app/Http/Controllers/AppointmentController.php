<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    //test
    public function index()
    {
        
        return response()->json([
            [
                'id' => 123,
                'room_name' => 'test-soba-123',
                'status' => 'scheduled'
            ]
        ]);
    }

    public function joinRoom($id)
    {
       
        return response()->json([
            'appointment' => [
                'id' => $id,
                'room_name' => 'test-soba-123',
                'status' => 'active'
            ],
            'roomName' => "AiSclepius-SecureRoom-test-soba-123",
            'role' => 'doctor',
            'userName' => 'Test Doktor'
        ]);
    }

    public function finishRoom($id)
    {
       
        return response()->json([
            'success' => true,
            'message' => 'Pregled uspešno završen.'
        ]);
    }
}