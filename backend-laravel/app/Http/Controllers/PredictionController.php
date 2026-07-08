<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Prediction;

class PredictionController extends Controller
{
    private string $urlAPI = "http://ml-service:8000";

 


public function predictHeart(Request $request)
{
    
    $age      = (float) $request->input('age', 0);
    
    $sexText  = $request->input('sex', 'muški'); 
    $sex      = ($sexText === 'muški' || $sexText == '1') ? 1.0 : 0.0;
    
    $cp       = (float) $request->input('cp', 0);        // Tip bola u grudima (0-3 ili 1-4 zavisno od pripreme)
    $trestbps = (float) $request->input('trestbps', 0);  // Sistolni pritisak u mirovanju
    $chol     = (float) $request->input('chol', 0);      // Holesterol
    
    // Fasting blood sugar (fbs) > 120 mg/dl (1 = true; 0 = false)
    $fbs      = $request->input('fbs') ? 1.0 : 0.0;
    
    $restecg  = (float) $request->input('restecg', 0);   // EKG u mirovanju (0, 1 ili 2)
    $thalach  = (float) $request->input('thalach', 0);   // Maksimalan puls (Max heart rate achieved)
    
    // Angina izazvana vežbanjem (exang: 1 = da; 0 = ne)
    $exang    = $request->input('exang') ? 1.0 : 0.0;
    
    $oldpeak  = (float) $request->input('oldpeak', 0.0); // ST depresija
    $slope    = (float) $request->input('slope', 0);     // Nagib ST segmenta
    $ca       = (float) $request->input('ca', 0);        // Broj obojenih glavnih krvnih sudova (0-3)
    $thal     = (float) $request->input('thal', 0);      // Defekt (3 = normalno; 6 = fiksiran; 7 = reverzibilan)

    $numericData = [
        $age,       
        $sex,       
        $cp,        
        $trestbps, 
        $chol,      
        $fbs,       
        $restecg,   
        $thalach,   
        $exang,     
        $oldpeak,   
        $slope,     
        $ca,        
        $thal       
    ];
    try {
        // Saljemo niz brojeva Python serveru na port 8000
        $mlService = env('ML_SERVICE_URL',$this->urlAPI);
        $response = Http::post($mlService.'/predict-heart', $numericData);

        if ($response->successful()) {
            $risk = $response->json()['risk'];
            $shapValue = $response->json()['shap_values'];
            
            $userID = auth('sanctum')->id() ?:1;
          
            // Cuvanje u bazu podataka
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

      public function historyId($patientId)
    {


       
        if(!$patientId){
              return response()->json([
                'success'=>false,
                'message'=>'Korisnik nije pronadjen'
            ],401);
        }

        $predictions = Prediction::where('user_id',$patientId)->get();
       
        


        $predictions->transform(function ($item) {
            $item->input_data = is_string($item->input_data) ? json_decode($item->input_data) : $item->input_data;
            $item->shap_values = is_string($item->shap_values) ? json_decode($item->shap_values) : $item->shap_values;
            return $item;
        });

        return response()->json([
                'success' => true,
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
        