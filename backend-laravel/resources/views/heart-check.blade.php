<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AiSclepius - Analiza rizika srca') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('predict.heart') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Godine (Age)</label>
                            <input type="number" name="data[]" placeholder="npr. 55" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pol (Sex)</label>
                            <select name="data[]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="1">Muški (1)</option>
                                <option value="0">Ženski (0)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tip bola u grudima (CP: 0-3)</label>
                            <input type="number" name="data[]" min="0" max="3" value="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pritisak u mirovanju (Trestbps)</label>
                            <input type="number" name="data[]" placeholder="npr. 120" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Holesterol (Chol)</label>
                            <input type="number" name="data[]" placeholder="npr. 240" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Šećer > 120 (FBS: 0 ili 1)</label>
                            <select name="data[]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="0">Ne (0)</option>
                                <option value="1">Da (1)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">EKG rezultat (Restecg: 0-2)</label>
                            <input type="number" name="data[]" min="0" max="2" value="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Maks. puls (Thalach)</label>
                            <input type="number" name="data[]" placeholder="npr. 150" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Angina pri vežbi (Exang: 0 ili 1)</label>
                            <select name="data[]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="0">Ne (0)</option>
                                <option value="1">Da (1)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Oldpeak (npr. 1.5)</label>
                            <input type="number" step="0.1" name="data[]" placeholder="0.0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nagib ST segmenta (Slope: 0-2)</label>
                            <input type="number" name="data[]" min="0" max="2" value="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Krvni sudovi - CA (0-3)</label>
                            <input type="number" name="data[]" min="0" max="3" value="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Talasemija (Thal: 1-3)</label>
                            <input type="number" name="data[]" min="1" max="3" value="2" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Predvidi rizik</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>