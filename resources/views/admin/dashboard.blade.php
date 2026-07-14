@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="page-header">
        <div class="flex items-center gap-2 mb-1">
            <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold">{{ __('messages.system_live') }}</span>
        </div>
        <h1>Welcome back, {{ auth()->user()->name }}</h1>
        <p>Here's what's happening with your delivery operations today.</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-pink-500/10 text-pink-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Users</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-indigo-500/10 text-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['delivery_boys'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Delivery Boys</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-2xl bg-emerald-500/10 text-emerald-500">
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
                <div class="p-3 rounded-2xl bg-rose-500/10 text-rose-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['blocked_routes'] ?? 0 }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Blocked</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
        <a href="{{ route('admin.members') }}" class="glass-card p-4 flex items-center gap-3 hover:!border-pink-500/30 no-underline">
            <div class="p-2.5 rounded-xl bg-pink-500/10 text-pink-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('messages.members') }}</span>
        </a>
        <a href="{{ route('admin.members.create') }}" class="glass-card p-4 flex items-center gap-3 hover:!border-pink-500/30 no-underline">
            <div class="p-2.5 rounded-xl bg-pink-500/10 text-pink-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('messages.create') }}</span>
        </a>
        <a href="{{ route('admin.reports') }}" class="glass-card p-4 flex items-center gap-3 hover:!border-pink-500/30 no-underline">
            <div class="p-2.5 rounded-xl bg-emerald-500/10 text-emerald-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('messages.reports') }}</span>
        </a>
        <a href="{{ route('admin.blocked') }}" class="glass-card p-4 flex items-center gap-3 hover:!border-pink-500/30 no-underline">
            <div class="p-2.5 rounded-xl bg-rose-500/10 text-rose-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('messages.blocked') }}</span>
        </a>
        <a href="{{ route('admin.activity-logs') }}" class="glass-card p-4 flex items-center gap-3 hover:!border-pink-500/30 no-underline">
            <div class="p-2.5 rounded-xl bg-indigo-500/10 text-indigo-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('messages.activity') }}</span>
        </a>
        <a href="{{ route('chat') }}" class="glass-card p-4 flex items-center gap-3 hover:!border-pink-500/30 no-underline">
            <div class="p-2.5 rounded-xl bg-purple-500/10 text-purple-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('messages.chat') }}</span>
        </a>
    </div>

    <!-- Recent Routes -->
    <div class="glass-card p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Routes</h2>
            <a href="{{ route('admin.reports') }}" class="btn btn-primary btn-sm">View All</a>
        </div>

        @if($recentRoutes->count() > 0)
        <div class="overflow-x-auto">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Delivery Boy</th>
                        <th>Route</th>
                        <th>KM</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRoutes as $route)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center text-[9px] font-bold">{{ strtoupper(substr($route->deliveryBoy->name ?? 'NA', 0, 2)) }}</div>
                                <span class="font-semibold">{{ $route->deliveryBoy->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="text-gray-500 dark:text-gray-400">{{ $route->from_location }} → {{ $route->to_location }}</td>
                        <td class="font-mono font-bold text-pink-500">{{ number_format($route->total_km, 1) }} km</td>
                        <td><span class="badge badge-{{ $route->status }}">{{ ucfirst(str_replace('_', ' ', $route->status)) }}</span></td>
                        <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $route->created_at->format('d M, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            <h3>No routes yet</h3>
            <p>Delivery routes will appear here once created.</p>
        </div>
        @endif
    </div>
</div>
@endsection
