<?php

namespace App\Services;

use App\Models\DeliveryRoute;
use App\Models\ManualDelivery;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ManualDeliveryService
{
    public function __construct(
        protected ActivityService $activityService,
        protected ChatService $chatService,
    ) {}

    public function getAll()
    {
        return ManualDelivery::with('deliveryBoy', 'creator')
            ->orderBy('delivery_date', 'desc')
            ->orderBy('delivery_time', 'desc')
            ->get();
    }

    public function getFiltered(array $filters)
    {
        $query = ManualDelivery::with('deliveryBoy', 'creator');

        if (!empty($filters['delivery_boy_id'])) {
            $query->where('delivery_boy_id', $filters['delivery_boy_id']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('delivery_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('delivery_date', '<=', $filters['to_date']);
        }

        return $query->orderBy('delivery_date', 'desc')
            ->orderBy('delivery_time', 'desc')
            ->get();
    }

    public function getDeliveryBoys()
    {
        return User::where('role', 'deliveryboy')->orderBy('name')->get();
    }

    public function create(array $data, $image = null): ManualDelivery
    {
        $imagePath = null;
        if ($image) {
            $imagePath = $image->store('manual-deliveries', 'public');
        }

        $boy = User::find($data['delivery_boy_id']);

        $manual = ManualDelivery::create([
            'delivery_boy_id' => $data['delivery_boy_id'],
            'title' => $data['title'],
            'note' => $data['note'],
            'delivery_date' => $data['delivery_date'],
            'delivery_time' => $data['delivery_time'],
            'total_km' => $data['total_km'] ?? 0,
            'image_path' => $imagePath,
            'created_by' => Auth::id(),
        ]);

        $this->syncDeliveryRoute($manual, $boy, $imagePath);

        $creator = Auth::user();
        $this->activityService->log(
            'manual_delivery_created',
            "Manual delivery '{$manual->title}' created for {$boy->name} by {$creator->name}"
        );

        $this->chatService->sendMessage("📦 Manual delivery created: {$manual->title} → {$boy->name}");

        return $manual;
    }

    private function syncDeliveryRoute(ManualDelivery $manual, ?User $boy, ?string $imagePath): void
    {
        if (!$boy) {
            return;
        }

        DeliveryRoute::create([
            'delivery_boy_id' => $boy->id,
            'from_location' => 'Office',
            'to_location' => $manual->title,
            'total_km' => $manual->total_km,
            'boc' => Str::limit($manual->note, 255),
            'image_path' => $imagePath,
            'status' => 'completed',
            'is_manual' => true,
            'started_at' => now(),
        ]);
    }
}
