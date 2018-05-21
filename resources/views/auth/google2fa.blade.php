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

            <p class="login-box-msg">Autenticazione a 2 fattori</p>

            <form action="{{ url('/register') }}" role="form" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group">
                        <label for="one_time_password" class="col-md-4 control-label">One Time Password</label>

                        <div class="col-md-8">
                            <input id="one_time_password" type="number" class="form-control" name="one_time_password" required autofocus>
                        </div>
                    </div>
                </div>
                <p>Scansione il QR Code con Google Authenticator App {{ $data['google2fa_secret'] }}</p>
                <div><img src="{{ $src_qrcode }}"></div>

                <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
    </div>
    <!-- /.login-box -->
@endsection

@section('style')
@stop
@section('scripts')
@stop

