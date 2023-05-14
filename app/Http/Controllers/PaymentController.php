<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function indexIncoming()
    {
        return view('pages.incoming-payment');
    }

    public function indexOutcoming()
    {
        return view('pages.outcoming-payment');
    }
}
