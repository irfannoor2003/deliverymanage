<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRouteRequest;
use App\Http\Requests\StorePitStopRequest;
use App\Models\DeliveryRoute;
use App\Services\ChatService;
use App\Services\DeliveryService;
use App\Services\GeoFenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryBoyController extends Controller
{
    public function __construct(
        protected DeliveryService $deliveryService,
        protected GeoFenceService $geoFenceService,
        protected ChatService $chatService,
    ) {}

    public function dashboard()
    {
        if (Auth::user()->hasBlockedRoutes()) {
            session()->flash('error', 'You have blocked routes. Contact admin to unblock.');
        }

        $routes = $this->deliveryService->getDeliveryBoyRoutes();
        $stats = $this->deliveryService->getDeliveryBoyStats();
        return view('deliveryboy.dashboard', compact('routes', 'stats'));
    }

    public function createRoute()
    {
        if (Auth::user()->hasBlockedRoutes()) {
            return redirect()->route('deliveryboy.dashboard')
                ->with('error', 'You have blocked routes. Please contact admin.');
        }

        if (Auth::user()->hasActiveRoute()) {
            return redirect()->route('deliveryboy.dashboard')
                ->with('error', 'You already have an active delivery. Complete it first.');
        }

        return view('deliveryboy.routes.create');
    }

    public function storeRoute(StoreRouteRequest $request)
    {
        if (Auth::user()->hasBlockedRoutes()) {
            return back()->with('error', 'You have blocked routes. Please contact admin.');
        }

        // Geo-fence check
        if ($request->latitude && $request->longitude) {
            $error = $this->geoFenceService->validate((float) $request->latitude, (float) $request->longitude);
            if ($error) {
                return back()->with('error', $error);
            }
        }

        $route = $this->deliveryService->createRoute(
            $request->validated(),
            $request->file('image'),
            $request->file('audio')
        );

        $this->chatService->sendMessage(
            "🚚 New route created: {$route->to_location}"
        );

        if ($route->audio_path) {
            $this->chatService->sendMessage(null, null, $route->audio_path);
        }

        return redirect()->route('deliveryboy.dashboard')
            ->with('success', 'Route created successfully');
    }

    public function updateRouteStatus(DeliveryRoute $route, string $status)
    {
        if ($route->delivery_boy_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        if ($route->isBlocked()) {
            return back()->with('error', 'This route is blocked. Contact admin.');
        }

        $this->deliveryService->updateStatus($route, $status);

        if ($status === 'in_progress') {
            $this->chatService->sendMessage(
                "🚚 Delivery #{$route->id} started: {$route->from_location} → {$route->to_location}"
            );
            if ($route->audio_path) {
                $this->chatService->sendMessage(null, null, $route->audio_path);
            }
        }

        $message = match ($status) {
            'in_progress' => 'Delivery started',
            'completed' => 'Delivery completed',
            default => 'Route status updated',
        };

        return back()->with('success', $message);
    }

    public function completeRoute(Request $request, DeliveryRoute $route)
    {
        if ($route->delivery_boy_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        if ($route->isBlocked()) {
            return back()->with('error', 'This route is blocked. Contact admin.');
        }

        if ($route->status !== 'in_progress') {
            return back()->with('error', 'Route is not in progress.');
        }

        $request->validate([
            'total_km' => 'required|numeric|min:0',
            'boc' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = [
            'total_km' => $request->total_km,
            'boc' => $request->boc,
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('route-images', 'public');
        }

        $route->update($data);
        $this->deliveryService->updateStatus($route, 'completed');

        foreach ($route->stops as $stop) {
            $this->chatService->sendMessage("📍 Pit stop #{$stop->order}: {$stop->stop_name}");
            if ($stop->audio_path) {
                $this->chatService->sendMessage(null, null, $stop->audio_path);
            }
        }

        return redirect()->route('deliveryboy.dashboard')
            ->with('success', 'Delivery completed successfully');
    }

    public function addPitStop(StorePitStopRequest $request, DeliveryRoute $route)
    {
        $wantJson = $request->wantsJson() || $request->ajax();

        if ($route->delivery_boy_id !== Auth::id()) {
            return $wantJson
                ? response()->json(['message' => 'Unauthorized'], 403)
                : back()->with('error', 'Unauthorized');
        }

        if ($route->isBlocked()) {
            return $wantJson
                ? response()->json(['message' => 'This route is blocked.'], 403)
                : back()->with('error', 'This route is blocked.');
        }

        if ($route->status !== 'in_progress') {
            return $wantJson
                ? response()->json(['message' => 'Pit stops can only be added during an active delivery.'], 422)
                : back()->with('error', 'Pit stops can only be added during an active delivery.');
        }

        $data = $this->deliveryService->addPitStop(
            $route,
            $request->validated(),
            $request->file('image'),
            $request->file('audio')
        );

        if ($wantJson) {
            return response()->json(['success' => true, 'data' => $data]);
        }

        return back()->with('success', 'Pit stop added');
    }

    public function getPitStops(DeliveryRoute $route)
    {
        if ($route->delivery_boy_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stops = $route->stops()->orderBy('order')->get()->map(function ($stop) {
            return [
                'id' => $stop->id,
                'stop_name' => $stop->stop_name,
                'stop_km' => $stop->stop_km,
                'image_path' => $stop->image_path,
                'order' => $stop->order,
            ];
        });

        return response()->json($stops);
    }

    public function editRoute(DeliveryRoute $route)
    {
        if ($route->delivery_boy_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        $route->load('stops');

        return view('deliveryboy.routes.edit', compact('route'));
    }

    public function updateRoute(Request $request, DeliveryRoute $route)
    {
        if ($route->delivery_boy_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        $request->validate([
            'to_location' => 'required|string|max:255',
            'total_km' => 'nullable|numeric|min:0',
            'boc' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = [
            'to_location' => $request->to_location,
        ];

        if ($request->filled('total_km')) {
            $data['total_km'] = $request->total_km;
        }
        if ($request->filled('boc')) {
            $data['boc'] = $request->boc;
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('route-images', 'public');
        }

        $route->update($data);

        return redirect()->route('deliveryboy.dashboard')
            ->with('success', 'Route updated successfully');
    }

    public function updatePitStop(Request $request, DeliveryRoute $route, \App\Models\RouteStop $pitStop)
    {
        if ($route->delivery_boy_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        $request->validate([
            'stop_name' => 'required|string|max:255',
            'stop_km' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = [
            'stop_name' => $request->stop_name,
            'stop_km' => $request->stop_km ?? 0,
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('stop-images', 'public');
        }

        $pitStop->update($data);

        return back()->with('success', 'Pit stop updated');
    }

    public function deletePitStop(Request $request, DeliveryRoute $route, \App\Models\RouteStop $pitStop)
    {
        if ($route->delivery_boy_id !== Auth::id()) {
            return $request->ajax()
                ? response()->json(['error' => 'Unauthorized'], 403)
                : back()->with('error', 'Unauthorized');
        }

        $pitStop->delete();

        return $request->ajax()
            ? response()->json(['success' => true])
            : back()->with('success', 'Pit stop deleted');
    }
}
