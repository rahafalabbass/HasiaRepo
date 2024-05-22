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

    
}
