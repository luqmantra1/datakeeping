<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\PolicyOverviewWidget;
use App\Filament\Widgets\ClientOverviewWidget;

class Dashboard extends BaseDashboard
{
    // You can define widgets and customize them for each user role
    protected function getHeaderWidgets(): array
    {
        $user = auth()->user();

        if (!$user) return [];

        // Example of showing different widgets based on user roles
        if ($user->hasRole('CEO') || $user->hasRole('Admin')) {
            return [
                \App\Filament\Widgets\PolicyOverviewWidget::class,
                \App\Filament\Widgets\ClientOverviewWidget::class,
                // Add more widgets if needed
            ];
        } elseif ($user->hasRole('Team Member')) {
            return [
                \App\Filament\Widgets\PolicyOverviewWidget::class, // You can limit widgets for Team Members
            ];
        }

        return [];
    }
}
