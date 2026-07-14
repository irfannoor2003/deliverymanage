@extends('layouts.app')

@section('title', __('messages.team_chat'))

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="glass-card overflow-hidden" style="height: calc(100vh - 200px); min-height: 500px; display: flex; flex-direction: column;">
        <!-- Chat Header -->
        <div class="p-4 sm:p-5 border-b border-gray-200 dark:border-white/10 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-pink-500 to-purple-500 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-900 dark:text-white">{{ __('messages.team_chat') }}</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.team_chat_desc') }}</p>
            </div>
            <span class="ml-auto flex items-center gap-1.5 text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wide">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ __('messages.live') }}
            </span>
        </div>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-4" id="messagesContainer">
            @forelse($messages as $message)
            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] sm:max-w-[70%] {{ $message->sender_id === auth()->id() ? 'bg-pink-500 text-white rounded-2xl rounded-br-md' : 'bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white rounded-2xl rounded-bl-md' }} px-4 py-3">
                    @if($message->sender_id !== auth()->id())
                    <div class="text-[10px] font-bold {{ $message->sender_id === auth()->id() ? 'text-white/70' : 'text-pink-500' }} mb-1 uppercase tracking-wide">{{ $message->sender->name }}</div>
                    @endif

                    @if($message->type === 'audio' && $message->audio_path)
                    <div class="audio-player" data-src="{{ asset('storage/' . $message->audio_path) }}">
                        <button type="button" class="play-btn" onclick="togglePlay(this)">
                            <svg class="play-icon" fill="currentColor" viewBox="0 0 24 24" width="18" height="18"><path d="M8 5v14l11-7z"/></svg>
                            <svg class="pause-icon hidden" fill="currentColor" viewBox="0 0 24 24" width="18" height="18"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        </button>
                        <div class="waveform">
                            <span></span><span></span><span></span><span></span><span></span>
                            <span></span><span></span><span></span><span></span><span></span>
                            <span></span><span></span><span></span><span></span><span></span>
                            <span></span><span></span><span></span><span></span><span></span>
                        </div>
                        <div class="audio-progress"><div class="audio-progress-bar"></div></div>
                        <span class="audio-duration">0:00</span>
                    </div>
                    @elseif($message->content)
                    <p class="text-sm leading-relaxed">{{ $message->content }}</p>
                    @endif

                    <div class="text-[10px] mt-1.5 {{ $message->sender_id === auth()->id() ? 'text-white/60' : 'text-gray-400 dark:text-gray-500' }} text-right">{{ $message->created_at->format('H:i') }}</div>
                </div>
            </div>
            @empty
            <div class="flex-1 flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-2xl bg-pink-500/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">{{ __('messages.start_conversation') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.send_message_to_team') }}</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Recording Indicator (someone else is recording) -->
        <div id="recordingIndicator" class="hidden px-4 sm:px-5 py-2 bg-red-500/5 border-t border-red-500/10">
            <div class="flex items-center gap-2 text-xs text-red-500 font-semibold">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                <span id="recordingIndicatorText">{{ __('messages.someone_recording') }}</span>
            </div>
        </div>

        <!-- Message Input Area -->
        <div class="p-4 sm:p-5 border-t border-gray-200 dark:border-white/10">
            <!-- Default input state -->
            <div id="inputState">
                <form action="{{ route('chat.send') }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-3" id="chatForm">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="content" placeholder="{{ __('messages.type_message') }}" class="glass-input" id="chatInput">
                    </div>
                    <input type="file" name="audio" accept="audio/*" class="hidden" id="audioInput">
                    <button type="button" onclick="startRecording()" class="audio-btn" id="recordBtn" title="{{ __('messages.record_audio') }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                    </button>
                    <button type="submit" class="btn btn-primary !p-3">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </form>
            </div>

            <!-- Recording state (shows during recording) -->
            <div id="recordingState" class="hidden flex items-center gap-3">
                <div class="flex-1 flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-red-500/5 border border-red-500/20">
                    <span class="w-3 h-3 rounded-full bg-red-500 animate-pulse flex-shrink-0"></span>
                    <div class="flex-1 h-1 rounded-full bg-red-500/20 overflow-hidden">
                        <div id="recordTimerBar" class="h-full bg-red-500 rounded-full" style="width: 100%; transition: width 1s linear;"></div>
                    </div>
                    <span id="recordTimer" class="text-sm font-mono font-bold text-red-500 tabular-nums">0:00</span>
                </div>
                <button type="button" onclick="stopRecording()" class="btn btn-primary !p-3 !bg-red-500 !border-red-500 hover:!bg-red-600" title="{{ __('messages.stop') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><rect x="6" y="6" width="12" height="12" rx="2" fill="currentColor"/></svg>
                </button>
            </div>

            <!-- Preview state (after recording, before sending) -->
            <div id="previewState" class="hidden flex items-center gap-3">
                <div class="flex-1 flex items-center gap-3 px-4 py-2 rounded-2xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                    <button type="button" onclick="togglePreviewPlay()" class="w-9 h-9 rounded-full bg-pink-500 text-white flex items-center justify-center flex-shrink-0 hover:scale-105 transition-transform">
                        <svg id="previewPlayIcon" fill="currentColor" viewBox="0 0 24 24" width="18" height="18"><path d="M8 5v14l11-7z"/></svg>
                        <svg id="previewPauseIcon" class="hidden" fill="currentColor" viewBox="0 0 24 24" width="18" height="18"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                    </button>
                    <div class="waveform-sm flex-1">
                        <span></span><span></span><span></span><span></span><span></span>
                        <span></span><span></span><span></span><span></span><span></span>
                        <span></span><span></span><span></span><span></span><span></span>
                        <span></span><span></span><span></span><span></span><span></span>
                    </div>
                    <span id="previewDuration" class="text-xs font-mono text-gray-500 dark:text-gray-400 tabular-nums">0:00</span>
                </div>
                <button type="button" onclick="deleteRecording()" class="btn btn-secondary !p-3" title="{{ __('messages.delete') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                <button type="button" onclick="sendRecording()" class="btn btn-primary !p-3" title="{{ __('messages.send') }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.audio-player {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 180px;
    max-width: 260px;
    padding: 4px 0;
}
.audio-player .play-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.2s;
    background: rgba(255,255,255,0.2);
    color: inherit;
}
.audio-player .play-btn:hover { transform: scale(1.05); background: rgba(255,255,255,0.3); }
.audio-player .waveform { display: flex; align-items: center; gap: 2px; height: 24px; flex: 1; }
.audio-player .waveform span { display: block; width: 3px; border-radius: 2px; background: currentColor; opacity: 0.4; height: 4px; transition: height 0.15s, opacity 0.15s; }
.audio-player .waveform span.active { opacity: 0.8; }
.audio-player .waveform span:nth-child(1) { height: 8px; }
.audio-player .waveform span:nth-child(2) { height: 12px; }
.audio-player .waveform span:nth-child(3) { height: 16px; }
.audio-player .waveform span:nth-child(4) { height: 20px; }
.audio-player .waveform span:nth-child(5) { height: 14px; }
.audio-player .waveform span:nth-child(6) { height: 18px; }
.audio-player .waveform span:nth-child(7) { height: 10px; }
.audio-player .waveform span:nth-child(8) { height: 22px; }
.audio-player .waveform span:nth-child(9) { height: 14px; }
.audio-player .waveform span:nth-child(10) { height: 8px; }
.audio-player .waveform span:nth-child(11) { height: 18px; }
.audio-player .waveform span:nth-child(12) { height: 12px; }
.audio-player .waveform span:nth-child(13) { height: 20px; }
.audio-player .waveform span:nth-child(14) { height: 10px; }
.audio-player .waveform span:nth-child(15) { height: 16px; }
.audio-player .waveform span:nth-child(16) { height: 24px; }
.audio-player .waveform span:nth-child(17) { height: 14px; }
.audio-player .waveform span:nth-child(18) { height: 8px; }
.audio-player .waveform span:nth-child(19) { height: 18px; }
.audio-player .waveform span:nth-child(20) { height: 12px; }
.audio-progress { width: 40px; height: 3px; border-radius: 2px; background: currentColor; opacity: 0.3; flex-shrink: 0; overflow: hidden; }
.audio-progress-bar { height: 100%; width: 0%; background: currentColor; opacity: 0.8; transition: width 0.3s; }
.audio-duration { font-size: 10px; opacity: 0.7; min-width: 32px; text-align: right; flex-shrink: 0; font-variant-numeric: tabular-nums; }

.waveform-sm { display: flex; align-items: center; gap: 1.5px; height: 20px; }
.waveform-sm span { display: block; width: 2.5px; border-radius: 2px; background: currentColor; opacity: 0.3; }
.waveform-sm span:nth-child(1) { height: 6px; }
.waveform-sm span:nth-child(2) { height: 10px; }
.waveform-sm span:nth-child(3) { height: 14px; }
.waveform-sm span:nth-child(4) { height: 18px; }
.waveform-sm span:nth-child(5) { height: 12px; }
.waveform-sm span:nth-child(6) { height: 16px; }
.waveform-sm span:nth-child(7) { height: 8px; }
.waveform-sm span:nth-child(8) { height: 20px; }
.waveform-sm span:nth-child(9) { height: 12px; }
.waveform-sm span:nth-child(10) { height: 6px; }
.waveform-sm span:nth-child(11) { height: 16px; }
.waveform-sm span:nth-child(12) { height: 10px; }
.waveform-sm span:nth-child(13) { height: 18px; }
.waveform-sm span:nth-child(14) { height: 8px; }
.waveform-sm span:nth-child(15) { height: 14px; }
.waveform-sm span:nth-child(16) { height: 22px; }
.waveform-sm span:nth-child(17) { height: 12px; }
.waveform-sm span:nth-child(18) { height: 6px; }
.waveform-sm span:nth-child(19) { height: 16px; }
.waveform-sm span:nth-child(20) { height: 10px; }
</style>

<script>
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;

    let mediaRecorder = null;
    let audioChunks = [];
    let recording = false;
    let activeAudio = null;
    let recordedBlob = null;
    let recordedFile = null;
    let recordedMime = '';
    let recordTimerInterval = null;
    let recordSeconds = 0;

    // Toggle play for received/sent audio messages
    function togglePlay(btn) {
        var player = btn.closest('.audio-player');
        var src = player.dataset.src;
        var playIcon = btn.querySelector('.play-icon');
        var pauseIcon = btn.querySelector('.pause-icon');
        var progressBar = player.querySelector('.audio-progress-bar');
        var durationEl = player.querySelector('.audio-duration');
        var waveformBars = player.querySelectorAll('.waveform span');

        if (activeAudio && activeAudio.dataset.player === player.dataset.src) {
            if (!activeAudio.paused) {
                activeAudio.pause();
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                waveformBars.forEach(function(b) { b.classList.remove('active'); });
            } else {
                activeAudio.play();
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
            }
            return;
        }

        if (activeAudio) {
            var prevBtns = document.querySelectorAll('.audio-player .play-btn');
            prevBtns.forEach(function(b) {
                b.querySelector('.play-icon').classList.remove('hidden');
                b.querySelector('.pause-icon').classList.add('hidden');
            });
            document.querySelectorAll('.waveform span').forEach(function(b) { b.classList.remove('active'); });
            document.querySelectorAll('.waveform-sm span').forEach(function(b) { b.classList.remove('active'); });
            activeAudio.pause();
            activeAudio = null;
        }

        var audio = new Audio(src);
        audio.dataset.player = src;
        activeAudio = audio;

        playIcon.classList.add('hidden');
        pauseIcon.classList.remove('hidden');

        audio.addEventListener('timeupdate', function() {
            if (audio.duration) {
                var pct = (audio.currentTime / audio.duration) * 100;
                progressBar.style.width = pct + '%';
                var mins = Math.floor(audio.currentTime / 60);
                var secs = Math.floor(audio.currentTime % 60);
                durationEl.textContent = mins + ':' + (secs < 10 ? '0' : '') + secs;
                var activeIdx = Math.floor((audio.currentTime / audio.duration) * waveformBars.length);
                waveformBars.forEach(function(b, i) {
                    b.classList.toggle('active', i < activeIdx);
                });
            }
        });

        audio.addEventListener('ended', function() {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            waveformBars.forEach(function(b) { b.classList.remove('active'); });
            progressBar.style.width = '0%';
            durationEl.textContent = '0:00';
            activeAudio = null;
        });

        audio.play();
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

    // Start recording
    async function startRecording() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('{{ __('messages.not_supported_recording') }}');
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            var mimeType = getAudioMime();
            try {
                mediaRecorder = new MediaRecorder(stream, { mimeType: mimeType });
            } catch(e) {
                mediaRecorder = new MediaRecorder(stream);
                mimeType = mediaRecorder.mimeType || 'audio/webm';
            }
            recordedMime = mimeType;
            audioChunks = [];

            mediaRecorder.ondataavailable = function(e) {
                if (e.data.size > 0) audioChunks.push(e.data);
            };

            mediaRecorder.onstop = function() {
                stream.getTracks().forEach(function(track) { track.stop(); });
                if (audioChunks.length > 0) {
                    recordedBlob = new Blob(audioChunks, { type: recordedMime });
                    var ext = getAudioExt(recordedMime);
                    recordedFile = new File([recordedBlob], 'recording.' + ext, { type: recordedMime });
                    showPreview();
                }
            };

            // Show recording state
            document.getElementById('inputState').classList.add('hidden');
            document.getElementById('recordingState').classList.remove('hidden');
            recording = true;
            recordSeconds = 0;
            startRecordTimer();
            broadcastRecording(true);

            mediaRecorder.start();
        } catch {
            alert('{{ __('messages.microphone_denied') }}');
        }
    }

    function startRecordTimer() {
        var timerEl = document.getElementById('recordTimer');
        var barEl = document.getElementById('recordTimerBar');
        recordSeconds = 0;
        barEl.style.width = '100%';
        recordTimerInterval = setInterval(function() {
            recordSeconds++;
            var mins = Math.floor(recordSeconds / 60);
            var secs = recordSeconds % 60;
            timerEl.textContent = mins + ':' + (secs < 10 ? '0' : '') + secs;
            if (recordSeconds > 180) stopRecording(); // 3 min max
        }, 1000);
    }

    function stopRecordTimer() {
        if (recordTimerInterval) {
            clearInterval(recordTimerInterval);
            recordTimerInterval = null;
        }
    }

    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            stopRecordTimer();
            mediaRecorder.stop();
            recording = false;
            broadcastRecording(false);
            document.getElementById('recordingState').classList.add('hidden');
        }
    }

    // Show preview with play/send/delete
    function showPreview() {
        var url = URL.createObjectURL(recordedBlob);
        var audio = new Audio(url);
        audio.addEventListener('loadedmetadata', function() {
            var mins = Math.floor(audio.duration / 60);
            var secs = Math.floor(audio.duration % 60);
            document.getElementById('previewDuration').textContent = mins + ':' + (secs < 10 ? '0' : '') + secs;
        });
        document.getElementById('previewState').classList.remove('hidden');
        document.getElementById('previewState').dataset.audioUrl = url;
    }

    function togglePreviewPlay() {
        var url = document.getElementById('previewState').dataset.audioUrl;
        var playIcon = document.getElementById('previewPlayIcon');
        var pauseIcon = document.getElementById('previewPauseIcon');

        if (activeAudio && activeAudio.dataset.player === 'preview') {
            if (!activeAudio.paused) {
                activeAudio.pause();
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
            } else {
                activeAudio.play();
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
            }
            return;
        }

        if (activeAudio) {
            var prevBtns = document.querySelectorAll('.audio-player .play-btn');
            prevBtns.forEach(function(b) {
                b.querySelector('.play-icon').classList.remove('hidden');
                b.querySelector('.pause-icon').classList.add('hidden');
            });
            document.querySelectorAll('.waveform span').forEach(function(b) { b.classList.remove('active'); });
            document.querySelectorAll('.waveform-sm span').forEach(function(b) { b.classList.remove('active'); });
            activeAudio.pause();
            activeAudio = null;
        }

        var audio = new Audio(url);
        audio.dataset.player = 'preview';
        activeAudio = audio;

        playIcon.classList.add('hidden');
        pauseIcon.classList.remove('hidden');

        var bars = document.querySelector('#previewState .waveform-sm span');
        audio.addEventListener('timeupdate', function() {
            if (audio.duration) {
                var activeIdx = Math.floor((audio.currentTime / audio.duration) * 20);
                var allBars = document.querySelectorAll('#previewState .waveform-sm span');
                allBars.forEach(function(b, i) {
                    b.classList.toggle('active', i < activeIdx);
                });
            }
        });

        audio.addEventListener('ended', function() {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            document.querySelectorAll('#previewState .waveform-sm span').forEach(function(b) { b.classList.remove('active'); });
            activeAudio = null;
        });

        audio.play();
    }

    function deleteRecording() {
        if (activeAudio) {
            activeAudio.pause();
            activeAudio = null;
        }
        recordedBlob = null;
        recordedFile = null;
        document.getElementById('previewState').classList.add('hidden');
        document.getElementById('inputState').classList.remove('hidden');
    }

    function sendRecording() {
        if (!recordedFile) return;
        var audioInput = document.getElementById('audioInput');
        var dataTransfer = new DataTransfer();
        dataTransfer.items.add(recordedFile);
        audioInput.files = dataTransfer.files;
        recordedBlob = null;
        recordedFile = null;
        document.getElementById('previewState').classList.add('hidden');
        document.getElementById('chatForm').submit();
    }

    function broadcastRecording(isRecording) {
        fetch('/chat/broadcast-recording', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ recording: isRecording })
        }).catch(function() {});
    }

    // Allow either text or audio
    document.getElementById('chatForm').addEventListener('submit', function(e) {
        var content = document.getElementById('chatInput').value.trim();
        var audioInput = document.getElementById('audioInput');
        if (!content && (!audioInput.files || audioInput.files.length === 0)) {
            e.preventDefault();
            alert('{{ __('messages.type_or_record') }}');
        }
    });

    // Poll for new messages
    var lastMessageId = {{ $messages->last()->id ?? 0 }};
    setInterval(function() {
        fetch('{{ route('chat.messages') }}')
            .then(function(res) { return res.json(); })
            .then(function(messages) {
                if (messages && messages.length > 0) {
                    var newMessages = messages.filter(function(m) { return m.id > lastMessageId; });
                    if (newMessages.length > 0) {
                        lastMessageId = newMessages[newMessages.length - 1].id;
                    }
                    newMessages.forEach(function(msg) {
                        var isSent = msg.sender_id === {{ auth()->id() }};
                        var msgHtml = '';
                        if (isSent) {
                            msgHtml = '<div class="flex justify-end" data-msg-id="' + msg.id + '"><div class="max-w-[80%] sm:max-w-[70%] bg-pink-500 text-white rounded-2xl rounded-br-md px-4 py-3">';
                        } else {
                            msgHtml = '<div class="flex justify-start" data-msg-id="' + msg.id + '"><div class="max-w-[80%] sm:max-w-[70%] bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white rounded-2xl rounded-bl-md px-4 py-3">';
                        }
                        if (!isSent) {
                            var senderName = msg.sender ? msg.sender.name : '{{ __('messages.unknown') }}';
                            msgHtml += '<div class="text-[10px] font-bold text-pink-500 mb-1 uppercase tracking-wide">' + senderName + '</div>';
                        }
                        if (msg.type === 'audio' && msg.audio_path) {
                            msgHtml += '<div class="audio-player" data-src="/storage/' + msg.audio_path + '">'
                                + '<button type="button" class="play-btn" onclick="togglePlay(this)">'
                                + '<svg class="play-icon" fill="currentColor" viewBox="0 0 24 24" width="18" height="18"><path d="M8 5v14l11-7z"/></svg>'
                                + '<svg class="pause-icon hidden" fill="currentColor" viewBox="0 0 24 24" width="18" height="18"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>'
                                + '</button>'
                                + '<div class="waveform">'
                                + '<span></span><span></span><span></span><span></span><span></span>'
                                + '<span></span><span></span><span></span><span></span><span></span>'
                                + '<span></span><span></span><span></span><span></span><span></span>'
                                + '<span></span><span></span><span></span><span></span><span></span>'
                                + '</div>'
                                + '<div class="audio-progress"><div class="audio-progress-bar"></div></div>'
                                + '<span class="audio-duration">0:00</span>'
                                + '</div>';
                        } else if (msg.content) {
                            msgHtml += '<p class="text-sm leading-relaxed">' + msg.content + '</p>';
                        }
                        var time = new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
                        msgHtml += '<div class="text-[10px] mt-1.5 ' + (isSent ? 'text-white/60' : 'text-gray-400 dark:text-gray-500') + ' text-right">' + time + '</div>';
                        msgHtml += '</div></div>';

                        container.insertAdjacentHTML('beforeend', msgHtml);
                        container.scrollTop = container.scrollHeight;

                        if (!isSent && 'Notification' in window && Notification.permission === 'granted') {
                            var nName = msg.sender ? msg.sender.name : 'Team Member';
                            if (msg.content) {
                                new Notification('New message from ' + nName, { body: msg.content, icon: '/favicon.ico' });
                            } else if (msg.type === 'audio') {
                                new Notification('New voice message from ' + nName, { icon: '/favicon.ico' });
                            }
                        }
                    });
                }
            })
            .catch(function() {});
    }, 5000);

    // Poll for recording status
    setInterval(function() {
        fetch('/chat/recording-status')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var indicator = document.getElementById('recordingIndicator');
                var textEl = document.getElementById('recordingIndicatorText');
                if (data.recording && data.user_id !== {{ auth()->id() }}) {
                    textEl.textContent = (data.user_name || '{{ __('messages.unknown') }}') + ' {{ __('messages.someone_recording') }}';
                    indicator.classList.remove('hidden');
                } else {
                    indicator.classList.add('hidden');
                }
            })
            .catch(function() {});
    }, 3000);
</script>
@endsection