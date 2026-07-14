@extends('layouts.app')

@section('title', __('messages.edit_member'))

@section('content')
<div class="max-w-lg mx-auto">
    <div class="page-header">
        <h1>{{ __('messages.edit_member') }}</h1>
        <p>{{ __('messages.edit_member_desc') }}</p>
    </div>

    <div class="glass-card p-8">
        <form action="{{ route('admin.members.update', $member) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.full_name') }}</label>
                <input type="text" name="name" required class="glass-input" value="{{ old('name', $member->name) }}">
                @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.new_pin') }} <span class="text-gray-400 font-normal">{{ __('messages.leave_blank') }}</span></label>
                <input type="text" name="pin" maxlength="4" class="glass-input" placeholder="{{ __('messages.leave_blank') }}" inputmode="numeric" pattern="[0-9]*">
                @error('pin') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('messages.role') }}</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['admin' => __('messages.admin_role'), 'saleshead' => __('messages.sales_head'), 'deliveryboy' => __('messages.delivery_role')] as $val => $label)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="{{ $val }}" class="sr-only peer" {{ $member->role === $val ? 'checked' : '' }}>
                        <div class="p-4 rounded-2xl border-2 border-gray-200 dark:border-white/10 bg-white/50 dark:bg-white/5 text-center transition-all peer-checked:border-pink-500 peer-checked:bg-pink-500/5">
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ $label }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('role') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">{{ __('messages.update_member') }}</button>
        </form>
    </div>
</div>
@endsection
