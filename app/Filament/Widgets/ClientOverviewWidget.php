<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ClientOverviewWidget   extends ChartWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Clients by Month';  // Set the widget title
    
    // protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected function getData(): array
    {
        // Get the counts of clients, grouped by the month they were created
        $data = Client::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month')
            ->toArray();

        // Ensure all months are included, even if a particular month has no clients
        $formattedData = [];
        for ($i = 1; $i <= 12; $i++) {
            $formattedData[$i] = $data[$i] ?? 0;  // Default to 0 if no data for a month
        }

        return [
            'datasets' => [
                [
                    'label' => 'Clients Added',
                    'data' => array_values($formattedData),  // Data for the chart
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',  // Optional: Background color for the bars
                    'borderColor' => 'rgba(75, 192, 192, 1)',  // Optional: Border color for the bars
                    'borderWidth' => 1,
                ],
            ],
            'labels' => [
                'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
            ],  // Labels for the x-axis (months)
        ];
    }

    protected function getType(): string
    {
        return 'bar';  // The chart type (could be 'line', 'bar', etc.)
    }

    // You can use columnSpan and rowSpan to manage the layout here
    public function getColumnSpan(): int
    {
        return 2; // Makes it take up half the available space
    }

    public function getRowSpan(): int
    {
        return 2; // Keeps the widget to one row
    }
}

