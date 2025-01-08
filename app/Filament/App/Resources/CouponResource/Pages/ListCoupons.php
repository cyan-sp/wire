<?php

namespace App\Filament\App\Resources\CouponResource\Pages;

use App\Filament\App\Resources\CouponResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListCoupons extends ListRecords
{
    protected static string $resource = CouponResource::class;

    protected static ?string $tenantRelationshipName = 'plans';

    protected function getHeaderActions(): array
    {
        // Get the current tenant (plan) through Filament
        $currentPlan = Filament::getTenant();
    
        \Log::info('Tenant/Plan Information', [
            'tenant_id' => $currentPlan?->id,
            'tenant_name' => $currentPlan?->name,
            'expected_silver_id' => 3,  // From your data
            'tenant_raw' => $currentPlan
        ]);

        $availableCoupons = $currentPlan?->available_coupons ?? 0;

        return [
            Actions\CreateAction::make()
                ->icon('heroicon-m-ticket')
                ->badge($availableCoupons)
            // ... rest of your code
        ];
    }
}
