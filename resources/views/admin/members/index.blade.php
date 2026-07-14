@extends('layouts.app')

@section('title', __('messages.members'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1>{{ __('messages.members') }}</h1>
            <p>{{ __('messages.members_management_desc') }}</p>
        </div>
        <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('messages.add_member') }}
        </a>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.user') }}</th>
                        <th>{{ __('messages.role') }}</th>
                        <th>{{ __('messages.pin') }}</th>
                        <th>{{ __('messages.created') }}</th>
                        <th class="text-right">{{ __('messages.actions') }}</th>
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
                            <span class="badge {{ $roleColors[$member->role] ?? 'bg-gray-500/10 text-gray-500' }}">{{ ucfirst($member->role) }}</span>
                        </td>
                        <td>
                            <code class="text-sm tracking-widest text-gray-400 bg-gray-100 dark:bg-white/5 px-2 py-1 rounded-md">••••</code>
                        </td>
                        <td class="text-gray-500 dark:text-gray-400 text-sm">{{ $member->created_at->format('d M, Y') }}</td>
                        <td class="text-right">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.members.edit', $member) }}" class="p-2 text-gray-400 hover:text-indigo-500 hover:bg-indigo-500/10 rounded-xl transition-all" title="{{ __('messages.edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                <form action="{{ route('admin.members.destroy', $member) }}" method="POST" onsubmit="if(!confirm('{{ __('messages.delete_member_confirm') }}')) return false; var btn = this.querySelector('button[type=submit]'); btn.disabled = true; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-rose-500 hover:bg-rose-500/10 rounded-xl transition-all" title="{{ __('messages.delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
