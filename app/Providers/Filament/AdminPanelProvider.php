<?php

namespace App\Providers\Filament;

use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Pxlrbt\FilamentSpotlight\SpotlightPlugin;

use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Http\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->login()
            ->colors([
                'primary' => Color::Sky,
                
            ])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->navigationItems([
                NavigationItem::make('Blog')
                    ->url('https://kprog.app', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-pencil-square')
                    ->group('External')
                    ->sort(2),
            ])
            // ->pages([
            //     \App\Filament\Pages\Dashboard::class,  // Specify Dashboard page here
            // ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Settings')
                    ->url('')  // Empty URL for now, you can replace this later
                    ->icon('heroicon-o-cog-6-tooth'),
                'logout' => MenuItem::make()->label('Log Out'),
            ])
            ->breadcrumbs(false)
            ->font(family: 'Poppins')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                // Remove AuthenticateSession middleware if unnecessary
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            
            ->authMiddleware([
                Authenticate::class,  // Correct Filament middleware
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        $user = auth()->user();

        if (!$user) return [];

        if ($user->hasRole('CEO') || $user->hasRole('Admin')) {
            return [
                \App\Filament\Widgets\PolicyOverviewWidget::class,
                \App\Filament\Widgets\ClientOverviewWidget::class,
                // More widgets for Admin if needed
            ];
        } elseif ($user->hasRole('Team Member')) {
            return [
                \App\Filament\Widgets\PolicyOverviewWidget::class,
            ];
        }

        return [];
    }
}
