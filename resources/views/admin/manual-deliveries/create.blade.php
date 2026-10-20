@extends('layouts.app')

@section('title', __('messages.add_manual_delivery'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="page-header">
        <h1>{{ __('messages.add_manual_delivery') }}</h1>
        <p>{{ __('messages.add_manual_delivery_desc') }}</p>
    </div>

    <div class="glass-card p-6 sm:p-8">
        <form action="{{ route('admin.manual-deliveries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.delivery_boy') }} <span class="text-red-500">*</span></label>
                    <select name="delivery_boy_id" required class="glass-input">
                        <option value="">{{ __('messages.select_delivery_boy') }}</option>
                        @foreach($deliveryBoys as $boy)
                        <option value="{{ $boy->id }}">{{ $boy->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.title') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="glass-input" placeholder="{{ __('messages.title_placeholder') }}">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.delivery_date') }} <span class="text-red-500">*</span></label>
                        <input type="date" name="delivery_date" required class="glass-input">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.delivery_time') }} <span class="text-red-500">*</span></label>
                        <input type="time" name="delivery_time" required class="glass-input">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.km') }} <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="total_km" required class="glass-input" placeholder="0.00">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.note_req') }} <span class="text-red-500">*</span></label>
                    <textarea name="note" rows="4" required class="glass-input" placeholder="{{ __('messages.note_placeholder') }}"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.images') }} <span class="text-gray-400">({{ __('messages.optional') }})</span></label>
                    <input type="file" name="image" accept="image/*" class="glass-input">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-6 border-t border-gray-100 dark:border-white/5">
                <button type="submit" class="btn btn-primary flex-1">{{ __('messages.create') }}</button>
                <a href="{{ route('admin.manual-deliveries') }}" class="btn btn-secondary flex-1">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection