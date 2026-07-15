@extends('layouts.app')

@section('title', __('messages.new_route'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="page-header">
        <h1>{{ __('messages.new_route') }}</h1>
        <p>{{ __('messages.starting_point') }}: <strong class="text-pink-500">{{ __('messages.office') }}</strong></p>
    </div>

    <div class="glass-card p-6 sm:p-8">
        <form action="{{ route('deliveryboy.routes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6 p-6 rounded-2xl bg-pink-500/5 border border-pink-500/10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-pink-500/10 text-pink-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('messages.from') }}</div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.office') }}</div>
                    </div>
                    <svg class="w-6 h-6 text-pink-500 mx-auto flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    <div class="flex-1">
                        <label class="block text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('messages.to') }}</label>
                        <div class="relative">
                            <input type="text" name="to_location" required class="glass-input pr-10 text-base" placeholder="{{ __('messages.say_where_going') }}" id="toLocation">
                            <button type="button" onclick="startSpeechToText('toLocation')" class="absolute right-2 top-1/2 -translate-y-1/2 audio-btn" title="{{ __('messages.record_audio') }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">{{ __('messages.tap_mic_destination') }}</p>
                    </div>
                </div>
            </div>

            <input type="hidden" name="from_location" value="Office">
            <input type="hidden" name="latitude" id="latitudeInput" value="">
            <input type="hidden" name="longitude" id="longitudeInput" value="">
            <input type="file" name="audio" id="audioInput" class="hidden" accept="audio/*">

            <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-6 border-t border-gray-100 dark:border-white/5">
                <button type="submit" id="submitBtn" class="btn btn-primary flex-1">{{ __('messages.create') }}</button>
                <a href="{{ route('deliveryboy.dashboard') }}" class="btn btn-secondary flex-1">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>

<script>
    // Get user location on page load
    var latInput = document.getElementById('latitudeInput');
    var lngInput = document.getElementById('longitudeInput');
    var submitBtn = document.getElementById('submitBtn');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            latInput.value = pos.coords.latitude;
            lngInput.value = pos.coords.longitude;
        }, function() {
            latInput.value = '';
            lngInput.value = '';
        }, { timeout: 10000 });
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        if (!latInput.value || !lngInput.value) {
            e.preventDefault();
            alert('{{ __('messages.location_required') }}');
            return;
        }
    });

    // Speech to text + auto-share audio to chat
    let speechRec = null;
    let audioRecorder = null;
    let audioChunks = [];
    let isRecording = false;

    function startSpeechToText(inputId) {
        const input = document.getElementById(inputId);

        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            alert('{{ __('messages.speech_not_supported') }}');
            return;
        }

        if (isRecording) {
            stopSpeechAndAudio(input);
            return;
        }

        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        speechRec = new SpeechRecognition();
        speechRec.lang = '{{ app()->getLocale() === 'ur' ? 'ur-PK' : 'en-US' }}';
        speechRec.interimResults = false;
        speechRec.continuous = false;

        speechRec.onresult = function(event) {
            input.value = event.results[0][0].transcript;
        };

        speechRec.onerror = function() {
            stopAudioRecording();
            isRecording = false;
        };

        speechRec.onend = function() {
            stopAudioRecording();
            isRecording = false;
        };

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

        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(function(stream) {
                var mimeType = getAudioMime();
                try {
                    audioRecorder = new MediaRecorder(stream, { mimeType: mimeType });
                } catch(e) {
                    audioRecorder = new MediaRecorder(stream);
                    mimeType = audioRecorder.mimeType || 'audio/webm';
                }
                audioChunks = [];

                audioRecorder.ondataavailable = function(e) {
                    if (e.data.size > 0) audioChunks.push(e.data);
                };

                audioRecorder.onstop = function() {
                    stream.getTracks().forEach(function(t) { t.stop(); });
                    setAudioFile();
                };

                audioRecorder.start();
            })
            .catch(function() {});
    }

    function stopAudioRecording() {
        if (audioRecorder && audioRecorder.state !== 'inactive') {
            audioRecorder.stop();
        }
    }

    function stopSpeechAndAudio(input) {
        if (speechRec) {
            speechRec.stop();
            speechRec = null;
        }
        stopAudioRecording();
        isRecording = false;
        input.style.borderColor = '';
        input.style.boxShadow = '';
    }

    function setAudioFile() {
        if (!audioChunks.length) return;
        var blob = new Blob(audioChunks, { type: getAudioMime() });
        var ext = getAudioExt(blob.type);
        var file = new File([blob], 'recording.' + ext, { type: blob.type });
        var dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('audioInput').files = dt.files;
    }
</script>
@endsection
