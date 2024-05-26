<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\subscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;



class paidController extends Controller
{
    public function simulatePayment(Request $request)
    {
        try {
            // Assuming the authenticated user is making a payment for a specific subscription
            $user = Auth::user();
            $subscription_id = $request->input('subscription_id');

            // Check if the subscription exists
            $subscription = subscriptions::findOrFail($subscription_id);

            // Create a new payment record
            $term =rand(0000,9999);
            $temp=rand(9999,9999);
            $paymentStatus =PaymentStatus::find(1);
            $status_id =$paymentStatus->id;
            $payment = Payment::create([
                'terminalRef' => rand(0000,9999),
                'amount' => 100.00, // Example amount
                'status' => 'Pending', // Initial status
                'currency' => 'USD',
                'language' => 'en',
                'amountRef' => $term,
                'transactionNo' => $temp,
                'orderRef' => rand(0000,9999),
                'message' => 'Payment is pending',
                'is_success' => false, // Payment is initially not successful
                'token' => Str::random(32),
                'paidDate' => now(),
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'status_id' => $status_id, // Will be updated after payment is processed
            ]);

            // Simulate the payment process (e.g., after some time or external API callback)
            // For demonstration purposes, we assume the payment is successful
            $payment->update([
                'status' => 'Paid',
                'is_success' => true,
                'paidDate' => now(),
                'status_id' => 8, // Assuming status_id 1 means 'Paid'
            ]);

            // Update the payment status record
            $paymentStatus = PaymentStatus::where('subscription_id', $subscription_id)->first();
            if ($paymentStatus) {
                $paymentStatus->update([
                    'subscription_fee' => 1, // Example update for first batch
                ]);
            }

            return response()->json(['message' => 'Payment simulated successfully', 'payment' => $payment], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => 'Error simulating payment', 'error' => $ex->getMessage()], 500);
        }
    }

}
