@extends('auth.master')
@section('body')
    <body class="hold-transition login-page">
    <div style="width: 50%; margin: 2% auto 7% auto;">
        @include('ui.messages')
        <div class="login-logo">
            <a href="#"><b>Newportal</b> Platform</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">

            <p class="login-box-msg" style="font-weight: bold">Configurazione autenticazione a 2 fattori</p>
            <hr>
            <div class="clear_both">
                <div class="col img">
                    <img src="{{ asset("storage/img/general/2fa_download.png") }}" style="width: 100%; max-width: 200px">
                </div>
                <div class="col des">
                    <p>Passaggio 1</p>
                    <p style="font-weight: bold">Scarica l'app</p>
                    <p>Scarica sul tuo smartphone l'app (es. Google Authenticator) per generare la One Time Password richiesta nel terzo passaggio</p>
                </div>
                <hr>
            </div>
            <div class="clear_both">
                <div class="col img">
                    <img src="{{ $src_qrcode }}" style="width: 100%; max-width: 200px">
                </div>
                <div class="col des">
                <p>Passaggio 2</p>
                <p style="font-weight: bold">Scansiona il QR Code</p>
                <p>Scansione il QR Code mostrato a sinistra, utilizzando l'app scaricata sul tuo smartphone.
                    Nel caso risulti impossibile effettuare la scansione, effettua nell'app l'inserimento manuale del
                    seguente codice basato sull'ora {{ $secret }}</p>
                </div>
                <hr>
            </div>
            <div class="clear_both">
                <div class="col img">
                    <img src="{{ asset("storage/images/general/google-2fa.png") }}" style="width: 100%; max-width: 200px">
                </div>
                <div class="col des">
                    <p>Passaggio 3</p>
                    <p style="font-weight: bold">Inserisci il codice di verifica</p>
                    <p>Inserisci il codice di verifica a 6 cifre generato dall'app scaricata sul tuo smartphone</p>
                    {!! Form::model($user, ['url' => '/admin/users/active2fa/'.$user->id,'class' => 'form-horizontal', 'id'=>'sendotp']) !!}
                        <div class="row">
                            <div class="col-md-4">
                                <input id="one_time_password" type="number" class="form-control" name="one_time_password" required autofocus>
                            </div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">Verifica codice di attivazione</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    {!! Form::close() !!}
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <a href="/admin/users/{!! $user->id !!}/edit" class="btn btn-default btn-block"><b>{{ __("Annulla") }}</b></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.login-box -->
@endsection

@section('style')
    <style>
        hr {
            border: none;
            border-top: 1px solid #e8e8e8;
            margin: 2rem auto;
            clear: both;
        }
        .img {
            width: 30%;
        }
        .des {
            width: 70%;
        }
        .col {
            display: block;
            float: left;
            margin: 0 0 2%;
            padding-right: 2%;
        }
        .clear_both {
            clear: both;
        }
    </style>
@stop
@section('scripts')
    <script>
        $('#one_time_password').on('keyup', function() {
            if(this.value.length==6) $('#sendotp').submit();
        });
    </script>
@stop

