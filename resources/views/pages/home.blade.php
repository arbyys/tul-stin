@extends('layouts.app')

@section('title', 'úvod')

@section('content')
<div class="card-group">
    <div class="card action-item">
        <a href="{{ route('accounts') }}" class="text-reset text-decoration-none">
            <div class="card-header primary-colored-header">
                Správa bankovních účtů
            </div>
            <div class="card-body text-center">
                <p class="card-text">
                    Vytvoření nových bankovních účtů, správa měn, správa existujících bankovních účtů
                </p>
            </div>
        </a>
    </div>
    <div class="card action-item">
        <a href="{{ route('history') }}" class="text-reset text-decoration-none">
            <div class="card-header primary-colored-header">
                Historie plateb
            </div>
            <div class="card-body text-center">
                <p class="card-text">
                    Přehledný výpis historie příchozích a odchozích plateb napříč bankovními účty
                </p>
            </div>
        </a>
    </div>
    <div class="card action-item">
        <a href="{{ route('outcoming-payment') }}" class="text-reset text-decoration-none">
            <div class="card-header primary-colored-header">
                Zaplatit z účtu
            </div>
            <div class="card-body text-center">
                <p class="card-text">
                    Funkce pro simulaci odchozí platby z účtu
                </p>
            </div>
        </a>
    </div>
    <div class="card action-item">
        <a href="{{ route('incoming-payment') }}" class="text-reset text-decoration-none">
            <div class="card-header primary-colored-header">
                Přijmout platbu na účet
            </div>
            <div class="card-body text-center">
                <p class="card-text">
                    Funkce pro simulaci příchozí platby na účet
                </p>
            </div>
        </a>
    </div>
</div>

<hr class="mt-5 mb-5">

<h3>Bankovní účty</h3>

<ul class="list-group">
    @forelse($accounts as $account)
        <li class="list-group-item d-flex justify-content-between">
            <span>
                Účet č. <b>{{ $account->iban }}</b> s měnou {{ $account->currency_code }} a zůstatkem {{ $account->balance }},-
            </span>
        </li>
    @empty
        Nemáte vytvořený žádný bankovní účet
    @endforelse
</ul>
@endsection
