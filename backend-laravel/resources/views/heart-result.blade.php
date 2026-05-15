<x-app-layout>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                <h3 class="text-lg font-bold mb-4">AiAsclepius Procena:</h3>
                
                @if($risk == 1)
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <p class="font-bold">VISOK RIZIK</p>
                        <p>AI model detektuje indikatore srčanog oboljenja.</p>
                    </div>
                @else
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                        <p class="font-bold">NIZAK RIZIK</p>
                        <p>AI model ne detektuje značajne rizike.</p>
                    </div>
                @endif

                <a href="{{ route('heart.check') }}" class="mt-6 inline-block text-indigo-600 hover:underline">Nazad na test</a>
            </div>
        </div>
    </div>
</x-app-layout>