@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center " style="height: 70vh;">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading font-weight-bold">Dvoufaktorové ověření</div>
                    <hr>
                    @if($errors->any())
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <strong>{{$errors->first()}}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('2fa') }}">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <p>Prosím zadejte heslo, které vygenerovala vaše autentifikační aplikace. Ujistěte se, že zadáváte aktuální heslo - nové se generuje každých 30sek. </p>
                                <label for="one_time_password" class="col-md-4 control-label">Ověřovací heslo:</label>


                                <div class="col-md-6">
                                    <input id="one_time_password" type="number" class="form-control" name="one_time_password" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        Přihlásit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
