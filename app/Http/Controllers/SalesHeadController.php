<?php

namespace App\Http\Controllers;

use App\Exports\DeliveryRoutesExport;
use App\Services\DeliveryService;
use App\Services\ReportService;
use App\Services\ExportService;
use App\Services\ManualDeliveryService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalesHeadController extends Controller
{
    public function __construct(
        protected DeliveryService $deliveryService,
        protected ReportService $reportService,
        protected ExportService $exportService,
        protected ManualDeliveryService $manualDeliveryService,
    ) {}

    public function members()
    {
        $members = \App\Models\User::orderBy('role')->orderBy('name')->get();
        return view('saleshead.members.index', compact('members'));
    }

    public function dashboard()
    {
        $stats = [
            'total_routes' => \App\Models\DeliveryRoute::count(),
            'pending_routes' => \App\Models\DeliveryRoute::where('status', 'pending')->count(),
            'in_progress_routes' => \App\Models\DeliveryRoute::where('status', 'in_progress')->count(),
            'completed_routes' => \App\Models\DeliveryRoute::where('status', 'completed')->count(),
            'blocked_routes' => \App\Models\DeliveryRoute::where('status', 'blocked')->count(),
        ];

        $recentRoutes = $this->deliveryService->getRecentRoutes();

        return view('saleshead.dashboard', compact('stats', 'recentRoutes'));
    }

    public function reports()
    {
        $deliveryBoys = $this->reportService->getDeliveryBoys();
        $routes = $this->deliveryService->getFilteredRoutes(
            request()->only(['delivery_boy_id', 'status', 'from_date', 'to_date']),
            perPage: 15
        );
        return view('saleshead.reports.index', compact('deliveryBoys', 'routes'));
    }

    public function viewRoute(\App\Models\DeliveryRoute $route)
    {
        $route->load('deliveryBoy', 'stops');
        return view('saleshead.route-detail', compact('route'));
    }

    public function exportReport(Request $request)
    {
        $filters = $request->only(['delivery_boy_id', 'status', 'from_date', 'to_date']);
        $format = $request->format ?? 'pdf';

        return match ($format) {
            'csv' => $this->exportService->csv($this->deliveryService->getFilteredRoutes($filters)),
            'xlsx' => Excel::download(new DeliveryRoutesExport($filters), 'delivery_routes_export_' . now()->format('Y_m_d_His') . '.xlsx'),
            default => view('saleshead.reports.export', ['routes' => $this->deliveryService->getFilteredRoutes($filters)]),
        };
    }

    public function manualDeliveries()
    {
        $deliveries = $this->manualDeliveryService->getFiltered(
            request()->only(['delivery_boy_id', 'from_date', 'to_date'])
        );
        $deliveryBoys = $this->manualDeliveryService->getDeliveryBoys();
        return view('saleshead.manual-deliveries.index', compact('deliveries', 'deliveryBoys'));
    }

    public function createManualDelivery()
    {
        $deliveryBoys = $this->manualDeliveryService->getDeliveryBoys();
        return view('saleshead.manual-deliveries.create', compact('deliveryBoys'));
    }

    public function storeManualDelivery(Request $request)
    {
        $request->validate([
            'delivery_boy_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'note' => 'required|string',
            'delivery_date' => 'required|date',
            'delivery_time' => 'required',
            'total_km' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:5120',
        ]);

        $this->manualDeliveryService->create(
            $request->only(['delivery_boy_id', 'title', 'note', 'delivery_date', 'delivery_time', 'total_km']),
            $request->file('image')
        );

        return redirect()->route('saleshead.manual-deliveries')
            ->with('success', __('messages.manual_delivery_created'));
    }
}
