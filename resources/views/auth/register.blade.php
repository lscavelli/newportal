@extends('auth.master')
@section('body')
    <body class="hold-transition login-page">
    <div class="login-box">
        @include('ui.messages')
        <div class="login-logo">
            <a href="#"><b>Newportal</b> Platform</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in per avviare la tua sessione</p>

            <form action="{{ url('/register') }}" role="form" method="POST">
                @csrf

                <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }} has-feedback">
                    <input id="nome" name="nome" type="text" class="form-control" placeholder="Nome" value="{{ old('nome') }}" required autofocus>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('nome'))
                        <span class="help-block">
                            <strong>{{ $errors->first('nome') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('cognome') ? ' has-error' : '' }} has-feedback">
                    <input id="cognome" name="cognome" type="text" class="form-control" placeholder="Cognome" value="{{ old('cognome') }}" required autofocus>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('cognome'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cognome') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
                    <input  id="password" type="password" class="form-control" name="password" required placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }} has-feedback">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Conferma Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                             </span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> Accetto i <a href="#">termini</a>
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.login-box-body -->
        <br />
        <div style="text-align: center;">
            <a href="{{ url('/login') }}" class="text-center">Sono già registrato</a>
        </div>
    </div>
    <!-- /.login-box -->
@endsection

@section('style')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset("/node_modules/admin-lte/plugins/iCheck/square/blue.css")}}">
@stop
@section('scripts')
    <!-- iCheck -->
    <script src="{{asset("/node_modules/admin-lte/plugins/iCheck/icheck.min.js")}}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@stop

