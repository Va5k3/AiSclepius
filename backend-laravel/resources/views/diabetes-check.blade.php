<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Analiza rizika od dijabetesa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('predict.diabetes') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Broj trudnoća (Pregnancies)</label>
                            <input type="number" name="data[]" required class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Glukoza (Glucose)</label>
                            <input type="number" name="data[]" required class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Krvni pritisak (BloodPressure)</label>
                            <input type="number" name="data[]" required class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Debljina kože (SkinThickness)</label>
                            <input type="number" name="data[]" required class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Insulin</label>
                            <input type="number" name="data[]" required class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">BMI (npr. 26.5)</label>
                            <input type="number" step="0.1" name="data[]" required class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">DPF (npr. 0.45)</label>
                            <input type="number" step="0.001" name="data[]" required class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Godine (Age)</label>
                            <input type="number" name="data[]" required class="w-full rounded-md border-gray-300">
                        </div>
                    </div>
                    <button type="submit" class="mt-6 w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700">Analiziraj Dijabetes</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>