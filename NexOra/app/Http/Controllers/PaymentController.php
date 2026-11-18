<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           
    }
}

/*
make index treturn just info 
    -Successful payments
    -Pending payments
    -Failed payments
    -Total revenue
    - chart Successful payment amounts · Last 7 days or depain on fillter 

make data return paymints => return all paymint in with fillter 
make onther one for detals pymint => get{id}
*/
