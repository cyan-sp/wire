<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Plan;
use App\Events\ClientPlanRegistered;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run()
    {
        // Create Coupons
        $coupon1 = Coupon::create([
            'code' => 'DISC2024',
            'type' => 'promo',
            'name' => '20% Discount',
            'description' => 'Get 20% off on your next purchase',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'redeem_at' => 'All branches',
            'image' => 'discount.png',
        ]);

        $coupon2 = Coupon::create([
            'code' => 'FREESHIP',
            'type' => 'offer',
            'name' => 'Free Shipping',
            'description' => 'Free shipping on orders above $50',
            'start_date' => now(),
            'end_date' => now()->addDays(15),
            'redeem_at' => 'Online only',
            'image' => 'freeshipping.png',
        ]);

        // Find Plans
        $plan1 = Plan::find(1);
        $plan2 = Plan::find(2);

        // Dispatch Events After Attaching Plans
        if ($plan1) {
            $coupon1->plans()->attach($plan1->id);
            // event(new ClientPlanRegistered($coupon1, $plan1));
        }

        if ($plan2) {
            $coupon2->plans()->attach($plan2->id);
            // event(new ClientPlanRegistered($coupon2, $plan2));
        }
    }
}
