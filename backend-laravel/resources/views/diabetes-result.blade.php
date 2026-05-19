<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h3 class="text-2xl font-bold mb-4 text-gray-800">AiSclepius Analiza Rizika (Dijabetes)</h3>
                
                @if($risk == 1)
                    <div class="bg-red-50 border-l-4 border-red-500 p-6 mb-6 rounded-r-lg shadow-sm">
                        <p class="text-red-800 font-bold text-lg">⚠️ POVIŠEN RIZIK - Preporuka: Konsultujte endokrinologa</p>
                        <p class="text-red-700 text-sm mt-2">AI model detektuje metaboličke indikatore koji ukazuju na visok rizik od dijabetesa.</p>
                    </div>
                @else
                    <div class="bg-green-50 border-l-4 border-green-500 p-6 mb-6 rounded-r-lg shadow-sm">
                        <p class="text-green-800 font-bold text-lg">✓ NIZAK RIZIK</p>
                        <p class="text-green-700 text-sm mt-2">AI model ne detektuje značajne faktore rizika za razvoj dijabetesa u ovom trenutku.</p>
                    </div>
                @endif

                <div class="bg-gray-50 p-6 rounded-lg mb-6 border border-gray-100">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">📊 Analiza Uticaja - Zašto je AI dao ovakvu procenu?</h4>
                    
                    @php
                        // Mapiranje parametara za Pima Indians Diabetes dataset
                        $features = [
                            'Trudnoće (Pregnancies)', 
                            'Glukoza (Glucose)', 
                            'Krvni pritisak (Blood Pressure)', 
                            'Debljina kože (Skin Thickness)', 
                            'Insulin', 
                            'BMI (Indeks telesne mase)', 
                            'Diabetes Pedigree Funkcija', 
                            'Godine (Age)'
                        ];
                        
                        $shap_array = is_array($shap_values) ? $shap_values : [];
                        $shap_impacts = [];
                        
                        foreach($shap_array as $idx => $value) {
                            if(isset($features[$idx])) {
                                $shap_impacts[] = [
                                    'feature' => $features[$idx],
                                    'value' => (float)$value,
                                    'abs_value' => abs((float)$value),
                                    'input' => $inputs[$idx] ?? 'N/A'
                                ];
                            }
                        }
                        
                        // Sortiranje po jačini uticaja (apsolutna vrednost)
                        usort($shap_impacts, function($a, $b) {
                            return $b['abs_value'] <=> $a['abs_value'];
                        });
                    @endphp
                    
                    <div class="space-y-3">
                        @forelse($shap_impacts as $impact)
                            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="w-full md:w-1/3">
                                    <span class="font-semibold text-gray-800 block">{{ $impact['feature'] }}</span>
                                    <span class="text-xs text-gray-500">Uneto: <strong class="text-gray-700">{{ $impact['input'] }}</strong></span>
                                </div>
                                
                                <div class="w-full md:w-2/3 flex items-center gap-3">
                                    @if($impact['value'] > 0)
                                        <div class="bg-red-500 h-3 rounded-full shadow-sm"></div>
                                        <span class="text-red-600 text-xs font-bold tracking-wide">↑ POVEĆAVA RIZIK (+{{ round($impact['value'], 3) }})</span>
                                    @else
                                        <div class="bg-green-500 h-3 rounded-full shadow-sm"></div>
                                        <span class="text-green-600 text-xs font-bold tracking-wide">↓ SMANJUJE RIZIK ({{ round($impact['value'], 3) }})</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600 text-center py-4 text-sm italic">Detaljna analiza uticaja trenutno nije dostupna.</p>
                        @endforelse
                    </div>
                </div>

               <!-- UNETI PODACI -->
                <div class="bg-blue-50 p-6 rounded-lg mb-6">
                    <h4 class="font-semibold text-gray-800 mb-4">📋 Vaši Uneti Podaci</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                        @foreach($inputs as $idx => $input)
                            @php
                                $feature_name = $features[$idx] ?? "Parametar $idx";
                            @endphp
                            <div class="bg-white p-3 rounded border border-blue-200">
                                <span class="text-gray-700 font-medium">{{ $feature_name }}:</span>
                                <span class="text-blue-600 font-bold">{{ $input }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

             
            </div>
        </div>
    </div>
</x-app-layout>