<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h3 class="text-2xl font-bold mb-2 text-gray-800">AiSclepius Analiza Rizika</h3>
                
                @if($risk == 1)
                    <div class="bg-red-50 border-l-4 border-red-500 p-6 mb-6">
                        <p class="text-red-800 font-bold text-lg">⚠️ POVIŠEN RIZIK - Preporuka: Konsultovati kardiologa</p>
                        <p class="text-red-700 text-sm mt-2">AI model detektuje indikatore srčanog oboljenja.</p>
                    </div>
                @else
                    <div class="bg-green-50 border-l-4 border-green-500 p-6 mb-6">
                        <p class="text-green-800 font-bold text-lg">✓ NIZAK RIZIK</p>
                        <p class="text-green-700 text-sm mt-2">AI model ne detektuje značajne rizike srčanog oboljenja.</p>
                    </div>
                @endif

                <!-- SHAP VREDNOSTI - Analiza Uticaja -->
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">📊 Analiza Uticaja - Koja Merenja su Bila Ključna?</h4>
                    
                    @php
                        $features = [ // imena parametarara u istom redosledu kao i SHAP vrednosti
                            'Age (Godine)',
                            'Sex (Pol)',
                            'CP (Tip bola)',
                            'Trestbps (Pritisak)',
                            'Chol (Holesterol)',
                            'FBS (Šećer)',
                            'Restecg (EKG)',
                            'Thalach (Maks puls)',
                            'Exang (Angina)',
                            'Oldpeak',
                            'Slope (Nagib)',
                            'CA (Sudovi)',
                            'Thal (Talasemija)'
                        ];
                        
                        // Sortiraj SHAP vrednosti po apsolutnoj vrednosti (najpotenciji uticaj prvi)
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
                        
                        // Sortiraj po apsolutnoj vrednosti descending
                        usort($shap_impacts, function($a, $b) {
                            return $b['abs_value'] <=> $a['abs_value'];
                        });
                    @endphp
                    
                    @forelse($shap_impacts as $impact)
                        <div class="mb-4 p-4 bg-white rounded border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-800">{{ $impact['feature'] }}</span>
                                <span class="text-sm text-gray-600">Vrednosti: <strong>{{ $impact['input'] }}</strong></span>
                            </div>
                            
                            <!-- Bar vizuelizacija uticaja -->
                            <div class="flex items-center gap-2">
                                @if($impact['value'] > 0)
                                    <div class="bg-red-400" style="height: 8px; border-radius: 4px;"></div>
                                    <span class="text-red-600 text-sm font-semibold">↑ POVEĆAVA RIZIK</span>
                                @else
                                    <div class="bg-green-400" style="height: 8px; border-radius: 4px;"></div>
                                    <span class="text-green-600 text-sm font-semibold">↓ SMANJUJE RIZIK</span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500 block mt-1">Uticaj: {{ round(abs($impact['value']), 4) }}</span>
                        </div>
                    @empty
                        <p class="text-gray-600">Analiza nije dostupna.</p>
                    @endforelse
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

                <div class="flex gap-4">
                    <a href="{{ route('heart.check') }}" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">← Novi Test</a>
                    <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Nazad na Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>