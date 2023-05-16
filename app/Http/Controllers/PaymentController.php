<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Currency;
use App\Services\CurrencyService;
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
        CurrencyService::updateExchangeRates();
        $currencies = Currency::all()->sortBy("code");

        return view('pages.incoming-payment', [
            "currencies" => $currencies,
        ]);
    }

    public function indexOutcoming()
    {
        CurrencyService::updateExchangeRates();
        $currencies = Currency::all()->sortBy("code");

        return view('pages.outcoming-payment', [
            "currencies" => $currencies,
        ]);
    }

    public function newIncomingPayment(Request $request) {
        $validatedData = $request->validate([
            'amount' => 'required|integer|min:1',
            'currency' => 'required|exists:currencies,code',
        ]);

        $currency = $validatedData["currency"];
        $amount = $validatedData["amount"];

        CurrencyService::updateExchangeRates();

        if (!Auth::user()->hasAccount("CZK"))
        {
            return redirect()->back()->withErrors([
                "msg" => "Nemáte bankovní účet s CZK. Bez jeho založení nelze provádět platby."
            ]);
        }

        if (Auth::user()->hasAccount($currency))
        {
            $account = Account::where("user_id", Auth::id())
                ->where("currency_code", $currency)
                ->first();
            $result = $account->makePayment($amount);

            if ($result)
            {
                return redirect()->back()->with([
                    "success" => "Platba úspěšně zpracována v měně {$currency}"
                ]);
            }
            else
            {
                return redirect()->back()->withErrors([
                    "msg" => "Při platbě se vyskytla neznámá chyba."
                ]);
            }
        }
        else
        {
            $convertedAmount = Currency::convertToCZK($currency, $amount);

            $account = Account::where("user_id", Auth::id())
                ->where("currency_code", "CZK")
                ->first();
            $result = $account->makePayment($convertedAmount);
            if ($result)
            {
                return redirect()->back()->with([
                    "success" => "Nemáte účet s měnou {$currency}. Částa byla konvertována a platba bylo přičtena na váš účet s měnou CZK."
                ]);
            }
            else
            {
                return redirect()->back()->withErrors([
                    "msg" => "Při platbě se vyskytla neznámá chyba."
                ]);
            }
        }

    }
    public function newOutcomingPayment(Request $request) {
        $validatedData = $request->validate([
            'amount' => 'required|integer|min:1',
            'currency' => 'required|exists:currencies,code',
        ]);

        $currency = $validatedData["currency"];
        $amount = $validatedData["amount"];

        CurrencyService::updateExchangeRates();

        if (!Auth::user()->hasAccount("CZK"))
        {
            return redirect()->back()->withErrors([
                "msg" => "Nemáte bankovní účet s CZK. Bez jeho založení nelze provádět platby."
            ]);
        }

        if (Auth::user()->hasAccountAndEnoughMoney($currency, $amount))
        {
            $account = Account::where("user_id", Auth::id())
                    ->where("currency_code", $currency)
                    ->first();
            $result = $account->makePayment(-$amount);
            if ($result)
            {
                return redirect()->back()->with([
                    "success" => "Platba úspěšně zpracována v měně {$currency}"
                ]);
            }
            else
            {
                return redirect()->back()->withErrors([
                    "msg" => "Při platbě se vyskytla neznámá chyba."
                ]);
            }
        }
        else
        {
            if ($currency == "CZK")
            {
                return redirect()->back()->withErrors([
                    "msg" => "Na vašem CZK účtu nemáte dost prostředků."
                ]);
            }

            $convertedAmount = Currency::convertToCZK($currency, $amount);
            if (!is_null($convertedAmount) && Auth::user()->hasAccountAndEnoughMoney("CZK", $convertedAmount))
            {
                $account = Account::where("user_id", Auth::id())
                    ->where("currency_code", "CZK")
                    ->first();
                $result = $account->makePayment(-$convertedAmount);
                if ($result)
                {
                    return redirect()->back()->with([
                        "success" => "Váš účet s měnou {$currency} neexistuje / nemá dost prostředků. Částa byla konvertována a platba bylo provedna z vašeho účet s měnou CZK."
                    ]);
                }
                else
                {
                    return redirect()->back()->withErrors([
                        "msg" => "Při platbě se vyskytla neznámá chyba."
                    ]);
                }
            }
            else
            {
                return redirect()->back()->withErrors([
                    "msg" => "Váš účet s měnou {$currency} neexistuje / nemá dost prostředků. Váš CZK účet také neobsahuje dostatek prostředků, aby mohla být částka po konverzi strhnuta z něj."
                ]);
            }
        }
    }
}
