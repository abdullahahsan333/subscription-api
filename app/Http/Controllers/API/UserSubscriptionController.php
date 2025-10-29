<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Carbon;

class UserSubscriptionController extends Controller
{
    public function subscribe(SubscribeRequest $request)
    {
        $user = auth()->user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        $start = Carbon::now();
        $end = $start->copy()->addDays($plan->duration);

        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'start_date' => $start,
            'end_date' => $end,
            'status' => 'active',
        ]);

        $user->update(['subscription_status' => 'active']);

        // return encrypt_response(['message' => 'Subscribed successfully', 'subscription' => $subscription]);
        return response()->json([
            'message' => 'Subscribed successfully',
            'subscription' => $subscription
        ]);
    }

    public function mySubscriptions()
    {
        // return encrypt_response(auth()->user()->subscriptions()->with('plan')->get());
        return response()->json(auth()->user()->subscriptions()->with('plan')->get());
    }
}
