<?php

namespace App\Services;

class ExportService
{
    public function csv($routes): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $headers = [__('messages.delivery_boy'), __('messages.from'), __('messages.to'), __('messages.km'), __('messages.boc'), __('messages.status'), __('messages.manual'), __('messages.self'), __('messages.date')];
        $callback = function () use ($routes, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($routes as $route) {
                fputcsv($file, [
                    $route->deliveryBoy->name ?? __('messages.na'),
                    $route->from_location,
                    $route->to_location,
                    $route->total_km,
                    $route->boc ?? '-',
                    $route->status,
                    $route->is_manual ? __('messages.yes') : __('messages.no'),
                    $route->is_manual ? __('messages.no') : __('messages.yes'),
                    $route->created_at->format('Y-m-d'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="report-' . now()->format('Y-m-d-Hi') . '.csv"',
        ]);
    }
}
