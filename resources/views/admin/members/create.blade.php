@extends('layouts.app')

@section('title', __('messages.create_member'))

@section('content')
<div class="max-w-lg mx-auto">
    <div class="page-header">
        <h1>{{ __('messages.create_member') }}</h1>
        <p>{{ __('messages.create_member_desc') }}</p>
    </div>

    <div class="glass-card p-8">
        <form action="{{ route('admin.members.store') }}" method="POST" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').classList.add('opacity-50', 'cursor-not-allowed');">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.full_name') }}</label>
                <input type="text" name="name" required class="glass-input" placeholder="{{ __('messages.enter_member_name') }}" value="{{ old('name') }}">
                @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.security_pin') }}</label>
                <input type="text" name="pin" maxlength="4" required class="glass-input" placeholder="{{ __('messages.enter_pin') }}" inputmode="numeric" pattern="[0-9]*">
                @error('pin') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('messages.role') }}</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="admin" class="sr-only peer">
                        <div class="p-4 rounded-2xl border-2 border-gray-200 dark:border-white/10 bg-white/50 dark:bg-white/5 text-center transition-all peer-checked:border-pink-500 peer-checked:bg-pink-500/5">
                            <div class="w-10 h-10 rounded-full bg-purple-500/10 text-purple-500 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ __('messages.admin_role') }}</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="saleshead" class="sr-only peer">
                        <div class="p-4 rounded-2xl border-2 border-gray-200 dark:border-white/10 bg-white/50 dark:bg-white/5 text-center transition-all peer-checked:border-pink-500 peer-checked:bg-pink-500/5">
                            <div class="w-10 h-10 rounded-full bg-blue-500/10 text-blue-500 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ __('messages.sales_head') }}</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="deliveryboy" class="sr-only peer">
                        <div class="p-4 rounded-2xl border-2 border-gray-200 dark:border-white/10 bg-white/50 dark:bg-white/5 text-center transition-all peer-checked:border-pink-500 peer-checked:bg-pink-500/5">
                            <div class="w-10 h-10 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            </div>
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ __('messages.delivery_role') }}</span>
                        </div>
                    </label>
                </div>
                @error('role') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">{{ __('messages.create_member') }}</button>
        </form>
    </div>
</div>
@endsection
