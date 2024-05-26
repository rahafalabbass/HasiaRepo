<?php

use App\Traits\GeneralTrait;
use Closure;
use Facade\FlareClient\Http\Client as HttpClient;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class PaymentService{
    use GeneralTrait;

public function __construct($paymentDate){
 // test environment 
 $response = Http::post('https://fmp-t.fatora.me/api/create-payment',[
  'json' => $paymentDate
]);
}
    private function buildRequest($uri, $method,$data=[])
    {
       
      //
    }

    //
    public function sendPayment($data)
    {
        //create payment
        $response = $this->buildRequest('send-payment','POST',$data);
    }

   
}
