@extends('layouts.app')

@section('title', __('messages.route_number', ['id' => $route->id]))

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="page-header">
        <div class="flex items-center justify-between">
            <div>
                <h1>{{ __('messages.route_number', ['id' => $route->id]) }}</h1>
                <p>{{ __('messages.route_detail_desc') }}</p>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">{{ __('messages.back') }}</a>
        </div>
    </div>

    <!-- Route Status -->
    <div class="glass-card p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <span class="badge badge-{{ $route->status }}">{{ __('messages.' . $route->status) }}</span>
            @if($route->is_manual)
            <span class="badge badge-manual">📦 {{ __('messages.manual_visit') }}</span>
            @endif
            <div class="ml-auto text-sm text-gray-500 dark:text-gray-400">{{ $route->created_at->format('d M Y, h:i A') }}</div>
        </div>
    </div>

    <!-- Route Details -->
    <div class="glass-card p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('messages.route_details') }}</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.from') }}</div>
                <div class="font-bold text-gray-900 dark:text-white">{{ $route->from_location === 'Office' ? __('messages.office') : $route->from_location }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.to') }}</div>
                <div class="font-bold text-gray-900 dark:text-white">{{ $route->to_location }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.total_km') }}</div>
                <div class="font-bold text-pink-500">{{ number_format($route->total_km, 1) }}{{ __('messages.km_suffix') }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.boc') }}</div>
                <div class="font-bold text-gray-900 dark:text-white">{{ $route->boc ?? '—' }}</div>
            </div>
        </div>

        @php $routeImages = $route->images ?: ($route->image_path ? [$route->image_path] : []); @endphp
        @if(count($routeImages))
        <div class="mt-4">
            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ __('messages.route_images') }}</div>
            <div class="flex flex-wrap gap-2">
                @foreach($routeImages as $img)
                <img src="{{ asset('storage/'.$img) }}" onclick="openImageModal(this.src)" class="h-20 w-20 rounded-xl object-cover border border-gray-200 dark:border-white/10 cursor-pointer hover:opacity-80 transition-all">
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Timeline -->
    <div class="glass-card p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('messages.timeline') }}</h3>
        <div class="space-y-4">
            <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50 dark:bg-white/5">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center flex-shrink-0">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-gray-900 dark:text-white">{{ __('messages.created') }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $route->created_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            @if($route->started_at)
            <div class="flex items-center gap-4 p-3 rounded-xl bg-amber-500/10">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center flex-shrink-0">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-gray-900 dark:text-white">{{ __('messages.started') }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $route->started_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
            @endif

            @if($route->status === 'completed' && $route->updated_at)
            <div class="flex items-center gap-4 p-3 rounded-xl bg-emerald-500/10">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center flex-shrink-0">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-gray-900 dark:text-white">{{ __('messages.completed') }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $route->updated_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Pit Stops -->
    <div class="glass-card p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('messages.pit_stops') }} ({{ $route->stops->count() }})</h3>
        @if($route->stops->count() > 0)
        <div class="space-y-4">
            @foreach($route->stops as $stop)
            <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                <div class="w-8 h-8 rounded-full bg-pink-500/20 text-pink-500 flex items-center justify-center text-sm font-bold flex-shrink-0">{{ $loop->index + 1 }}</div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-900 dark:text-white">{{ $stop->stop_name }}</div>
                    @if($stop->stop_km)
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($stop->stop_km, 1) }} {{ __('messages.km') }}</div>
                    @endif
                    @if($stop->image_path || $stop->images)
                    <div class="flex gap-1 mt-2 flex-wrap">
                        @foreach(($stop->images ?: [$stop->image_path]) as $img)
                        <img src="{{ asset('storage/'.$img) }}" onclick="openImageModal(this.src)" class="h-10 w-10 rounded-lg object-cover border border-gray-200 dark:border-white/10 cursor-pointer hover:opacity-80 transition-all">
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm">{{ __('messages.no_pit_stops') }}</div>
        @endif
    </div>

    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('deliveryboy.routes.edit', $route) }}" class="btn btn-primary flex-1">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            {{ __('messages.edit') }}
        </a>
        <a href="{{ route('deliveryboy.my-routes') }}" class="btn btn-secondary flex-1">{{ __('messages.back') }}</a>
    </div>
</div>
@endsection