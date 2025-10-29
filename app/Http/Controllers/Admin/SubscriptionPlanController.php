<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Validator;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::all();

        return response()->json([
            'status' => true,
            'message' => 'Subscription plans retrieved successfully.',
            'data' => $plans
        ], 200);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1', // in days
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create plan
        $plan = SubscriptionPlan::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Subscription plan created successfully.',
            'data' => $plan
        ], 201);
    }
    
    public function show($id)
    {
        $plan = SubscriptionPlan::find($id);

        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Subscription plan not found.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Subscription plan retrieved successfully.',
            'data' => $plan
        ], 200);
    }
    
    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::find($id);

        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Subscription plan not found.'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'price' => 'sometimes|numeric|min:0',
            'duration' => 'sometimes|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update plan
        $plan->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Subscription plan updated successfully.',
            'data' => $plan
        ], 200);
    }
    
    public function destroy($id)
    {
        $plan = SubscriptionPlan::find($id);

        if (!$plan) {
            return response()->json([
                'status' => false,
                'message' => 'Subscription plan not found.'
            ], 404);
        }

        $plan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Subscription plan deleted successfully.'
        ], 200);
    }
}
