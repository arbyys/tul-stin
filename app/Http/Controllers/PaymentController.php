<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexIncoming()
    {
        $currencies = Currency::all();

        return view('pages.incoming-payment', [
            "currencies" => $currencies,
        ]);
    }

    public function indexOutcoming()
    {
        $currencies = Currency::all();

        return view('pages.outcoming-payment', [
            "currencies" => $currencies,
        ]);
    }
}
