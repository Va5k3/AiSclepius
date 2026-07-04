<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Prediction;

class PredictionController extends Controller
{
    private string $urlAPI = "http://ml-service:8000";

    // FORMS - GET REQUESTS
   /* public function heartForm(){
        return view('heart-check'); // sta je view? to je fajl koji se nalazi u resources/views i on se koristi za prikazivanje stranice korisniku. U ovom slucaju, to je fajl heart-check.blade.php
    } OBRISATI
*/         
  /*  public function diabetesForm(){
        return view('diabetes-check');
    } OBRISATI
    */
    // PREDICTIONS - POST REQUESTS


public function predictHeart(Request $request)
{
    
        $age          = (float) $request->input('age', 0);
        $genderText   = $request->input('gender', 'muški');
        $genderValue  = ($genderText === 'muški' || $genderText == '1') ? 1.0 : 0.0;
        $systolic_bp  = (float) $request->input('systolic_bp', 0);
        $diastolic_bp = (float) $request->input('diastolic_bp', 0);
        $cholesterol  = (float) $request->input('cholesterol', 0);
        $bmi          = (float) $request->input('bmi', 0);
        
        // Angular salje true/false ili 1/0 za checkbox-ove
        $smoking      = $request->input('smoking') ? 1.0 : 0.0;
        $family_hist  = $request->input('family_history') ? 1.0 : 0.0;

        $numericData = [
            $age,
            $genderValue,
            1, // Privremena vrednost za indeks koji nedostaje u Angularu
            $systolic_bp,
            $diastolic_bp,
            $cholesterol,
            1,
            $bmi,
            $smoking,
            1,
            $family_hist,
            1,
            1
        ];
    try {
        // Saljemo niz brojeva Python serveru na port 8000
        $mlService = env('ML_SERVICE_URL',$this->urlAPI);
        $response = Http::post($mlService.'/predict-heart', $numericData);

        if ($response->successful()) {
            $risk = $response->json()['risk'];
            $shapValue = $response->json()['shap_values'];
            
            $userID = auth('sanctum')->id() ?:1;
          
            // Čuvanje u bazu podataka
            Prediction::create([

                'user_id' => $userID ,
                'type' => 'heart',
                'input_data' => $numericData,
                'shap_values' => $shapValue,
                'result' => $risk
            ]);

            
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'risk' => $risk,
                    'shap_values' => $shapValue
                ]);
            }

        }

        return response()->json(['success' => false, 'message' => 'Python server je vratio grešku.'], 502);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Greška u komunikaciji: ' . $e->getMessage()], 502);
    }
}

    public function predictDiabetes(Request $request){
        
        $pregnancies   = (float) $request->input('pregnacies', 0);
        $glucose       = (float) $request->input('glucose', 0);
        $bloodPressure = (float) $request->input('bloodPressure', 0);
        $skinThickness = (float) $request->input('skinThickness', 0);
        $insulin       = (float) $request->input('insulin', 0);
        $bmi           = (float) $request->input('bmi', 0);
        $dpf           = (float) $request->input('dpf', 0);
        $age           = (float) $request->input('age', 0);

        $numericData = [
                $pregnancies,
                $glucose,
                $bloodPressure,
                $skinThickness,
                $insulin,
                $bmi,
                $dpf,
                $age
            ];

        try{
            $mlService = env('ML_SERVICE_URL',$this->urlAPI);
            $response = Http::post($mlService.'/predict-diabetes', $numericData);
            if($response->successful()){
                $risk = $response->json()['risk'];
                $shapValue = $response->json()['shap_values'];

                $userID = auth('sanctum')->id() ?:1;
                // cuvanje u Prediction bazu
                Prediction::create([
                    'user_id' => $userID,
                    'type' => 'diabetes',
                    'input_data' => $numericData,
                    'shap_values' => $shapValue,
                    'result' => $risk
                ]);
    
                return response()->json([
                    'success' => true,
                    'risk' => $risk,
                    'shap_values' => $shapValue
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Python server je vratio neispravan odgovor.'], 502);
        }
        catch(\Exception $ex){
            return response()->json(['success' =>false, 'message'=> 'Python server nije dostupan'.$ex->getMessage()],502);
        }



    }


    public function history()
    {


        $userID = auth('sanctum')->id();
        if(!$userID){
            return response()->json([
                'success'=>false,
                'message'=>'Korisnik nije autentifikovan'
            ],401);
        }

        $predictions = Prediction::where('user_id',$userID)
                                        ->orderBy('created_at','desc')->get();
       
        


        $predictions->transform(function ($item) {
            $item->input_data = is_string($item->input_data) ? json_decode($item->input_data) : $item->input_data;
            $item->shap_values = is_string($item->shap_values) ? json_decode($item->shap_values) : $item->shap_values;
            return $item;
        });

        return response()->json(['success' => true,
                'history' => $predictions],200);
    }

   public function show($id){
        $userId = auth('sanctum')->id() ?: (auth()->id() ?: 1);

        $prediction = Prediction::where('id', $id)
                                ->where('user_id', $userId)
                                ->firstOrFail();

        return response()->json([
            'id' => $prediction->id,
            'type' => $prediction->type,
            'risk' => $prediction->result,
            'shap_values' => $prediction->shap_values,
            'input_values' => $prediction->input_data,
        ], 200);
}
    

}
        