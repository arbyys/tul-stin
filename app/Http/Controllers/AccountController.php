<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        $validatedData = $request->validate([
            'currency' => 'required|exists:currencies,code',
        ]);

        $currency = $validatedData["currency"];

        $czechAccountExists = Account::where("user_id", Auth::id())->where("currency_code", "CZK")->count() >= 1;
        if (!$czechAccountExists && $currency != "CZK") {
            return redirect()->back()->withErrors([
                "msg" => "Na vaše jméno ještě neexistuje bankovní účet s měnou CZK. Musíte založit nejdříve ten."
            ]);
        }
        $dupliciteAccountExists = Account::where("user_id", Auth::id())->where("currency_code", $currency)->count() >= 1;
        if ($dupliciteAccountExists) {
            return redirect()->back()->withErrors([
                "msg" => "Nelze mít více účtů na stejnou měnu."
            ]);
        }

        $account = new Account();
        $account->fill([
            'user_id' => Auth::id(),
            'currency_code' => $currency,
        ]);
        $account->save();
        return redirect()->back()->with([
            "success" => "Bankovní účet úspěšně založen"
        ]);
    }

    public function remove(Request $request)
    {
        $validatedData = $request->validate([
            'iban' => 'required|exists:accounts,iban',
        ]);

        $iban = $validatedData["iban"];

        $account = Account::find($iban);

        if($account->currency_code == "CZK" && !Auth::user()->hasOnlyCZKAccount())
        {
            return redirect()->back()->withErrors([
                "msg" => "Nelze smazat váš CZK účet, pokud máte vytvořeny ještě další účty v ostatních měnách."
            ]);
        }
        $result = $account->delete();

        if($result)
        {
            return redirect()->back()->with([
                "success" => "Bankovní účet úspěšně odstraněn"
            ]);
        }
        return redirect()->back()->withErrors([
            "msg" => "Při mazání účtu se vyskytla neznámá chyba."
        ]);

    }
}
