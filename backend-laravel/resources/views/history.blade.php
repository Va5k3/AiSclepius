<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Istorija Vaših AI Pregleda
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if($predictions->isEmpty())
                    <p class="text-gray-500 text-center py-4">Nemate zabeleženih pregleda do sada.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tip Analize</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uneti Podaci (Prva 3 parametra)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">AI Rezultat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($predictions as $prediction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $prediction->created_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold uppercase">
                                            {{ $prediction->type == 'heart' ? '❤️ Srce' : '🩸 Dijabetes' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            Godine: {{ $prediction->input_data[0] ?? '/' }}, 
                                            Pol/Trudnoće: {{ $prediction->input_data[1] ?? '/' }}, 
                                            Pritisak/Glukoza: {{ $prediction->input_data[2] ?? '/' }}...
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($prediction->result == 1)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Povišen Rizik
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Nizak Rizik
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>