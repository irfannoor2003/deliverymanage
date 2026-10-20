@extends('layouts.app')

@section('title', __('messages.export_report'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="glass-card p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ __('messages.delivery_routes_report') }}</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('messages.generated_on', ['date' => now()->format('d M, Y H:i')]) }}</p>
        </div>

        @if($routes->count() > 0)
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="p-4 rounded-2xl bg-pink-500/10 text-center">
                <div class="text-2xl font-bold text-pink-500">{{ $routes->count() }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.total_routes') }}</div>
            </div>
            <div class="p-4 rounded-2xl bg-emerald-500/10 text-center">
                <div class="text-2xl font-bold text-emerald-500">{{ $routes->where('status', 'completed')->count() }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.completed') }}</div>
            </div>
            <div class="p-4 rounded-2xl bg-indigo-500/10 text-center">
                <div class="text-2xl font-bold text-indigo-500">{{ number_format($routes->sum('total_km'), 1) }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.total_km') }}</div>
            </div>
        </div>

        <table class="glass-table">
            <thead>
                <tr>
                    <th>{{ __('messages.delivery_boy') }}</th>
                    <th>{{ __('messages.route') }}</th>
                    <th>{{ __('messages.km') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.manual') }}</th>
                    <th>{{ __('messages.self') }}</th>
                    <th>{{ __('messages.date') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($routes as $route)
                <tr>
                    <td class="font-semibold">{{ $route->deliveryBoy->name ?? __('messages.na') }}</td>
                    <td class="text-gray-500 dark:text-gray-400">{{ $route->from_location }} → {{ $route->to_location }}</td>
                    <td class="font-mono font-bold text-pink-500">{{ number_format($route->total_km, 1) }}</td>
                    <td><span class="badge badge-{{ $route->status }}">{{ __('messages.' . $route->status) }}</span></td>
                    <td><span class="badge {{ $route->is_manual ? 'badge-completed' : 'badge-pending' }}">{{ $route->is_manual ? __('messages.yes') : __('messages.no') }}</span></td>
                    <td><span class="badge {{ !$route->is_manual ? 'badge-completed' : 'badge-pending' }}">{{ !$route->is_manual ? __('messages.yes') : __('messages.no') }}</span></td>
                    <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $route->created_at->format('d M, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <h3>{{ __('messages.no_routes_found') }}</h3>
        </div>
        @endif

        <div class="text-center mt-8">
            <button onclick="window.print()" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                {{ __('messages.print_save_pdf') }}
            </button>
        </div>
    </div>
</div>
@endsection
