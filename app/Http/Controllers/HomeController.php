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
    public function getAvailablePlans()
    {
        $user = Auth::user();

        if ($user && $user->userable_type === 'App\Models\Client') {
            $client = \App\Models\Client::find($user->userable_id);

            $availablePlans = \App\Models\Plan::whereDoesntHave('clients', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })->get();

            return response()->json($availablePlans);
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
            $client = \App\Models\Client::find($user->userable_id);

            if ($client) {
                $myPlans = $client->plans()
                    ->select('plans.*', 'client_plan.numbering')
                    ->get();

                return response()->json($myPlans);
            }
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
}
