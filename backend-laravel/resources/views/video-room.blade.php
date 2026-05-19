<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🩺 Telemedicina: Uživo Konsultacija
            </h2>
            @if($role == 'doctor')
                <form action="{{ route('video.finish', $appointment->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition shadow-md text-sm">
                        🛑 Završi Pregled i Poziv
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-200">
                
               <div id="jitsi-container" class="w-full bg-gray-950 rounded-2xl overflow-hidden shadow-inner border border-gray-300" style="height: 650px !important; min-height: 650px !important;">
                </div>

                <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-3 text-sm text-gray-500 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="flex items-center gap-2">
                        <span>🔒 Veza je enkriptovana i potpuno besplatna (Jitsi Open-Source).</span>
                    </p>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition text-xs">
                        Izađi na Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://meet.jit.si/external_api.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const domain = "meet.jit.si";
            
            // Pravimo potpuno jedinstveno ime sobe da nam niko ne upadne
            const roomName = "AiSclepius-SecureRoom-{{ $roomName }}";
            
            const options = {
                roomName: roomName,
                width: "100%",
                height: "100%",
                parentNode: document.querySelector('#jitsi-container'),
                userInfo: {
                    displayName: "{{ auth()->user()->name }}" // Automatski povlači ime ulogovanog korisnika
                },
                configOverwrite: {
                    startWithAudioMuted: false,
                    startWithVideoMuted: false,
                    disableDeepLinking: true, // Dozvoljava mobilnim telefonima da otvore u browseru bez aplikacije
                    prejoinPageEnabled: false // Preskače ekran za proveru kamere, odmah ulazi u razgovor
                },
                interfaceConfigOverwrite: {
                    // Selektujemo samo najvažnija dugmad za čist i profesionalan medicinski izgled
                    TOOLBAR_BUTTONS: [
                        'microphone', 'camera', 'fullscreen', 'hangup', 'profile', 'chat'
                    ],
                }
            };

            // Pokretanje Jitsi interfejsa
            const api = new JitsiMeetExternalAPI(domain, options);

            // Ako korisnik klikne crvenu slušalicu unutar videa, vrati ga na Dashboard
            api.addEventListener('videoConferenceLeft', function() {
                window.location.href = "{{ route('dashboard') }}";
            });
        });
    </script>
</x-app-layout>