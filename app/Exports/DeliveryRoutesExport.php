<?php

namespace App\Exports;

use App\Models\DeliveryRoute;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DeliveryRoutesExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $user = Auth::user();

        $query = DeliveryRoute::with(['deliveryBoy', 'stops']);

        if ($user->isDeliveryBoy()) {
            $query->where('delivery_boy_id', $user->id);
        } elseif (!empty($this->filters['delivery_boy_id'])) {
            $query->where('delivery_boy_id', $this->filters['delivery_boy_id']);
        }

        if (!empty($this->filters['from_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['from_date']);
        }

        if (!empty($this->filters['to_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['to_date']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        $routes = $query->orderBy('created_at')->get();

        $rows = collect();
        foreach ($routes as $route) {
            $rows->push($route);
            foreach ($route->stops as $stop) {
                $rows->push($stop);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Type',
            'Delivery ID',
            'Delivery Boy',
            'From',
            'To',
            'Note / Purpose',
            'Status',
            'KM',
            'Total Trip KM (Pitstops)',
            'Date',
        ];
    }

    public function map($row): array
    {
        if ($row instanceof \App\Models\RouteStop) {
            return [
                "Pitstop of Delivery #{$row->delivery_route_id}",
                $row->delivery_route_id,
                $row->deliveryRoute->deliveryBoy->name ?? '-',
                $row->deliveryRoute->from_location ?? '-',
                $row->deliveryRoute->to_location ?? '-',
                $row->stop_name,
                '-',
                $row->stop_km,
                '-',
                $row->created_at ? $row->created_at->format('Y-m-d H:i') : '',
            ];
        }

        $totalPitstopKm = $row->stops->sum('stop_km');

        return [
            'Delivery',
            $row->id,
            $row->deliveryBoy->name ?? '-',
            $row->from_location,
            $row->to_location,
            $row->boc ?? '-',
            ucfirst(str_replace('_', ' ', $row->status)),
            $row->total_km,
            $totalPitstopKm,
            $row->created_at->format('Y-m-d H:i'),
        ];
    }
}
