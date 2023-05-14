@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="card card-default">
                    <h4 class="card-heading text-center mt-4">Nastavení dvoufaktorového ověření</h4>

                    <div class="card-body" style="text-align: center;">
                        <p>Stáhněte si aplikaci Google Authenticator a naskenujte QR kód níže. Pokud naskenování QR kódu nefunguje, použijte tento kód: <strong>{{ $secret }}</strong></p>
                        <div>
                            {!! $QR_Image !!}
                        </div>

                        <p>Každý účet musí mít aktivované dvoufaktorové ověření. Bez dvoufaktorového ověření se nelze přihlásit a používat aplikaci.</p>
                        <div>
                            <a href="{{ route('complete.registration') }}" class="btn btn-primary">Dokončit registraci</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
