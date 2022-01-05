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

            <p class="login-box-msg" style="font-weight: bold">Autenticazione a 2 fattori</p>
            <hr>
            <div class="clear_both">
                <div class="col img">
                    <img src="{{ asset("storage/img/general/google-2fa.png") }}" style="width: 100%; max-width: 200px">
                </div>
                <div class="col des">
                    <p style="font-weight: bold">Inserisci il codice di verifica</p>
                    <p>Inserisci il codice di verifica a 6 cifre generato dall'app scaricata sul tuo smartphone</p>
                    <form class="form-horizontal" id="sendotp" method="POST" action="{{ route('2fa') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <input id="one_time_password" type="number" class="form-control" name="one_time_password" required autofocus>
                            </div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">Verifica codice di attivazione</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <a href="{{ url('/logout') }}" class="btn btn-default btn-block" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><strong>{{ __("Annulla") }}</strong></a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
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

