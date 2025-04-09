<?php

namespace App\Filament\Widgets;

use App\Models\Policy;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Proposal;
use App\Models\Quotation;

class PolicyOverviewWidget  extends BaseWidget
{
    protected static ?int $sort = 2; // Control the order of this widget
    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Policies', Policy::count())
                ->description('Number of Policies')
                ->descriptionIcon('heroicon-o-shield-check') // Change the icon
                ->color('warning') // Warning color for visibility
                ->chart([6, 7, 8, 9, 10, 6, 5]),

                Stat::make('Total Proposals', Proposal::count())
                ->description('Number of proposals submitted')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->chart([3, 2, 4, 6, 5, 7, 8]),

                Stat::make('Total Quotations', Quotation::count())
                ->description('Number of Quotations')
                ->descriptionIcon('heroicon-o-currency-dollar')  // Change the icon
                ->color('danger')  // Use danger color for emphasis
                ->chart([8, 7, 6, 5, 4, 6, 7]), // Optional trend chart
        ];
    }

    // You can use columnSpan and rowSpan to manage the layout here
    public function getColumnSpan(): int
    {
        return 3; // Makes it take up half the available space
    }

    public function getRowSpan(): int
    {
        return 3; // Keeps the widget to one row
    }
}

