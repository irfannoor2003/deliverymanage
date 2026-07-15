@extends('layouts.app')

@section('title', __('messages.reports'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="page-header">
        <h1>{{ __('messages.reports') }}</h1>
        <p>{{ __('messages.total_routes') }}</p>
    </div>

    <!-- Filters -->
    <div class="glass-card p-6 mb-6">
        <form action="{{ route('saleshead.reports') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.delivery_boy') }}</label>
                <select name="delivery_boy_id" class="glass-input glass-select pr-8 py-2 text-sm">
                    <option value="">{{ __('messages.all') }}</option>
                    @foreach($deliveryBoys as $db)
                    <option value="{{ $db->id }}" {{ request('delivery_boy_id') == $db->id ? 'selected' : '' }}>{{ $db->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.status') }}</label>
                <select name="status" class="glass-input glass-select pr-8 py-2 text-sm">
                    <option value="">{{ __('messages.all') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('messages.in_progress') }}</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>{{ __('messages.blocked') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.from') }}</label>
                <input type="date" name="from_date" class="glass-input py-2 text-sm" value="{{ request('from_date') }}">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.to') }}</label>
                <input type="date" name="to_date" class="glass-input py-2 text-sm" value="{{ request('to_date') }}">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.filter') }}</button>
                <a href="{{ route('saleshead.reports') }}" class="btn btn-secondary btn-sm">{{ __('messages.reset') }}</a>
            </div>
        </form>
    </div>

    <!-- Export -->
    <div class="flex justify-end gap-2 mb-4">
        <a href="{{ route('saleshead.reports.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="btn btn-primary btn-sm" target="_blank">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            {{ __('messages.export_pdf') }}
        </a>
        <a href="{{ route('saleshead.reports.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-secondary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            {{ __('messages.export_csv') }}
        </a>
        <a href="{{ route('saleshead.reports.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}" class="btn btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            {{ __('messages.export_excel') }}
        </a>
    </div>

    <!-- Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.delivery_boy') }}</th>
                        <th>{{ __('messages.from') }}</th>
                        <th>{{ __('messages.to') }}</th>
                        <th>{{ __('messages.km') }}</th>
                        <th>{{ __('messages.boc') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $route)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center text-[9px] font-bold">{{ strtoupper(substr($route->deliveryBoy->name ?? 'NA', 0, 2)) }}</div>
                                <span class="font-semibold">{{ $route->deliveryBoy->name ?? __('messages.na') }}</span>
                            </div>
                        </td>
                        <td class="text-gray-500 dark:text-gray-400">{{ $route->from_location }}</td>
                        <td class="text-gray-500 dark:text-gray-400">{{ $route->to_location }}</td>
                        <td class="font-mono font-bold text-pink-500">{{ number_format($route->total_km, 1) }}</td>
                        <td class="text-gray-500 dark:text-gray-400">{{ $route->boc ?? '—' }}</td>
                        <td><span class="badge badge-{{ $route->status }}">{{ __('messages.' . $route->status) }}</span></td>
                        <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $route->created_at->format('d M, Y') }}</td>
                        <td><a href="{{ route('saleshead.routes.view', $route) }}" class="text-xs font-bold text-pink-500 hover:underline">{{ __('messages.view') }}</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="8"><div class="empty-state py-8"><h3>{{ __('messages.no_routes_found') }}</h3><p>{{ __('messages.try_adjusting_filters') }}</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
