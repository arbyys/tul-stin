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
        $accounts = Account::where("user_id", Auth::id())->get();

        return view('pages.accounts', [
            "currencies" => $currencies,
            "accounts" => $accounts
        ]);
    }

    public function create(Request $request)
    {
        if (!isset($request->currency) || Currency::find($request->currency)->count() <= 0) {
            return redirect()->back()->withErrors([
                "msg" => "Musíte specifikovat měnu"
            ]);
        }
        $czechAccountExists = Account::where("user_id", Auth::id())->where("currency_code", "CZK")->count() >= 1;
        if (!$czechAccountExists && $request->currency != "CZK") {
            return redirect()->back()->withErrors([
                "msg" => "Na vaše jméno ještě neexistuje bankovní účet s měnou CZK. Musíte založit nejdříve ten."
            ]);
        }
        $dupliciteAccountExists = Account::where("user_id", Auth::id())->where("currency_code", $request->currency)->count() >= 1;
        if ($dupliciteAccountExists) {
            return redirect()->back()->withErrors([
                "msg" => "Nelze mít více účtů na stejnou měnu."
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

    public function remove(Request $request)
    {
        $account = Account::find($request->iban);
        if(is_null($account)) {
            return redirect()->back()->withErrors([
                "msg" => "Tento účet nebyl nalezen"
            ]);
        }

        $account->delete();
        return redirect()->back()->with([
            "success" => "Bankovní účet úspěšně odstraněn"
        ]);
    }
}
