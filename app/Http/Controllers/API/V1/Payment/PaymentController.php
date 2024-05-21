<?php

namespace App\Http\Controllers\API\V1\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Transaction;
use App\Services\PaymentService;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentController extends Controller
{
    use GeneralTrait;

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function createPayment(PaymentRequest $request)
    {
        try {
            $paymentData = $request->validated();
            $response = $this->paymentService->createPayment($paymentData); 
            if ($response['success']) {
                // تخزين بيانات الدفع
                $transaction = Transaction::create([
                    'terminalId' => $paymentData['terminalId'],
                    'lang' => $paymentData['lang'],
                    'amount' => $request->input('amount'),
                    'sub_id' => $request->subscriptionId // confirm???
                ]);
                if (!$transaction) {
                    return $this->paymentResponse($transaction, 'Error', 100);
                }
                return $this->paymentResponse($transaction, 'Success', 0);
            } else {
                return $this->paymentResponse('', 400);
            }
        } catch (ModelNotFoundException $e) {
            return $this->buildResponse($e, 'Error', 'حدث خطأ في عملية الغاء ', 404);
        }
    }

    public function payOrder()
    {
        $data = [
            "lang"=> "en",
            "terminalId" => "XXXXXXXX",
            "amount"=> 100,
            "cardNumber"=> "9760AAXXXXXXXXXX",
            "expiryDate"=> "YYYYMM",
            "callbackURL"=> "close_button_url_path",
            "triggerURL"=> "auto_invoke_trigger_url_path",
            "savedCards"=>"S",
            "appUser"=>"username_from_app",
            "notes"=> "additional_notes",
        ];

      return  $this->paymentService->sendPayment($data);
        
    }
}
