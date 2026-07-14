@extends('layouts.app')

@section('title', __('messages.my_routes'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold">{{ __('messages.on_duty') }}</span>
            </div>
            <h1>{{ __('messages.my_routes') }}</h1>
            <p>{{ __('messages.welcome') }}, {{ auth()->user()->name }}.</p>
        </div>
        <a href="{{ route('deliveryboy.routes.create') }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            {{ __('messages.new_route') }}
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-pink-500/10 text-pink-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_routes'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Routes</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-amber-500/10 text-amber-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_routes'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Pending</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-blue-500/10 text-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['in_progress_routes'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">In Progress</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-emerald-500/10 text-emerald-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['completed_routes'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Completed</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Routes List -->
    @forelse($routes as $route)
    <div class="glass-card p-6 mb-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <span class="badge badge-{{ $route->status }}">{{ ucfirst(str_replace('_', ' ', $route->status)) }}</span>
            <div class="text-right">
                <div class="text-xl font-bold text-pink-500">{{ number_format($route->total_km, 1) }} km</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Note: {{ $route->boc ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="flex items-center gap-4 mb-4">
            <div class="text-center flex-1">
                <div class="w-3 h-3 rounded-full bg-emerald-500 mx-auto mb-1"></div>
                <div class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('messages.from') }}</div>
                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $route->from_location === 'Office' ? __('messages.office') : $route->from_location }}</div>
            </div>
            <div class="flex-1 h-0.5 rounded-full bg-gradient-to-r from-emerald-500 to-pink-500 relative">
                <svg class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-4 h-4 text-pink-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </div>
            <div class="text-center flex-1">
                <div class="w-3 h-3 rounded-full bg-pink-500 mx-auto mb-1"></div>
                <div class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">To</div>
                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $route->to_location }}</div>
            </div>
        </div>

        @if($route->stops->count() > 0)
        <div class="mb-4">
            <h4 class="flex items-center gap-1.5 text-xs font-semibold text-pink-500 mb-2 uppercase tracking-wide">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                Pit Stops ({{ $route->stops->count() }})
            </h4>
            <div class="flex flex-wrap gap-2">
                @foreach($route->stops as $stop)
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-pink-500/5 border border-pink-500/10 text-xs font-medium text-gray-700 dark:text-gray-300" id="stop-{{ $stop->id }}">
                    <span class="w-4 h-4 rounded-full bg-pink-500/20 text-pink-500 text-[9px] font-bold flex items-center justify-center">{{ $loop->index + 1 }}</span>
                    {{ $stop->stop_name }} <span class="text-pink-500 font-mono">({{ $stop->stop_km }} km)</span>
                    @if($route->status !== 'completed')
                    <button type="button" onclick="deleteRoutePitStop({{ $route->id }}, {{ $stop->id }})" class="p-0.5 text-gray-400 hover:text-red-500 rounded-full hover:bg-red-500/10 transition-all" title="Delete">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    @endif
                </span>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-3 mt-4 pt-4 border-t border-gray-100 dark:border-white/5">
            @if($route->status === 'pending')
            <form action="{{ route('deliveryboy.routes.status', [$route, 'in_progress']) }}" method="POST" class="flex-1" onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').classList.add('opacity-50', 'cursor-not-allowed');">
                @csrf
                <button type="submit" class="btn btn-primary w-full">{{ __('messages.start_delivery') }}</button>
            </form>
            <a href="{{ route('deliveryboy.routes.edit', $route) }}" class="btn btn-secondary flex-1">{{ __('messages.edit') }}</a>
            @elseif($route->status === 'in_progress')
            <button onclick="openCompleteModal({{ $route->id }})" class="btn btn-primary flex-1 !bg-gradient-to-r !from-emerald-500 !to-emerald-600 !shadow-emerald-500/30">{{ __('messages.complete_delivery') }}</button>
            @elseif($route->status === 'completed')
            <a href="{{ route('deliveryboy.routes.edit', $route) }}" class="btn btn-primary flex-1">{{ __('messages.edit') }}</a>
            @endif
        </div>
    </div>
    @empty
    <div class="glass-card p-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-pink-500/10 flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">No Routes Yet</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Start by creating your first delivery route</p>
        <a href="{{ route('deliveryboy.routes.create') }}" class="btn btn-primary">Create First Route</a>
    </div>
    @endforelse
</div>

<!-- Pit Stop Modal -->
<div id="pitStopModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm hidden">
    <div class="glass-card p-6 w-full max-w-lg mx-4">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.add_pit_stop') }}</h3>
            <button onclick="hidePitStopModal()" class="p-2 text-gray-400 hover:text-pink-500 rounded-xl hover:bg-pink-500/10 transition-all">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="" method="POST" enctype="multipart/form-data" id="pitStopForm">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.stop_name') }}</label>
                    <div class="relative">
                        <input type="text" name="stop_name" required class="glass-input pr-10" id="stopNameModal">
                        <button type="button" onclick="startSpeechToText('stopNameModal')" class="absolute right-2 top-1/2 -translate-y-1/2 audio-btn" title="{{ __('messages.record_audio') }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.km') }}</label>
                    <input type="number" step="0.01" name="stop_km" class="glass-input">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.image') }}</label>
                    <input type="file" name="image" accept="image/*" class="glass-input">
                </div>
                <input type="file" name="audio" id="pitModalAudioInput" class="hidden" accept="audio/*">
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary flex-1">{{ __('messages.add_pit_stop') }}</button>
                    <button type="button" onclick="hidePitStopModal()" class="btn btn-secondary flex-1">{{ __('messages.cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Complete Visit Modal -->
<div id="completeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm hidden">
    <div class="glass-card p-6 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.complete_delivery') }}</h3>
            <button onclick="hideCompleteModal()" class="p-2 text-gray-400 hover:text-pink-500 rounded-xl hover:bg-pink-500/10 transition-all">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="" method="POST" enctype="multipart/form-data" id="completeForm" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').classList.add('opacity-50', 'cursor-not-allowed');">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.km') }} <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="total_km" required class="glass-input" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.boc') }}</label>
                    <input type="text" name="boc" class="glass-input" placeholder="Optional">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.image') }}</label>
                    <input type="file" name="image" accept="image/*" class="glass-input">
                </div>

                <!-- Pit Stops Section -->
                <div class="pt-4 border-t border-gray-100 dark:border-white/5">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('messages.pit_stops') }}</h4>
                        <button type="button" onclick="showPitStopFields()" id="showPitStopBtn" class="text-xs font-semibold text-pink-500 hover:text-pink-600 transition-colors">
                            + {{ __('messages.add_pit_stop') }}
                        </button>
                    </div>

                    <!-- Existing Pit Stops List -->
                    <div id="existingPitStops" class="space-y-2 mb-3"></div>

                    <!-- Add Pit Stop Form (hidden by default) -->
                    <div id="pitStopFields" class="space-y-3 hidden p-3 rounded-xl bg-gray-50 dark:bg-white/5">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.stop_name') }}</label>
                            <div class="relative">
                                <input type="text" name="stop_name" class="glass-input pr-10" id="modalStopName" placeholder="Pit stop name">
                                <button type="button" onclick="startSpeechToText('modalStopName')" class="absolute right-2 top-1/2 -translate-y-1/2 audio-btn" title="{{ __('messages.record_audio') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.km') }}</label>
                            <input type="number" step="0.01" name="stop_km" class="glass-input" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.image') }}</label>
                            <input type="file" name="stop_image" accept="image/*" class="glass-input">
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="addPitStopFromModal()" class="btn btn-primary flex-1 text-xs">{{ __('messages.add_pit_stop') }}</button>
                            <button type="button" onclick="hidePitStopFields()" class="btn btn-secondary flex-1 text-xs">{{ __('messages.cancel') }}</button>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-white/5">
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary flex-1 !bg-gradient-to-r !from-emerald-500 !to-emerald-600 !shadow-emerald-500/30">{{ __('messages.complete_delivery') }}</button>
                        <button type="button" onclick="hideCompleteModal()" class="btn btn-secondary flex-1">{{ __('messages.cancel') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let speechRec = null;
    let audioRecorder = null;
    let audioChunks = [];
    let isRecording = false;

    function startSpeechToText(inputId) {
        const input = document.getElementById(inputId);
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) { return; }
        if (isRecording) { stopSpeechAndAudio(input); return; }
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        speechRec = new SpeechRecognition();
        speechRec.lang = '{{ app()->getLocale() === 'ur' ? 'ur-PK' : 'en-US' }}';
        speechRec.interimResults = false;
        speechRec.continuous = false;
        speechRec.onresult = function(e) { input.value = e.results[0][0].transcript; };
        speechRec.onerror = function() { stopAudioRecording(); isRecording = false; };
        speechRec.onend = function() { stopAudioRecording(); isRecording = false; };
        startAudioRecording();
        speechRec.start();
        isRecording = true;
        input.style.borderColor = '#ec4899';
        input.style.boxShadow = '0 0 15px rgba(236,72,153,0.3)';
    }

    function getAudioMime() {
        var types = ['audio/webm;codecs=opus', 'audio/webm', 'audio/mp4', 'audio/ogg;codecs=opus'];
        for (var i = 0; i < types.length; i++) {
            if (MediaRecorder.isTypeSupported(types[i])) return types[i];
        }
        return 'audio/webm';
    }

    function getAudioExt(mime) {
        if (mime.indexOf('mp4') > -1) return 'mp4';
        if (mime.indexOf('ogg') > -1) return 'ogg';
        return 'webm';
    }

    function startAudioRecording() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) return;
        navigator.mediaDevices.getUserMedia({ audio: true }).then(function(stream) {
            var mimeType = getAudioMime();
            try {
                audioRecorder = new MediaRecorder(stream, { mimeType: mimeType });
            } catch(e) {
                audioRecorder = new MediaRecorder(stream);
                mimeType = audioRecorder.mimeType || 'audio/webm';
            }
            audioChunks = [];
            audioRecorder.ondataavailable = function(e) { if (e.data.size > 0) audioChunks.push(e.data); };
            audioRecorder.onstop = function() {
                stream.getTracks().forEach(function(t) { t.stop(); });
                if (!audioChunks.length) return;
                audioBlob = new Blob(audioChunks, { type: getAudioMime() });
            };
            audioRecorder.start();
        }).catch(function() {});
    }

    function stopAudioRecording() {
        if (audioRecorder && audioRecorder.state !== 'inactive') { audioRecorder.stop(); }
    }

    function stopSpeechAndAudio(input) {
        if (speechRec) { speechRec.stop(); speechRec = null; }
        stopAudioRecording();
        isRecording = false;
        input.style.borderColor = '';
        input.style.boxShadow = '';
        setAudioFile('pitModalAudioInput');
    }

    var audioBlob = null;

    function setAudioFile(inputId) {
        if (!audioChunks.length) return;
        var blob = new Blob(audioChunks, { type: getAudioMime() });
        var ext = getAudioExt(blob.type);
        var file = new File([blob], 'recording.' + ext, { type: blob.type });
        var dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById(inputId).files = dt.files;
    }

    function shareAudioToChat(file) {
        var formData = new FormData();
        formData.append('audio', file);
        formData.append('_token', '{{ csrf_token() }}');
        fetch('{{ route("chat.send") }}', { method: 'POST', body: formData }).catch(function() {});
    }

    var currentRouteId = null;

    function showPitStopModal(routeId) {
        document.getElementById('pitStopForm').action = `/deliveryboy/routes/${routeId}/pit-stop`;
        document.getElementById('pitStopModal').classList.remove('hidden');
    }
    function hidePitStopModal() {
        document.getElementById('pitStopModal').classList.add('hidden');
    }
    function showPitStopFields() {
        document.getElementById('showPitStopBtn').classList.add('hidden');
        document.getElementById('pitStopFields').classList.remove('hidden');
    }
    function hidePitStopFields() {
        document.getElementById('showPitStopBtn').classList.remove('hidden');
        document.getElementById('pitStopFields').classList.add('hidden');
        document.getElementById('modalStopName').value = '';
        document.querySelector('#pitStopFields input[name="stop_km"]').value = '';
        document.querySelector('#pitStopFields input[name="stop_image"]').value = '';
    }
    function addPitStopFromModal() {
        if (!currentRouteId) return;
        var name = document.getElementById('modalStopName').value.trim();
        var km = document.querySelector('#pitStopFields input[name="stop_km"]').value;
        var imageInput = document.querySelector('#pitStopFields input[name="stop_image"]');
        if (!name) { alert('Please enter a stop name.'); return; }
        var submitBtn = document.querySelector('#pitStopFields .btn-primary');
        if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Adding...'; }
        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('stop_name', name);
        if (km) formData.append('stop_km', km);
        if (imageInput.files[0]) formData.append('image', imageInput.files[0]);
        if (audioBlob) {
            var ext = getAudioExt(audioBlob.type);
            var audioFile = new File([audioBlob], 'recording.' + ext, { type: audioBlob.type });
            formData.append('audio', audioFile);
            audioBlob = null;
        }
        fetch('/deliveryboy/routes/' + currentRouteId + '/pit-stop', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(function(r) {
            return r.json().then(function(data) { return { status: r.status, data: data }; });
        })
        .then(function(obj) {
            if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = '{{ __('messages.add_pit_stop') }}'; }
            if (obj.status >= 200 && obj.status < 300 && obj.data.success) {
                hidePitStopFields();
                refreshPitStops();
            } else if (obj.status === 422 && obj.data.errors) {
                var msgs = Object.values(obj.data.errors).flat();
                alert('Validation error(s):\n- ' + msgs.join('\n- '));
            } else {
                alert(obj.data.message || 'Error adding pit stop');
            }
        })
        .catch(function() {
            if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = '{{ __('messages.add_pit_stop') }}'; }
            alert('Network error. Please check your connection and try again.');
        });
    }
    function refreshPitStops() {
        if (!currentRouteId) return;
        fetch('/deliveryboy/routes/' + currentRouteId + '/pit-stops')
            .then(function(r) { return r.json(); })
            .then(function(stops) {
                var container = document.getElementById('existingPitStops');
                container.innerHTML = '';
                if (!stops || stops.length === 0) {
                    container.innerHTML = '<p class="text-xs text-gray-400 dark:text-gray-500">No pit stops added yet</p>';
                    return;
                }
                stops.forEach(function(s) {
                    var el = document.createElement('div');
                    el.className = 'flex items-center justify-between px-3 py-2 rounded-lg bg-gray-50 dark:bg-white/5 text-xs';
                    var imgHtml = s.image_path ? '<img src="/storage/' + s.image_path + '" class="w-8 h-8 rounded-lg object-cover ml-2">' : '';
                    el.innerHTML = '<div class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-pink-500/20 text-pink-500 text-[9px] font-bold flex items-center justify-center flex-shrink-0">' + s.order + '</span><span class="font-medium text-gray-700 dark:text-gray-300">' + s.stop_name + '</span><span class="text-pink-500 font-mono">' + s.stop_km + ' km</span></div><div class="flex items-center gap-1">' + imgHtml + '</div>';
                    container.appendChild(el);
                });
            })
            .catch(function() {});
    }
    function deleteRoutePitStop(routeId, stopId) {
        if (!confirm('Delete this pit stop?')) return;
        fetch('/deliveryboy/routes/' + routeId + '/pit-stop/' + stopId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                var el = document.getElementById('stop-' + stopId);
                if (el) el.remove();
            }
        })
        .catch(function() {});
    }

    function deletePitStop(stopId) {
        if (!currentRouteId || !confirm('Delete this pit stop?')) return;
        fetch('/deliveryboy/routes/' + currentRouteId + '/pit-stop/' + stopId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) refreshPitStops();
        })
        .catch(function() {});
    }

    function openCompleteModal(routeId) {
        currentRouteId = routeId;
        document.getElementById('completeForm').action = `/deliveryboy/routes/${routeId}/complete`;
        document.getElementById('completeModal').classList.remove('hidden');
        document.getElementById('showPitStopBtn').classList.remove('hidden');
        document.getElementById('pitStopFields').classList.add('hidden');
        refreshPitStops();
    }
    function hideCompleteModal() {
        document.getElementById('completeModal').classList.add('hidden');
        currentRouteId = null;
    }
</script>
@endsection
