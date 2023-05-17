<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $accounts = Account::where("user_id", Auth::id())->get();

        return view('pages.home', [
            "accounts" => $accounts
        ]);
    }
}
