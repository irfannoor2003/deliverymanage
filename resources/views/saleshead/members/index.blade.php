@extends('layouts.app')

@section('title', __('messages.members'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="page-header">
        <h1>{{ __('messages.members') }}</h1>
        <p>{{ __('messages.members_management_desc') }}</p>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.user') }}</th>
                        <th>{{ __('messages.role') }}</th>
                        <th>{{ __('messages.created') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-pink-500 to-purple-500 flex items-center justify-center text-white text-[10px] font-bold">{{ strtoupper(substr($member->name, 0, 2)) }}</div>
                                <span class="font-semibold">{{ $member->name }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                            $roleColors = ['admin' => 'bg-purple-500/10 text-purple-500', 'saleshead' => 'bg-blue-500/10 text-blue-500', 'deliveryboy' => 'bg-emerald-500/10 text-emerald-500'];
                            @endphp
                            <span class="badge {{ $roleColors[$member->role] ?? 'bg-gray-500/10 text-gray-500' }}">{{ __('messages.role_' . $member->role) }}</span>
                        </td>
                        <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $member->created_at->format('d M, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
