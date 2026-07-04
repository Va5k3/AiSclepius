<?php

namespace App\Http\Controllers;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    //uzimanje beleski i lekova za pacijenta
    public function show($patientId){
    // Tražimo karton samo za ovog pacijenta
    $record = MedicalRecord::where('patient_id', $patientId)->first();

    if (!$record) {
        return response()->json([
            'success' => true,
            'data' => [
                'notes' => '',
                'medications' => []
            ]
        ]);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'notes' => $record->notes ?? '',
            'medications' => $record->medications ?? []
        ]
    ]);
}
    //cuvanje beleski i lekova
    public function storeOrUpdate(Request $request, $patientId){
    $doctorId = auth('sanctum')->id();

        $record = MedicalRecord::updateOrCreate(
            ['doctor_id' => $doctorId, 'patient_id' => $patientId],
            [
                'notes' => $request->input('notes'),
                'medications' => $request->input('medications') // Angular šalje niz stringova
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Karton uspešno ažuriran.',
            'data' => $record
        ]);
    }





    





}
