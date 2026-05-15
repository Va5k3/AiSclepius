<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Prediction;

class PredictionController extends Controller
{

    // FORMS - GET REQUESTS
    public function heartForm(){
        return view('heart-check'); // sta je view? to je fajl koji se nalazi u resources/views i on se koristi za prikazivanje stranice korisniku. U ovom slucaju, to je fajl heart-check.blade.php
    }

    public function diabetesForm(){
        return view('diabetes-check');
    }
    
    // PREDICTIONS - POST REQUESTS

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
            $shapValue = $response->json()['shap_values'];
            // cuvanje u Prediction bazu
            Prediction::create([
                'user_id' => auth()->id(), # id trenutnog ulogovanog korisnika
                'type' => 'heart',
                'input_data' => $numericData,
                'result' => $risk
            ]);


            return view('heart-result', [
                'risk' => $risk,
                'shap_values' => $shapValue,
                'inputs' => $numericData
                ]); // prikazujemo stranicu sa rezultatom, to je fajl heart-result.blade.php, i prosledju
        }
    
        return "Greska: Python server nije dostupan"; 
    }


    public function predictDiabetes(Request $request){
        
        $data = $request->input('data');
        $numericData = array_map(function($val){
            return is_numeric($val) ? (float)$val : 0;
        }, $data);

        $response = Http::post('http://localhost:8000/predict-diabetes', $numericData);

        if($response->successful()){
            $risk = $response->json()['risk'];

            // cuvanje u Prediction bazu
            Prediction::create([
                'user_id' => auth()->id(), # id trenutnog ulogovanog korisnika
                'type' => 'diabetes',
                'input_data' => $numericData,
                'result' => $risk
            ]);



            return view('diabetes-result',['risk' => $risk]);
        }

        return "Greska: Python server nije dostupan";

    }


    public function history()
    {
        $predictions = Prediction::where('user_id',auth()->id())->orderBy('created_at','desc')->get();

        return view('history',compact('predictions'));
    }


}
