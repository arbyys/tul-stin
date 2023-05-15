<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return view('pages.accounts', [
            "currencies" => $currencies
        ]);
    }

    public function create(Request $request)
    {
        if(!isset($request->currency) || Currency::find($request->currency)->count() <= 0) {
            return redirect()->back()->withErrors([
                "msg" => "Musíte specifikovat měnu"
            ]);
        }

        $account = new Account();
        $account->fill([
            'user_id' => Auth::id(),
            'currency_code' => $request->currency,
        ]);
        $account->save();
        return redirect()->back()->with([
            "success" => "Bankovní účet úspěšně založen"
        ]);
    }
}
