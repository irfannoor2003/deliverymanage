@extends('layouts.app')

@section('title', __('messages.activity_logs'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="page-header">
        <h1>{{ __('messages.activity_logs') }}</h1>
    </div>

    <!-- Filters -->
    <div class="glass-card p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.action') }}</label>
                <select name="action" class="glass-input !py-1.5 text-xs">
                    <option value="" class="dark:!bg-[#1a1a2e] dark:!text-white">{{ __('messages.all') }}</option>
                    <option value="login" class="dark:!bg-[#1a1a2e] dark:!text-white" {{ request('action') === 'login' ? 'selected' : '' }}>{{ __('messages.login') }}</option>
                    <option value="logout" class="dark:!bg-[#1a1a2e] dark:!text-white" {{ request('action') === 'logout' ? 'selected' : '' }}>{{ __('messages.logout') }}</option>
                    <option value="delivery_started" class="dark:!bg-[#1a1a2e] dark:!text-white" {{ request('action') === 'delivery_started' ? 'selected' : '' }}>{{ __('messages.delivery_started') }}</option>
                    <option value="delivery_completed" class="dark:!bg-[#1a1a2e] dark:!text-white" {{ request('action') === 'delivery_completed' ? 'selected' : '' }}>{{ __('messages.delivery_completed') }}</option>
                    <option value="delivery_blocked" class="dark:!bg-[#1a1a2e] dark:!text-white" {{ request('action') === 'delivery_blocked' ? 'selected' : '' }}>{{ __('messages.delivery_blocked') }}</option>
                    <option value="delivery_unblocked" class="dark:!bg-[#1a1a2e] dark:!text-white" {{ request('action') === 'delivery_unblocked' ? 'selected' : '' }}>{{ __('messages.delivery_unblocked') }}</option>
                    <option value="user_created" class="dark:!bg-[#1a1a2e] dark:!text-white" {{ request('action') === 'user_created' ? 'selected' : '' }}>{{ __('messages.user_created') }}</option>
                    <option value="user_deleted" class="dark:!bg-[#1a1a2e] dark:!text-white" {{ request('action') === 'user_deleted' ? 'selected' : '' }}>{{ __('messages.user_deleted') }}</option>
                    <option value="chat_sent" class="dark:!bg-[#1a1a2e] dark:!text-white" {{ request('action') === 'chat_sent' ? 'selected' : '' }}>{{ __('messages.chat_sent') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.from') }}</label>
                <input type="date" name="from_date" class="glass-input !py-1.5 text-xs" value="{{ request('from_date') }}">
            </div>
            <div>
                <label class="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.to') }}</label>
                <input type="date" name="to_date" class="glass-input !py-1.5 text-xs" value="{{ request('to_date') }}">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.filter') }}</button>
                <a href="{{ route('admin.activity-logs') }}" class="btn btn-secondary btn-sm">{{ __('messages.reset') }}</a>
            </div>
        </form>
    </div>

    <!-- Logs -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.user') }}</th>
                        <th>{{ __('messages.action') }}</th>
                        <th>{{ __('messages.description') }}</th>
                        <th>{{ __('messages.ip_address') }}</th>
                        <th>{{ __('messages.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center text-[9px] font-bold">{{ strtoupper(substr($log->user->name ?? 'NA', 0, 2)) }}</div>
                                <span class="font-semibold text-sm">{{ $log->user->name ?? __('messages.na') }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge !text-[10px] {{ str_contains($log->action, 'blocked') ? '!bg-rose-500/10 !text-rose-500' : (str_contains($log->action, 'completed') ? '!bg-emerald-500/10 !text-emerald-500' : (str_contains($log->action, 'created') ? '!bg-pink-500/10 !text-pink-500' : '!bg-gray-500/10 !text-gray-500')) }}">
                                {{ __('messages.' . $log->action) }}
                            </span>
                        </td>
                        <td class="text-sm text-gray-500 dark:text-gray-400">{{ $log->description }}</td>
                        <td class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $log->ip_address ?? '—' }}</td>
                        <td class="text-sm text-gray-500 dark:text-gray-400">{{ $log->created_at->format('d M, H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state py-8"><h3>{{ __('messages.no_activity_logs_found') }}</h3></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($logs->hasPages())
    <div class="mt-6">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
