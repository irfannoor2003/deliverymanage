<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\DeliveryService;
use App\Services\ReportService;
use App\Services\UserService;
use App\Services\ActivityService;
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
        return redirect()->route('admin.members')->with('success', 'Member created successfully');
    }

    public function editMember(User $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    public function updateMember(UpdateUserRequest $request, User $member)
    {
        $this->userService->update($member, $request->validated());
        return redirect()->route('admin.members')->with('success', 'Member updated successfully');
    }

    public function destroyMember(User $member)
    {
        if (Auth::id() === $member->id) {
            return back()->with('error', 'You cannot delete yourself');
        }
        $this->userService->delete($member);
        return redirect()->route('admin.members')->with('success', 'Member deleted successfully');
    }

    public function reports()
    {
        $routes = $this->deliveryService->getAllRoutes();
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
        return back()->with('success', 'Delivery #' . $route->id . ' unblocked');
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
}
