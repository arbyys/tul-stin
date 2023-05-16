@extends('layouts.app')

@section('title', 'historie plateb')

@section('content')
    <h3 class="mb-3">Historie plateb</h3>
    <div class="accordion" id="accordion">
    @forelse($accounts as $iban => $account)
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button @if(!$loop->first)collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $iban }}" aria-expanded="@if($loop->first)true @else false @endif" aria-controls="collapse-{{ $iban }}">
                    Účet {{ $iban }} - měna {{ $account[0]->currency_code }}
                </button>
            </h2>
            <div id="collapse-{{ $iban }}" class="accordion-collapse collapse @if($loop->first)show @endif" data-bs-parent="#accordion">
                <div class="accordion-body">
                    <ul class="list-group">
                        @php
                        $account = $account->forget($account->keys()->first())
                        @endphp

                        @forelse($account as $payment)
                            <li class="list-group-item">
                                Platba {{ $payment->amount }}, {{ $payment->created_at }}
                            </li>
                        @empty
                            <span class="text-danger">Tento účet neobsahuje žádné platby</span>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    @empty
        Nemáte vytvořený žádný bankovní účet
    @endforelse
    </div>
@endsection
