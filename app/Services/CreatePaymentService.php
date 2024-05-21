<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class PaymentService
{
    public function createPayment(array $requestData)
    {
        // إرسال الطلب إلى الرابط المطلوب باستخدام Http
        $response = Http::post('https://egate-t.fatora.me/api/create-payment', $requestData);

        return $response->json();
    }
}




class PaymentService2{
    // API_SECRET_KEY=your_api_secret_key_here  write in file .env

    // public function makePayment($amount, $currency, $TransactionNum, $userName, $password){
    // //  $apiKey = env('API_SECRET_KEY');
    // // اضافة الخدمات بالهيدر بدل ارساله كجزء من بيانات طلب خدمة
    // //   $response = Http::withHeaders([
    // //     'Authorization' => 'Bearer ' . $apiKey,
    // // ])->post('https://api.example.com/payment', [
    // //     // بيانات الدفع هنا
    // // ]);
    
        // $response = Http::post('https://egate-t.fatora.me/api/create-payment', [
        //     'amount' => $amount,
        //     'currency' => $currency,
        //     'TransactionNum' => $TransactionNum, // رقم المعاملة
        // //     'userName' => $userName,
        // //     'password' => $password,
        // ]);
        // return $response;

    // }
    public function makePayment($amount, $currency, $TransactionNum)
{
    try {
        // 1. Validate input
        $this->validateInput($amount, $currency, $TransactionNum, $userName, $password);

        // 2. Log the start of payment process
        Log::info('Payment process started.');

        // 3. Make the payment request
        $response = Http::post('https://egate-t.fatora.me/api/create-payment', [
            'amount' => $amount,
            'currency' => $currency,
            'TransactionNum' => $TransactionNum, // رقم المعاملة
        
        ]);

        // 4. Check if payment was successful
        if ($response->successful()) {
            // 5. Log payment success
            Log::info('Payment successful.');
            return $response->json();
        } else {
            // 6. Log payment failure
            Log::error('Payment failed: ' . $response->status());
            return response()->json(['error' => 'Payment failed'], $response->status());
        }
    } catch (\Exception $e) {
        // 7. Handle exceptions
        Log::error('Payment error: ' . $e->getMessage());
        return response()->json(['error' => 'Payment error'], 500);
    }
}

private function validateInput($amount, $currency, $TransactionNum, $userName, $password)
{
    // Initialize an array to store validation errors
    $errors = [];

    // Check if amount is numeric and greater than zero
    if (!is_numeric($amount) || $amount <= 0) {
        $errors[] = 'The amount must be a positive number.';
    }

    // Check if currency is a valid currency code (you can add more currency codes as needed)
    $validCurrencies = ['USD', 'EUR', 'GBP'];
    if (!in_array($currency, $validCurrencies)) {
        $errors[] = 'Invalid currency.';
    }

    // Check if TransactionNum is a valid transaction number (you can add more validation logic as needed)
    if (strlen($TransactionNum) !== 16 || !is_numeric($TransactionNum)) {
        $errors[] = 'Invalid transaction number.';
    }

    // Check if userName and password are not empty
    if (empty($userName) || empty($password)) {
        $errors[] = 'Username and password are required.';
    }

    // If there are validation errors, throw an exception with the errors
    if (!empty($errors)) {
        throw new \Exception(implode(' ', $errors));
    }
}




    
}
