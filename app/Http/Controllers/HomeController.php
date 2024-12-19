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
    public function getAvailablePlans()
    {
        $user = Auth::user();

        if ($user && $user->userable_type === 'App\Models\Client') {
            $clientId = $user->userable_id;

            $availablePlans = DB::table('plans')
                ->leftJoin('client_plan', function ($join) use ($clientId) {
                    $join->on('plans.id', '=', 'client_plan.plan_id')
                        ->where('client_plan.client_id', '=', $clientId);
                })
                ->whereNull('client_plan.plan_id') // Only plans not associated with the client
                ->select('plans.*')
                ->get();

            return response()->json($availablePlans);
        }

        return response()->json(['message' => 'Forbidden'], 403); // Forbidden for non-clients
    }

    /**
     * Fetch plans associated with the user.
     */
    public function getMyPlans()
    {
        $user = Auth::user();

        if ($user && $user->userable_type === 'App\Models\Client') {
            $clientId = $user->userable_id;

            $myPlans = DB::table('client_plan')
                ->join('plans', 'client_plan.plan_id', '=', 'plans.id')
                ->where('client_plan.client_id', $clientId)
                ->select('plans.*', 'client_plan.numbering')
                ->get();

            return response()->json($myPlans);
        }

        return response()->json(['message' => 'Forbidden'], 403); // Forbidden for non-clients
    }

    /**
     * Associate a plan with the user.
     */
    public function associatePlan(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->userable_type === 'App\Models\Client') {
            $clientId = $user->userable_id;
            $planId = $request->input('planId');

            // Validate if the plan exists
            $plan = DB::table('plans')->where('id', $planId)->first();
            if (!$plan) {
                return response()->json(['message' => 'Plan not found'], 404);
            }

            // Check if the client is already associated with the plan
            $existingAssociation = DB::table('client_plan')
                ->where('client_id', $clientId)
                ->where('plan_id', $planId)
                ->exists();

            if ($existingAssociation) {
                return response()->json(['message' => 'Plan already associated'], 400);
            }

            // Generate numbering
            $sequence = str_pad($plan->current_sequence + 1, $plan->consecutive_length, '0', STR_PAD_LEFT);
            $numbering = "{$plan->code}{$plan->prefix}{$sequence}";

            // Create the association
            DB::table('client_plan')->insert([
                'client_id' => $clientId,
                'plan_id' => $planId,
                'numbering' => $numbering,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update the plan's current sequence
            DB::table('plans')->where('id', $planId)->update([
                'current_sequence' => $plan->current_sequence + 1,
            ]);

            return response()->json(['message' => 'Plan associated successfully!', 'numbering' => $numbering]);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
