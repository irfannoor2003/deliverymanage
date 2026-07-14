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
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Delivery Boy</label>
                <select name="delivery_boy_id" class="glass-input glass-select pr-8 py-2 text-sm">
                    <option value="">All</option>
                    @foreach($deliveryBoys as $db)
                    <option value="{{ $db->id }}" {{ request('delivery_boy_id') == $db->id ? 'selected' : '' }}>{{ $db->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                <select name="status" class="glass-input glass-select pr-8 py-2 text-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">From</label>
                <input type="date" name="from_date" class="glass-input py-2 text-sm" value="{{ request('from_date') }}">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">To</label>
                <input type="date" name="to_date" class="glass-input py-2 text-sm" value="{{ request('to_date') }}">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('saleshead.reports') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Export -->
    <div class="flex justify-end gap-2 mb-4">
        <a href="{{ route('saleshead.reports.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="btn btn-primary btn-sm" target="_blank">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export PDF
        </a>
        <a href="{{ route('saleshead.reports.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-secondary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export CSV
        </a>
        <a href="{{ route('saleshead.reports.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}" class="btn btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export Excel
        </a>
    </div>

    <!-- Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>Delivery Boy</th>
                        <th>From</th>
                        <th>To</th>
                        <th>KM</th>
                        <th>Note</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $route)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center text-[9px] font-bold">{{ strtoupper(substr($route->deliveryBoy->name ?? 'NA', 0, 2)) }}</div>
                                <span class="font-semibold">{{ $route->deliveryBoy->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="text-gray-500 dark:text-gray-400">{{ $route->from_location }}</td>
                        <td class="text-gray-500 dark:text-gray-400">{{ $route->to_location }}</td>
                        <td class="font-mono font-bold text-pink-500">{{ number_format($route->total_km, 1) }}</td>
                        <td class="text-gray-500 dark:text-gray-400">{{ $route->boc ?? '—' }}</td>
                        <td><span class="badge badge-{{ $route->status }}">{{ ucfirst(str_replace('_', ' ', $route->status)) }}</span></td>
                        <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $route->created_at->format('d M, Y') }}</td>
                        <td><a href="{{ route('saleshead.routes.view', $route) }}" class="text-xs font-bold text-pink-500 hover:underline">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="8"><div class="empty-state py-8"><h3>No routes found</h3><p>Try adjusting your filters.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
