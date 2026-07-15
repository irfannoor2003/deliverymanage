@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="page-header">
        <div class="flex items-center gap-2 mb-1">
            <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold">{{ __('messages.system_live') }}</span>
        </div>
        <h1>{{ __('messages.dashboard') }}</h1>
        <p>{{ __('messages.total_routes') }}</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-pink-500/10 text-pink-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_routes'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.total_routes') }}</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-indigo-500/10 text-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_routes'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.pending') }}</div>
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
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.in_progress') }}</div>
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
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.completed') }}</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-rose-500/10 text-rose-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['blocked_routes'] ?? 0 }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.blocked') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Routes -->
    <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.recent_routes') }}</h2>
            <a href="{{ route('saleshead.reports') }}" class="btn btn-primary btn-sm">{{ __('messages.view_all') }}</a>
        </div>

        @if($recentRoutes->count() > 0)
        <div class="overflow-x-auto">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.delivery_boy') }}</th>
                        <th>{{ __('messages.route') }}</th>
                        <th>{{ __('messages.km') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRoutes as $route)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center text-[9px] font-bold">{{ strtoupper(substr($route->deliveryBoy->name ?? 'NA', 0, 2)) }}</div>
                                <span class="font-semibold">{{ $route->deliveryBoy->name ?? __('messages.na') }}</span>
                            </div>
                        </td>
                        <td class="text-gray-500 dark:text-gray-400">{{ $route->from_location }} → {{ $route->to_location }}</td>
                        <td class="font-mono font-bold text-pink-500">{{ number_format($route->total_km, 1) }}{{ __('messages.km_suffix') }}</td>
                        <td><span class="badge badge-{{ $route->status }}">{{ __('messages.' . $route->status) }}</span></td>
                        <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $route->created_at->format('d M, Y') }}</td>
                        <td><a href="{{ route('saleshead.routes.view', $route) }}" class="text-xs font-bold text-pink-500 hover:underline">{{ __('messages.view') }}</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            <h3>{{ __('messages.no_routes') }}</h3>
            <p>{{ __('messages.no_routes_desc') }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
