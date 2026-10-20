@extends('layouts.app')

@section('title', __('messages.manual_deliveries'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1>{{ __('messages.manual_deliveries') }}</h1>
            <p>{{ __('messages.manual_deliveries_desc') }}</p>
        </div>
        <a href="{{ route('admin.manual-deliveries.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('messages.add_manual_delivery') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="glass-card p-6 mb-6">
        <form action="{{ route('admin.manual-deliveries') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.delivery_boy') }}</label>
                <select name="delivery_boy_id" class="glass-input glass-select pr-8 py-2 text-sm">
                    <option value="" class="dark:!bg-[#1a1a2e] dark:!text-white">{{ __('messages.all') }}</option>
                    @foreach($deliveryBoys as $db)
                    <option value="{{ $db->id }}" class="dark:!bg-[#1a1a2e] dark:!text-white" {{ request('delivery_boy_id') == $db->id ? 'selected' : '' }}>{{ $db->name }}</option>
                    @endforeach
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
                <a href="{{ route('admin.manual-deliveries') }}" class="btn btn-secondary btn-sm">{{ __('messages.reset') }}</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.delivery_boy') }}</th>
                        <th>{{ __('messages.title') }}</th>
                        <th>{{ __('messages.from') }}</th>
                        <th>{{ __('messages.km') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.time') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-pink-500 to-purple-500 flex items-center justify-center text-white text-[9px] font-bold">{{ strtoupper(substr($delivery->deliveryBoy->name ?? 'NA', 0, 2)) }}</div>
                                <span class="font-semibold">{{ $delivery->deliveryBoy->name ?? __('messages.na') }}</span>
                            </div>
                        </td>
                        <td class="font-semibold text-gray-900 dark:text-white">{{ $delivery->title }}</td>
                        <td class="text-gray-500 dark:text-gray-400">{{ $delivery->note }}</td>
                        <td class="font-mono font-bold text-pink-500">{{ number_format($delivery->total_km, 1) }}</td>
                        <td><span class="badge badge-completed">{{ __('messages.completed') }}</span></td>
                        <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $delivery->delivery_date->format('d M, Y') }}</td>
                        <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $delivery->delivery_time }}</td>
                        <td>
                            @if($delivery->image_path)
                            <a href="{{ asset('storage/' . $delivery->image_path) }}" target="_blank" class="text-xs font-bold text-pink-500 hover:underline">{{ __('messages.image') }}</a>
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8"><div class="empty-state py-8"><h3>{{ __('messages.no_manual_deliveries') }}</h3><p>{{ __('messages.no_manual_deliveries_desc') }}</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
