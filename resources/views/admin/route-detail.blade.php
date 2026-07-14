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

    <!-- Delivery Boy Info -->
    <div class="glass-card p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-pink-500 to-purple-500 flex items-center justify-center text-white text-lg font-bold">{{ strtoupper(substr($route->deliveryBoy->name ?? 'NA', 0, 2)) }}</div>
            <div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.delivery_boy') }}</div>
                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $route->deliveryBoy->name ?? 'N/A' }}</div>
            </div>
            <div class="ml-auto">
                <span class="badge badge-{{ $route->status }}">{{ ucfirst(str_replace('_', ' ', $route->status)) }}</span>
            </div>
        </div>
    </div>

    <!-- Route Details -->
    <div class="glass-card p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('messages.route_details') }}</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.from') }}</div>
                <div class="font-bold text-gray-900 dark:text-white">{{ $route->from_location }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.to') }}</div>
                <div class="font-bold text-gray-900 dark:text-white">{{ $route->to_location }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.total_km') }}</div>
                <div class="font-bold text-pink-500">{{ number_format($route->total_km, 1) }} km</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.boc') }}</div>
                <div class="font-bold text-gray-900 dark:text-white">{{ $route->boc ?? '—' }}</div>
            </div>
        </div>

        @if($route->image_path)
        <div class="mt-4">
            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ __('messages.route_image') }}</div>
            <img src="{{ asset('storage/'.$route->image_path) }}" class="w-full max-w-md rounded-2xl border border-gray-200 dark:border-white/10">
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

            @if($route->status === 'blocked' && $route->blocked_at)
            <div class="flex items-center gap-4 p-3 rounded-xl bg-rose-500/10">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center flex-shrink-0">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-gray-900 dark:text-white">{{ __('messages.blocked') }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $route->blocked_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
            @endif

            @if($route->unblocked_at)
            <div class="flex items-center gap-4 p-3 rounded-xl bg-purple-500/10">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center flex-shrink-0">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-gray-900 dark:text-white">{{ __('messages.unblock') }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $route->unblocked_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Pit Stops -->
    <div class="glass-card p-6">
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
                    @if($stop->image_path)
                    <img src="{{ asset('storage/'.$stop->image_path) }}" class="mt-2 h-24 w-auto rounded-xl border border-gray-200 dark:border-white/10 object-cover">
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm">{{ __('messages.no_pit_stops') }}</div>
        @endif
    </div>
</div>
@endsection
