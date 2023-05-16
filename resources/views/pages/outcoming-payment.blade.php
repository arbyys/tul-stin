@extends('layouts.app')

@section('title', 'odchozí platba')

@section('content')
    <form action="{{ route('new_outcoming_payment') }}" method="POST">
        @csrf
    </form>
@endsection
