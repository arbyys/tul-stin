@extends('layouts.app')

@section('title', 'historie plateb')

@section('content')
    <h3 class="mb-3">Historie plateb</h3>
    <div class="accordion" id="accordion">
    @forelse($accountPayments as $iban => $data)
        @php
            $account = $data["account"];
            $payments = $data["payments"];
        @endphp
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button @if(!$loop->first)collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $account->iban }}" aria-expanded="@if($loop->first)true @else false @endif" aria-controls="collapse-{{ $account->iban }}">
                    Účet {{ $account->iban }} - zůstatek {{ $account->balance }} {{ $account->currency_code }}
                </button>
            </h2>
            <div id="collapse-{{ $iban }}" class="accordion-collapse collapse @if($loop->first)show @endif" data-bs-parent="#accordion">
                <div class="accordion-body">
                    <ul class="list-group">
                        @forelse($payments as $payment)
                            <li class="list-group-item @if($payment->amount < 0) payment-outcoming @else payment-incoming @endif">
                                <span class="payment-big">{{ $payment->amount }} {{ $account->currency_code }}</span> <br>
                                {{ date_format(date_create($payment->created_at),"d.m.Y, H:i") }}
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
