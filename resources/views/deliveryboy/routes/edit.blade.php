@extends('layouts.app')

@section('title', __('messages.edit_route'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="page-header">
        <h1>{{ __('messages.edit_route') }} #{{ $route->id }}</h1>
        <p>{{ __('messages.edit_route_desc') }}</p>
    </div>

    <div class="glass-card p-6 sm:p-8 mb-6">
        <form action="{{ route('deliveryboy.routes.update', $route) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5">{{ __('messages.route_details') }}</h3>

            <div class="mb-4 p-4 rounded-2xl bg-pink-500/5 border border-pink-500/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-500/10 text-pink-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('messages.from') }}</div>
                        <div class="text-base font-bold text-gray-900 dark:text-white">{{ __('messages.office') }}</div>
                    </div>
                    <svg class="w-5 h-5 text-pink-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    <div class="flex-1">
                        <label class="block text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('messages.to') }}</label>
                        <input type="text" name="to_location" required class="glass-input" value="{{ old('to_location', $route->to_location) }}">
                    </div>
                </div>
            </div>

            @if($route->status === 'completed' || $route->status === 'in_progress')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.km') }}</label>
                    <input type="number" step="0.01" name="total_km" required class="glass-input" value="{{ old('total_km', $route->total_km) }}">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.boc') }}</label>
                    <input type="text" name="boc" class="glass-input" value="{{ old('boc', $route->boc) }}">
                </div>
            </div>

            <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.image') }}</label>
                @if($route->image_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $route->image_path) }}" class="h-20 w-20 rounded-xl object-cover">
                </div>
                @endif
                <input type="file" name="image" accept="image/*" class="glass-input">
            </div>
            @endif

            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-white/5">
                <button type="submit" class="btn btn-primary w-full">{{ __('messages.update_route') }}</button>
            </div>
        </form>
    </div>

    @if($route->status === 'completed' || $route->status === 'in_progress')
    <!-- Pit Stops Management -->
    <div class="glass-card p-6 sm:p-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5">{{ __('messages.pit_stops') }}</h3>

        @if($route->stops->count() > 0)
        <div class="space-y-4 mb-6" id="pitStopsList">
            @foreach($route->stops as $stop)
            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                <form action="{{ route('deliveryboy.routes.pitstop.update', [$route, $stop]) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-semibold text-sm text-pink-500 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-full bg-pink-500/20 text-pink-500 text-[10px] font-bold flex items-center justify-center">{{ $loop->index + 1 }}</span>
                            {{ __('messages.stop_label') }} {{ $loop->index + 1 }}
                        </span>
                        <button type="button" onclick="if(confirm('{{ __('messages.delete_pit_stop_confirm') }}')){ this.closest('form').nextElementSibling.submit(); }" class="p-1.5 text-gray-400 hover:text-red-500 rounded-lg hover:bg-red-500/10 transition-all">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.stop_name') }}</label>
                            <input type="text" name="stop_name" class="glass-input text-sm" value="{{ $stop->stop_name }}">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.km') }}</label>
                            <input type="number" step="0.01" name="stop_km" class="glass-input text-sm" value="{{ $stop->stop_km }}">
                        </div>
                    </div>
                    <div class="mt-3 flex items-end gap-3">
                        <div class="flex-1">
                            <label class="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.image') }}</label>
                            @if($stop->image_path)
                            <div class="mb-1">
                                <img src="{{ asset('storage/' . $stop->image_path) }}" class="h-12 w-12 rounded-lg object-cover">
                            </div>
                            @endif
                            <input type="file" name="image" accept="image/*" class="glass-input text-sm">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.save') }}</button>
                    </div>
                </form>
                <form action="{{ route('deliveryboy.routes.pitstop.delete', [$route, $stop]) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm" id="noPitStops">{{ __('messages.no_pit_stops_yet') }}</div>
        @endif

        @if($route->status !== 'completed')
        <div class="pt-4 border-t border-gray-100 dark:border-white/5">
            <button type="button" onclick="showPitStopModal({{ $route->id }})" class="btn btn-secondary w-full !border-dashed !border-2">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                {{ __('messages.add_pit_stop') }}
            </button>
        </div>
        @endif
    </div>
    @endif
</div>

<!-- Pit Stop Modal -->
<div id="pitStopModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm hidden">
    <div class="glass-card p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.add_pit_stop') }}</h3>
            <button onclick="hidePitStopModal()" class="p-2 text-gray-400 hover:text-pink-500 rounded-xl hover:bg-pink-500/10 transition-all">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" enctype="multipart/form-data" id="pitStopForm">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.stop_name') }}</label>
                    <input type="text" name="stop_name" required class="glass-input" id="pitStopName">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.km') }}</label>
                    <input type="number" step="0.01" name="stop_km" class="glass-input" id="pitStopKm">
                </div>
                <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.image') }}</label>
                    <input type="file" name="image" accept="image/*" class="glass-input" id="pitStopImage">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary flex-1">{{ __('messages.add_pit_stop') }}</button>
                    <button type="button" onclick="hidePitStopModal()" class="btn btn-secondary flex-1">{{ __('messages.cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let currentRouteId = {{ $route->id }};

    function showPitStopModal(routeId) {
        currentRouteId = routeId;
        document.getElementById('pitStopName').value = '';
        document.getElementById('pitStopKm').value = '';
        document.getElementById('pitStopImage').value = '';
        document.getElementById('pitStopModal').classList.remove('hidden');
    }

    function hidePitStopModal() {
        document.getElementById('pitStopModal').classList.add('hidden');
    }

    document.getElementById('pitStopForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(form);
        var btn = form.querySelector('button[type=submit]');
        btn.disabled = true;
        btn.textContent = '...';

        fetch('/deliveryboy/routes/' + currentRouteId + '/pit-stop', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value },
            body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                var stop = data.data;
                var count = document.querySelectorAll('#pitStopsList > div').length + 1;
                var html = '<div class="p-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">'
                    + '<form action="/deliveryboy/routes/' + currentRouteId + '/pit-stop/' + stop.id + '" method="POST" enctype="multipart/form-data">'
                    + '<input type="hidden" name="_token" value="' + document.querySelector('input[name=_token]').value + '">'
                    + '<input type="hidden" name="_method" value="PUT">'
                    + '<div class="flex items-center justify-between mb-3">'
                    + '<span class="font-semibold text-sm text-pink-500 flex items-center gap-1.5">'
                    + '<span class="w-5 h-5 rounded-full bg-pink-500/20 text-pink-500 text-[10px] font-bold flex items-center justify-center">' + count + '</span>'
                    + '{{ __('messages.stop_label') }} ' + count
                    + '</span>'
                    + '<button type="button" onclick="if(confirm(\'{{ __('messages.delete_pit_stop_confirm') }}\')){ this.closest(\'form\').nextElementSibling.submit(); }" class="p-1.5 text-gray-400 hover:text-red-500 rounded-lg hover:bg-red-500/10 transition-all">'
                    + '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>'
                    + '</button>'
                    + '</div>'
                    + '<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">'
                    + '<div><label class="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.stop_name') }}</label><input type="text" name="stop_name" class="glass-input text-sm" value="' + stop.stop_name + '"></div>'
                    + '<div><label class="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.km') }}</label><input type="number" step="0.01" name="stop_km" class="glass-input text-sm" value="' + (stop.stop_km || 0) + '"></div>'
                    + '</div>'
                    + '<div class="mt-3 flex items-end gap-3">'
                    + '<div class="flex-1"><label class="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.image') }}</label>'
                    + (stop.image_path ? '<div class="mb-1"><img src="/storage/' + stop.image_path + '" class="h-12 w-12 rounded-lg object-cover"></div>' : '')
                    + '<input type="file" name="image" accept="image/*" class="glass-input text-sm"></div>'
                    + '<button type="submit" class="btn btn-primary btn-sm">{{ __('messages.save') }}</button>'
                    + '</div>'
                    + '</form>'
                    + '<form action="/deliveryboy/routes/' + currentRouteId + '/pit-stop/' + stop.id + '" method="POST" class="hidden">'
                    + '<input type="hidden" name="_token" value="' + document.querySelector('input[name=_token]').value + '">'
                    + '<input type="hidden" name="_method" value="DELETE">'
                    + '</form>'
                    + '</div>';

                var list = document.getElementById('pitStopsList');
                if (list) {
                    list.insertAdjacentHTML('beforeend', html);
                } else {
                    var container = document.querySelector('.glass-card.p-6');
                    var noMsg = document.getElementById('noPitStops');
                    if (noMsg) noMsg.remove();
                    var newList = document.createElement('div');
                    newList.className = 'space-y-4 mb-6';
                    newList.id = 'pitStopsList';
                    newList.innerHTML = html;
                    var heading = container.querySelector('h3');
                    if (heading) heading.insertAdjacentElement('afterend', newList);
                }
                hidePitStopModal();
            }
        })
        .catch(function() {})
        .finally(function() {
            btn.disabled = false;
            btn.textContent = '{{ __('messages.add_pit_stop') }}';
        });
    });

    function hidePitStopModal() {
        document.getElementById('pitStopModal').classList.add('hidden');
    }
</script>
@endsection
