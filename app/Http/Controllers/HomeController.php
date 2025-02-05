<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function getBrands()
    {
        $brands = DB::table('brands')
            ->select('brands.*')
            ->get();

        return response()->json($brands);
    }

    public function getBrandPlans($brandId)
    {
        $plans = DB::table('plans')
            ->join('brand_plan', 'plans.id', '=', 'brand_plan.plan_id')
            ->where('brand_plan.brand_id', $brandId)
            ->select('plans.*')
            ->get();

        return response()->json($plans);
    }
    /**
     * Fetch all plans not associated with the user.
     */
    /**
     * Fetch all plans not associated with the user.
     */
    /**
     * Fetch all plans not associated with the user.
     */
    public function getAvailablePlans()
    {
        $user = Auth::user();

        if ($user && $user->userable_type === 'App\Models\Client') {
            $clientId = $user->userable_id;

            // Get ALL plans with a left join to check association
            $allPlans = DB::table('plans')
                ->leftJoin('client_plan', function ($join) use ($clientId) {
                    $join->on('plans.id', '=', 'client_plan.plan_id')
                        ->where('client_plan.client_id', '=', $clientId);
                })
                // Select all plan fields and add a boolean for association
                ->select(
                    'plans.*',
                    DB::raw('CASE WHEN client_plan.client_id IS NOT NULL THEN true ELSE false END as is_joined')
                )
                ->get();

            return response()->json($allPlans);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }

    /**
     * Fetch plans associated with the user.
     */
    /**
     * Fetch plans associated with the user.
     */
    public function getMyPlans()
    {
        $user = Auth::user();

        if ($user && $user->userable_type === 'App\Models\Client') {
            $clientId = $user->userable_id;

            $myPlans = DB::table('plans')
                ->join('client_plan', 'plans.id', '=', 'client_plan.plan_id')
                ->where('client_plan.client_id', $clientId)
                ->leftJoin('coupon_plan', 'plans.id', '=', 'coupon_plan.plan_id')
                ->leftJoin('coupons', 'coupon_plan.coupon_id', '=', 'coupons.id')
                ->select(
                    'plans.*',
                    'client_plan.numbering',
                    DB::raw('COUNT(DISTINCT coupons.id) as coupon_count'),
                    DB::raw('JSON_ARRAYAGG(
                    IF(coupons.id IS NOT NULL,
                        JSON_OBJECT(
                            "id", coupons.id,
                            "code", coupons.code,
                            "name", coupons.name,
                            "description", coupons.description,
                            "redeem_at", coupons.redeem_at,
                            "end_date", coupons.end_date,
                            "type", coupons.type
                        ),
                        NULL
                    )
                ) as coupons')
                )
                ->groupBy('plans.id', 'client_plan.numbering')
                ->get()
                ->map(function ($plan) {
                    $coupons = json_decode($plan->coupons);
                    // Filter out null values and ensure it's an array
                    $plan->coupons = array_values(array_filter($coupons ?: []));
                    return $plan;
                });

            return response()->json($myPlans);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }

    /**
     * Associate a plan with the user.
     */
    /**
     * Associate a plan with the user.
     */
    public function associatePlan(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->userable_type === 'App\Models\Client') {
            $client = \App\Models\Client::find($user->userable_id);
            $plan = \App\Models\Plan::find($request->input('planId'));

            if (!$plan) {
                return response()->json(['message' => 'Plan not found'], 404);
            }

            // Check if client already has this plan
            if ($client->plans()->where('plan_id', $plan->id)->exists()) {
                return response()->json(['message' => 'Plan already associated'], 400);
            }

            // Generate numbering
            $sequence = str_pad(
                $plan->current_sequence + 1,
                $plan->consecutive_length,
                '0',
                STR_PAD_LEFT
            );
            $numbering = "{$plan->code}{$plan->prefix}{$sequence}";

            // Create association using relationship
            $client->plans()->attach($plan->id, [
                'numbering' => $numbering,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update plan sequence
            $plan->increment('current_sequence');

            return response()->json([
                'message' => 'Plan associated successfully!',
                'numbering' => $numbering
            ]);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    /**
     * Fetch all available coupons.
     */
    public function getCoupons()
    {
        // Get all coupons from the database
        $coupons = DB::table('coupons')
            ->select('coupons.*')
            ->get();

        return response()->json($coupons);
    }

    /**
     * Fetch coupons for a specific brand.
     */
    public function getBrandCoupons($brandId)
    {
        // Get coupons associated with plans that belong to this brand
        $coupons = DB::table('coupons')
            ->join('coupon_plan', 'coupons.id', '=', 'coupon_plan.coupon_id')
            ->join('brand_plan', 'coupon_plan.plan_id', '=', 'brand_plan.plan_id')
            ->where('brand_plan.brand_id', $brandId)
            ->select('coupons.*')
            ->distinct()
            ->get();

        return response()->json($coupons);
    }
}
