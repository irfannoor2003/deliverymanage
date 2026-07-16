@extends('layouts.app')

@section('title', __('messages.my_report'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="page-header">
        <h1>{{ __('messages.my_report') }}</h1>
        <p>{{ __('messages.my_report_desc') }}</p>
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
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.total_routes') }}</div>
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
                <div class="p-3 rounded-2xl bg-amber-500/10 text-amber-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
    </div>

    <!-- Routes Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.route') }}</th>
                        <th>{{ __('messages.km') }}</th>
                        <th>{{ __('messages.boc') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $route)
                    <tr>
                        <td class="text-gray-500 dark:text-gray-400">{{ $route->from_location }} → {{ $route->to_location }}</td>
                        <td class="font-mono font-bold text-pink-500">{{ number_format($route->total_km, 1) }}{{ __('messages.km_suffix') }}</td>
                        <td class="text-gray-500 dark:text-gray-400">{{ $route->boc ?? '—' }}</td>
                        <td><span class="badge badge-{{ $route->status }}">{{ __('messages.' . $route->status) }}</span></td>
                        <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $route->created_at->format('d M, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state py-8"><h3>{{ __('messages.no_report_data') }}</h3><p>{{ __('messages.no_routes_desc') }}</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($routes->hasPages())
    <div class="mt-6">
        {{ $routes->links() }}
    </div>
    @endif
</div>
@endsection
