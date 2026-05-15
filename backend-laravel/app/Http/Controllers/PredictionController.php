<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PredictionController extends Controller
{

    public function heartForm(){
        return view('heart-check'); // sta je view? to je fajl koji se nalazi u resources/views i on se koristi za prikazivanje stranice korisniku. U ovom slucaju, to je fajl heart-check.blade.php
    }
    
    public function predictHeart(Request $request)
    {
        // kupimo podatke iz forme
        $data = $request->input('data'); // data je ime inputa u formi, to je niz koji sadrzi sve podatke koje je korisnik uneo u formu
        $numericData = array_map(function($value) {
            return is_numeric($value) ? (float)$value : 0;
        }, $data); 
    
        $response = Http::post('http://localhost:8000/predict-heart', $numericData);

        if ($response->successful()){
            $risk = $response->json()['risk']; 
            return view('heart-result', ['risk' => $risk]); // prikazujemo stranicu sa rezultatom, to je fajl heart-result.blade.php, i prosledju
        }
    
        return "Greska: Python server nije dostupan"; 
    }



}
