@extends('layouts.app')

@section('title', 'správa účtů')

@section('content')
    <h2>Nový bankovní účet</h2>
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
@endsection
