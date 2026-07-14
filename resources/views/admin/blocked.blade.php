@extends('layouts.app')

@section('title', __('messages.blocked_deliveries'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="page-header">
        <h1>{{ __('messages.blocked_deliveries') }}</h1>
        <p>{{ __('messages.blocked') }}</p>
    </div>

    @forelse($routes as $route)
    <div class="glass-card p-6 mb-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <div class="flex items-center gap-3">
                <span class="badge badge-blocked !bg-rose-500/15 !text-rose-500">{{ __('messages.blocked') }}</span>
                <span class="text-sm font-semibold text-gray-900 dark:text-white">Route #{{ $route->id }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500 dark:text-gray-400"> </span>
                <span class="text-sm font-bold text-rose-500">{{ $route->blocked_at?->format('d M Y, H:i') ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div>
                <span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Delivery Boy</span>
                <div class="flex items-center gap-2 mt-1">
                    <div class="w-8 h-8 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center text-[10px] font-bold">{{ strtoupper(substr($route->deliveryBoy->name ?? 'NA', 0, 2)) }}</div>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $route->deliveryBoy->name ?? 'N/A' }}</span>
                </div>
            </div>
            <div>
                <span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">From</span>
                <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $route->from_location }}</p>
            </div>
            <div>
                <span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">To</span>
                <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $route->to_location }}</p>
            </div>
            <div>
                <span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">KM</span>
                <p class="text-sm font-bold text-pink-500 mt-1">{{ number_format($route->total_km, 1) }} km</p>
            </div>
        </div>

        @if($route->stops->count() > 0)
        <div class="mb-4">
            <span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pit Stops ({{ $route->stops->count() }})</span>
            <div class="flex flex-wrap gap-2 mt-1">
                @foreach($route->stops as $stop)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-white/5 text-xs font-medium">{{ $stop->stop_name }} <span class="text-pink-500 font-mono">({{ $stop->stop_km }} km)</span></span>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-white/5">
            <form action="{{ route('admin.blocked.unblock', [$route->deliveryBoy, $route]) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="btn btn-primary w-full !bg-gradient-to-r !from-emerald-500 !to-emerald-600 !shadow-emerald-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Unblock Delivery
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="glass-card p-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('messages.blocked_deliveries') }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">All deliveries are on track.</p>
    </div>
    @endforelse
</div>
@endsection
