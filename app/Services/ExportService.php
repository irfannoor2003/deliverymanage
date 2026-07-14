<?php

namespace App\Services;

use App\Models\DeliveryRoute;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportService
{
    public function pdf($routes, string $view): \Illuminate\Http\Response
    {
        $summary = app(ReportService::class)->getSummary($routes);
        $pdf = Pdf::loadView($view, compact('routes', 'summary'));
        return $pdf->download('report-' . now()->format('Y-m-d-Hi') . '.pdf');
    }

    public function csv($routes): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $headers = ['Delivery Boy', 'From', 'To', 'KM', 'Note', 'Status', 'Date'];
        $callback = function () use ($routes, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($routes as $route) {
                fputcsv($file, [
                    $route->deliveryBoy->name ?? 'N/A',
                    $route->from_location,
                    $route->to_location,
                    $route->total_km,
                    $route->boc ?? '-',
                    $route->status,
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
