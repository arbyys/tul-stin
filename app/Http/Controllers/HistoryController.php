<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Payment;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $accounts = Account::all();

        $accountPayments = [];

        foreach ($accounts as $account) {
            $accountPayments[$account->iban] = [
                'account' => $account,
                'payments' => $account->payments()->orderByDesc('created_at')->get(),
            ];
        }


        return view('pages.history', [
            "accountPayments" => $accountPayments
        ]);
    }
}
