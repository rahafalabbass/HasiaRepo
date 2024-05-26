<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\subscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // not use now
    public function simulatePayment(Request $request)
    {
        try {
            // Assuming the authenticated user is making a payment for a specific subscription
            $user = Auth::user();
            $subscriptionId = $request->input('subscription_id');

            // Check if the subscription exists
            $subscription = subscriptions::findOrFail($subscriptionId);

            // Create a new payment record
            $payment = Payment::create([
                'terminalRef' => Str::random(10),
                'amount' => 100.00, // Example amount
                'status' => 'Pending', // Initial status
                'currency' => 'USD',
                'language' => 'en',
                'amountRef' => Str::random(10),
                'transactionNo' => Str::random(10),
                'orderRef' => Str::random(10),
                'message' => 'Payment is pending',
                'is_success' => false, // Payment is initially not successful
                'token' => Str::random(32),
                'paidDate' => null,
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'status_id' => null, // Will be updated after payment is processed
            ]);

            // Simulate the payment process (e.g., after some time or external API callback)
            // For demonstration purposes, we assume the payment is successful
            $payment->update([
                'status' => 'Paid',
                'is_success' => true,
                'paidDate' => now(),
                'status_id' => 1, // Assuming status_id 1 means 'Paid'
            ]);

            // Update the payment status record
            $paymentStatus = PaymentStatus::where('subscription_id', $subscriptionId)->first();
            if ($paymentStatus) {
                $paymentStatus->update([
                    'firstBatch' => 1, // Example update for first batch
                ]);
            }

            return response()->json(['message' => 'Payment simulated successfully', 'payment' => $payment], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => 'Error simulating payment', 'error' => $ex->getMessage()], 500);
        }
    }
}

