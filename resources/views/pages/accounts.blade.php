@extends('layouts.app')

@section('title', 'správa účtů')

@section('content')
    <div class="container">
        <h2>Nový účet</h2>
        <hr>
        <form class="w-md-50">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Měna</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <button type="submit" class="btn btn-primary">Potvrdit</button>
        </form>

        <hr>

        @php
            App\Services\CurrencyService::updateExchangeRates()
        @endphp
    </div>
@endsection
