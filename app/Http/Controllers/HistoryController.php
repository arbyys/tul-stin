<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //$accounts = Account::all();
        $accounts = Account::leftJoin('payments', 'accounts.iban', '=', 'payments.account_iban')
            ->select('accounts.*', 'payments.amount', 'payments.created_at')
            ->get()
            ->groupBy('iban');
        //dd($accounts);
        return view('pages.history', [
            "accounts" => $accounts
        ]);
    }
}
