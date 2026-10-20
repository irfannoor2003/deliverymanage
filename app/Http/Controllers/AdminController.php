<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\DeliveryService;
use App\Services\ReportService;
use App\Services\UserService;
use App\Services\ActivityService;
use App\Services\ManualDeliveryService;
use App\Exports\DeliveryRoutesExport;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected DeliveryService $deliveryService,
        protected ReportService $reportService,
        protected ActivityService $activityService,
        protected ExportService $exportService,
        protected ManualDeliveryService $manualDeliveryService,
    ) {}

    public function dashboard()
    {
        $stats = $this->userService->getStats();
        $recentRoutes = $this->deliveryService->getRecentRoutes();
        return view('admin.dashboard', compact('stats', 'recentRoutes'));
    }

    public function members()
    {
        $members = $this->userService->getAll();
        return view('admin.members.index', compact('members'));
    }

    public function createMember()
    {
        return view('admin.members.create');
    }

    public function storeMember(StoreUserRequest $request)
    {
        $this->userService->create($request->validated());
        return redirect()->route('admin.members')->with('success', __('messages.member_created'));
    }

    public function editMember(User $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    public function updateMember(UpdateUserRequest $request, User $member)
    {
        $this->userService->update($member, $request->validated());
        return redirect()->route('admin.members')->with('success', __('messages.member_updated'));
    }

    public function destroyMember(User $member)
    {
        if (Auth::id() === $member->id) {
            return back()->with('error', __('messages.cannot_delete_self'));
        }
        $this->userService->delete($member);
        return redirect()->route('admin.members')->with('success', __('messages.member_deleted'));
    }

    public function reports()
    {
        $routes = $this->deliveryService->getFilteredRoutes(
            request()->only(['delivery_boy_id', 'status', 'from_date', 'to_date']),
            perPage: 15
        );
        $deliveryBoys = $this->reportService->getDeliveryBoys();
        return view('admin.reports.index', compact('routes', 'deliveryBoys'));
    }

    public function exportReport(Request $request)
    {
        $filters = $request->only(['delivery_boy_id', 'status', 'from_date', 'to_date']);
        $format = $request->format ?? 'pdf';

        return match ($format) {
            'csv' => $this->exportService->csv($this->deliveryService->getFilteredRoutes($filters)),
            'xlsx' => Excel::download(new DeliveryRoutesExport($filters), 'delivery_routes_export_' . now()->format('Y_m_d_His') . '.xlsx'),
            default => view('admin.reports.export', ['routes' => $this->deliveryService->getFilteredRoutes($filters)]),
        };
    }

    public function blockedDeliveries()
    {
        $routes = $this->deliveryService->getBlockedRoutes();
        return view('admin.blocked', compact('routes'));
    }

    public function unblockDelivery(User $user, \App\Models\DeliveryRoute $route)
    {
        $this->deliveryService->unblock($route);
        return back()->with('success', __('messages.delivery_unblocked_msg', ['id' => $route->id]));
    }

    public function viewRoute(\App\Models\DeliveryRoute $route)
    {
        $route->load('deliveryBoy', 'stops');
        return view('admin.route-detail', compact('route'));
    }

    public function activityLogs(Request $request)
    {
        $logs = $this->activityService->getAll(
            $request->only(['user_id', 'action', 'from_date', 'to_date']),
            (int) ($request->per_page ?? 20)
        );
        return view('admin.activity-logs', compact('logs'));
    }

    public function manualDeliveries()
    {
        $deliveries = $this->manualDeliveryService->getFiltered(
            request()->only(['delivery_boy_id', 'from_date', 'to_date'])
        );
        $deliveryBoys = $this->manualDeliveryService->getDeliveryBoys();
        return view('admin.manual-deliveries.index', compact('deliveries', 'deliveryBoys'));
    }

    public function createManualDelivery()
    {
        $deliveryBoys = $this->manualDeliveryService->getDeliveryBoys();
        return view('admin.manual-deliveries.create', compact('deliveryBoys'));
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

        return redirect()->route('admin.manual-deliveries')
            ->with('success', __('messages.manual_delivery_created'));
    }
}
