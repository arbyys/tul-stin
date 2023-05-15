@extends('layouts.app')

@section('title', 'správa účtů')

@section('content')
    <h3>Nový bankovní účet</h3>
    <hr>
    <form class="w-md-50" method="POST" action="{{ route('create_account') }}">
        @csrf
        <div class="mb-3">
            <select name="currency" required class="form-select" aria-label="Měna účtu">
                <option value="" selected disabled>Vyberte měnu účtu</option>
                @foreach($currencies as $currency)
                    <option value="{{ $currency->code }}">{{ $currency->code }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Potvrdit</button>
    </form>

    <hr>
    <h3>Vaše účty</h3>

    <ul class="list-group">
    @forelse($accounts as $account)
        <li class="list-group-item d-flex justify-content-between">
            <span>
                Účet č. <b>{{ $account->iban }}</b> s měnou {{ $account->currency_code }} a zůstatkem {{ $account->balance }},-
            </span>
            <div>
                <form method="post" action="{{ route('remove_account') }}">
                    @csrf
                    <input type="hidden" name="iban" value="{{ $account->iban }}">
                    <button type="submit" class="btn btn-danger">Smazat</button>
                </form>
            </div>
        </li>
    @empty
        Nemáte vytvořený žádný bankovní účet
    @endforelse
    </ul>
@endsection
